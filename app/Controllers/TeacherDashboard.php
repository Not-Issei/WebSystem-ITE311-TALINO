<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TeacherDashboard extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    /**
     * Check if user is logged in and has teacher role
     */
    private function checkTeacherAccess()
    {
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/login');
        }

        if ($this->session->get('role') !== 'teacher') {
            $this->session->setFlashdata('error', 'Access denied. Teacher privileges required.');
            return redirect()->to('/dashboard');
        }

        return null;
    }

    /**
     * Teacher Dashboard
     */
    public function index()
    {
        $redirect = $this->checkTeacherAccess();
        if ($redirect) return $redirect;

        // Get statistics for teacher dashboard
        $stats = $this->getTeacherStats();

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'stats' => $stats
        ];

        return view('dashboards/teacher', $data);
    }

    /**
     * Course Management
     */
    public function courses()
    {
        $redirect = $this->checkTeacherAccess();
        if ($redirect) return $redirect;

        $courses = [];
        // Get courses taught by this teacher (if courses table exists)
        if ($this->db->tableExists('courses')) {
            $builder = $this->db->table('courses');
            
            // Check if teacher_id column exists
            $fields = $this->db->getFieldNames('courses');
            if (in_array('teacher_id', $fields)) {
                $courses = $builder->where('teacher_id', $this->session->get('user_id'))
                                 ->orderBy('created_at', 'DESC')
                                 ->get()
                                 ->getResultArray();
            } else {
                // If no teacher_id column, show all courses for now
                $courses = $builder->orderBy('created_at', 'DESC')
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
            'courses' => $courses
        ];

        return view('dashboards/teacher_courses', $data);
    }

    /**
     * Student Management
     */
    public function students()
    {
        $redirect = $this->checkTeacherAccess();
        if ($redirect) return $redirect;

        $students = [];
        // Get students enrolled in teacher's courses
        if ($this->db->tableExists('enrollments') && $this->db->tableExists('courses')) {
            $builder = $this->db->table('enrollments e');
            $students = $builder->select('u.id, u.name, u.email, c.title as course_title, e.enrolled_at')
                              ->join('users u', 'u.id = e.student_id')
                              ->join('courses c', 'c.id = e.course_id')
                              ->where('c.teacher_id', $this->session->get('user_id'))
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
            'students' => $students
        ];

        return view('dashboards/teacher_students', $data);
    }

    /**
     * Grades Management
     */
    public function grades()
    {
        $redirect = $this->checkTeacherAccess();
        if ($redirect) return $redirect;

        $submissions = [];
        // Get submissions for teacher's courses
        if ($this->db->tableExists('submissions') && $this->db->tableExists('quizzes') && $this->db->tableExists('courses')) {
            $builder = $this->db->table('submissions s');
            $submissions = $builder->select('s.*, u.name as student_name, q.title as quiz_title, c.title as course_title')
                                 ->join('users u', 'u.id = s.student_id')
                                 ->join('quizzes q', 'q.id = s.quiz_id')
                                 ->join('courses c', 'c.id = q.course_id')
                                 ->where('c.teacher_id', $this->session->get('user_id'))
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

        return view('dashboards/teacher_grades', $data);
    }

    /**
     * Get teacher statistics
     */
    private function getTeacherStats()
    {
        $stats = [
            'total_courses' => 0,
            'total_students' => 0,
            'total_quizzes' => 0,
            'pending_grades' => 0
        ];

        try {
            $teacherId = $this->session->get('user_id');

            // Check if courses table has teacher_id column
            $hasTeacherId = false;
            if ($this->db->tableExists('courses')) {
                $fields = $this->db->getFieldNames('courses');
                $hasTeacherId = in_array('teacher_id', $fields);
            }

            // Total courses taught
            if ($this->db->tableExists('courses')) {
                $builder = $this->db->table('courses');
                if ($hasTeacherId) {
                    $stats['total_courses'] = $builder->where('teacher_id', $teacherId)->countAllResults();
                } else {
                    // If no teacher_id column, show all courses for now
                    $stats['total_courses'] = $builder->countAllResults();
                }
            }

            // Total students enrolled
            if ($this->db->tableExists('enrollments') && $this->db->tableExists('courses')) {
                $builder = $this->db->table('enrollments e');
                if ($hasTeacherId) {
                    $stats['total_students'] = $builder->join('courses c', 'c.id = e.course_id')
                                                     ->where('c.teacher_id', $teacherId)
                                                     ->countAllResults();
                } else {
                    // If no teacher_id, count all enrolled students
                    $stats['total_students'] = $builder->countAllResults();
                }
            }

            // Total quizzes created
            if ($this->db->tableExists('quizzes') && $this->db->tableExists('courses')) {
                $builder = $this->db->table('quizzes q');
                if ($hasTeacherId) {
                    $stats['total_quizzes'] = $builder->join('courses c', 'c.id = q.course_id')
                                                    ->where('c.teacher_id', $teacherId)
                                                    ->countAllResults();
                } else {
                    // If no teacher_id, count all quizzes
                    $stats['total_quizzes'] = $builder->countAllResults();
                }
            }

            // Pending submissions to grade
            if ($this->db->tableExists('submissions') && $this->db->tableExists('quizzes') && $this->db->tableExists('courses')) {
                $builder = $this->db->table('submissions s');
                if ($hasTeacherId) {
                    $stats['pending_grades'] = $builder->join('quizzes q', 'q.id = s.quiz_id')
                                                      ->join('courses c', 'c.id = q.course_id')
                                                      ->where('c.teacher_id', $teacherId)
                                                      ->where('s.grade IS NULL')
                                                      ->countAllResults();
                } else {
                    // If no teacher_id, count all pending submissions
                    $stats['pending_grades'] = $builder->where('grade IS NULL')->countAllResults();
                }
            }
        } catch (\Exception $e) {
            // If there's any database error, return default stats
            log_message('error', 'Teacher stats error: ' . $e->getMessage());
        }

        return $stats;
    }
}
