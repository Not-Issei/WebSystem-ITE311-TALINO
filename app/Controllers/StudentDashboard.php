<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class StudentDashboard extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    /**
     * Check if user is logged in and has student role
     */
    private function checkStudentAccess()
    {
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/login');
        }

        if ($this->session->get('role') !== 'student') {
            $this->session->setFlashdata('error', 'Access denied. Student privileges required.');
            return redirect()->to('/dashboard');
        }

        return null;
    }

    /**
     * Student Dashboard
     */
    public function index()
    {
        $redirect = $this->checkStudentAccess();
        if ($redirect) return $redirect;

        // Get statistics for student dashboard
        $stats = $this->getStudentStats();

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'stats' => $stats
        ];

        return view('dashboards/student', $data);
    }

    /**
     * My Courses
     */
    public function courses()
    {
        $redirect = $this->checkStudentAccess();
        if ($redirect) return $redirect;

        $courses = [];
        // Get courses enrolled by this student
        if ($this->db->tableExists('enrollments') && $this->db->tableExists('courses')) {
            $builder = $this->db->table('enrollments e');
            $courses = $builder->select('c.*, u.name as teacher_name, e.enrolled_at')
                             ->join('courses c', 'c.id = e.course_id')
                             ->join('users u', 'u.id = c.teacher_id')
                             ->where('e.student_id', $this->session->get('user_id'))
                             ->orderBy('e.enrolled_at', 'DESC')
                             ->get()
                             ->getResultArray();
        }

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'courses' => $courses
        ];

        return view('dashboards/student_courses', $data);
    }

    /**
     * Available Courses (for enrollment)
     */
    public function browse()
    {
        $redirect = $this->checkStudentAccess();
        if ($redirect) return $redirect;

        $availableCourses = [];
        // Get courses not yet enrolled by this student
        if ($this->db->tableExists('courses')) {
            $studentId = $this->session->get('user_id');
            
            if ($this->db->tableExists('enrollments')) {
                // Get courses not enrolled
                $builder = $this->db->table('courses c');
                $availableCourses = $builder->select('c.*, u.name as teacher_name')
                                          ->join('users u', 'u.id = c.teacher_id')
                                          ->where('c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ' . $studentId . ')')
                                          ->orderBy('c.created_at', 'DESC')
                                          ->get()
                                          ->getResultArray();
            } else {
                // If no enrollments table, show all courses
                $builder = $this->db->table('courses c');
                $availableCourses = $builder->select('c.*, u.name as teacher_name')
                                          ->join('users u', 'u.id = c.teacher_id')
                                          ->orderBy('c.created_at', 'DESC')
                                          ->get()
                                          ->getResultArray();
            }
        }

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'courses' => $availableCourses
        ];

        return view('dashboards/student_browse', $data);
    }

    /**
     * My Grades
     */
    public function grades()
    {
        $redirect = $this->checkStudentAccess();
        if ($redirect) return $redirect;

        $submissions = [];
        // Get student's submissions and grades
        if ($this->db->tableExists('submissions') && $this->db->tableExists('quizzes') && $this->db->tableExists('courses')) {
            $builder = $this->db->table('submissions s');
            $submissions = $builder->select('s.*, q.title as quiz_title, c.title as course_title, c.id as course_id')
                                 ->join('quizzes q', 'q.id = s.quiz_id')
                                 ->join('courses c', 'c.id = q.course_id')
                                 ->where('s.student_id', $this->session->get('user_id'))
                                 ->orderBy('s.submitted_at', 'DESC')
                                 ->get()
                                 ->getResultArray();
        }

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'submissions' => $submissions
        ];

        return view('dashboards/student_grades', $data);
    }

    /**
     * Get student statistics
     */
    private function getStudentStats()
    {
        $stats = [];
        $studentId = $this->session->get('user_id');

        // Enrolled courses
        if ($this->db->tableExists('enrollments')) {
            $builder = $this->db->table('enrollments');
            $stats['enrolled_courses'] = $builder->where('student_id', $studentId)->countAllResults();
        } else {
            $stats['enrolled_courses'] = 0;
        }

        // Completed assignments/quizzes
        if ($this->db->tableExists('submissions')) {
            $builder = $this->db->table('submissions');
            $stats['completed_assignments'] = $builder->where('student_id', $studentId)
                                                    ->where('grade IS NOT NULL')
                                                    ->countAllResults();
        } else {
            $stats['completed_assignments'] = 0;
        }

        // Pending assignments
        if ($this->db->tableExists('submissions') && $this->db->tableExists('quizzes') && $this->db->tableExists('enrollments')) {
            // This would require more complex logic to determine pending assignments
            // For now, we'll set it to 0
            $stats['pending_assignments'] = 0;
        } else {
            $stats['pending_assignments'] = 0;
        }

        // Average grade
        if ($this->db->tableExists('submissions')) {
            $builder = $this->db->table('submissions');
            $result = $builder->selectAvg('grade')
                            ->where('student_id', $studentId)
                            ->where('grade IS NOT NULL')
                            ->get()
                            ->getRowArray();
            $stats['average_grade'] = $result['grade'] ? round($result['grade'], 1) : 0;
        } else {
            $stats['average_grade'] = 0;
        }

        return $stats;
    }

    /**
     * Enroll in a course
     */
    public function enroll()
    {
        $redirect = $this->checkStudentAccess();
        if ($redirect) return $redirect;

        if ($this->request->getMethod() === 'POST') {
            $courseId = $this->request->getPost('course_id');
            $studentId = $this->session->get('user_id');

            // Check if enrollment table exists
            if (!$this->db->tableExists('enrollments')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Enrollment system not available']);
            }

            // Check if already enrolled
            $builder = $this->db->table('enrollments');
            $existing = $builder->where('student_id', $studentId)
                              ->where('course_id', $courseId)
                              ->get()
                              ->getRowArray();

            if ($existing) {
                return $this->response->setJSON(['success' => false, 'message' => 'Already enrolled in this course']);
            }

            // Enroll student
            $enrollmentData = [
                'student_id' => $studentId,
                'course_id' => $courseId,
                'enrolled_at' => date('Y-m-d H:i:s')
            ];

            $builder = $this->db->table('enrollments');
            if ($builder->insert($enrollmentData)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Successfully enrolled in course']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to enroll in course']);
            }
        }

        return $this->response->setStatusCode(405);
    }
}
