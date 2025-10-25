<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\MaterialModel;
use CodeIgniter\HTTP\ResponseInterface;

class StudentDashboard extends BaseController
{
    protected $db;
    protected $session;
    protected $courseModel;
    protected $enrollmentModel;
    protected $materialModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->materialModel = new MaterialModel();
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

        $userId = $this->session->get('user_id');

        // Initialize arrays to prevent errors
        $enrolledCourses = [];
        $availableCourses = [];
        $enrollmentStats = [
            'total_enrolled' => 0,
            'completed_courses' => 0,
            'dropped_courses' => 0,
            'recent_enrollments' => 0
        ];

        try {
            // Get enrolled courses
            $enrolledCourses = $this->enrollmentModel->getUserEnrollments($userId);
            
            // Get available courses for enrollment
            $availableCourses = $this->courseModel->getAvailableCoursesForUser($userId);
            
            // Get enrollment statistics
            $enrollmentStats = $this->enrollmentModel->getUserEnrollmentStats($userId);
        } catch (\Exception $e) {
            log_message('error', 'Student dashboard enrollment error: ' . $e->getMessage());
            // Continue with empty arrays - dashboard will still load
        }

        // Get general statistics for student dashboard
        $stats = $this->getStudentStats();

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'stats' => $stats,
            'enrolled_courses' => $enrolledCourses,
            'available_courses' => $availableCourses,
            'enrollment_stats' => $enrollmentStats
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
        try {
            if ($this->db->tableExists('enrollments') && $this->db->tableExists('courses')) {
                // Check if required columns exist
                $enrollmentFields = $this->db->getFieldNames('enrollments');
                $courseFields = $this->db->getFieldNames('courses');
                
                // Check for user column (student_id or user_id)
                $userColumn = null;
                if (in_array('student_id', $enrollmentFields)) {
                    $userColumn = 'student_id';
                } elseif (in_array('user_id', $enrollmentFields)) {
                    $userColumn = 'user_id';
                }
                
                $hasCourseId = in_array('course_id', $enrollmentFields);
                $hasTeacherId = in_array('teacher_id', $courseFields);
                
                if ($userColumn && $hasCourseId) {
                    $builder = $this->db->table('enrollments e');
                    if ($hasTeacherId) {
                        $courses = $builder->select('c.*, u.name as teacher_name, e.enrolled_at')
                                         ->join('courses c', 'c.id = e.course_id')
                                         ->join('users u', 'u.id = c.teacher_id')
                                         ->where("e.$userColumn", $this->session->get('user_id'))
                                         ->orderBy('e.enrolled_at', 'DESC')
                                         ->get()
                                         ->getResultArray();
                    } else {
                        $courses = $builder->select('c.*, e.enrolled_at')
                                         ->join('courses c', 'c.id = e.course_id')
                                         ->where("e.$userColumn", $this->session->get('user_id'))
                                         ->orderBy('e.enrolled_at', 'DESC')
                                         ->get()
                                         ->getResultArray();
                    }
                } else {
                    // If columns don't exist, show all courses
                    $builder = $this->db->table('courses');
                    $courses = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Student courses error: ' . $e->getMessage());
            $courses = [];
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
        try {
            if ($this->db->tableExists('courses')) {
                $studentId = $this->session->get('user_id');
                
                // Check if courses table has teacher_id column
                $courseFields = $this->db->getFieldNames('courses');
                $hasTeacherId = in_array('teacher_id', $courseFields);
                
                if ($this->db->tableExists('enrollments')) {
                    // Check if enrollments has required columns
                    $enrollmentFields = $this->db->getFieldNames('enrollments');
                    $hasStudentId = in_array('student_id', $enrollmentFields);
                    $hasCourseId = in_array('course_id', $enrollmentFields);
                    
                    if ($hasStudentId && $hasCourseId) {
                        // Get courses not enrolled (with proper column checking)
                        $builder = $this->db->table('courses c');
                        if ($hasTeacherId) {
                            $availableCourses = $builder->select('c.*, u.name as teacher_name')
                                                      ->join('users u', 'u.id = c.teacher_id')
                                                      ->where('c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ' . $studentId . ')')
                                                      ->orderBy('c.created_at', 'DESC')
                                                      ->get()
                                                      ->getResultArray();
                        } else {
                            $availableCourses = $builder->where('c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ' . $studentId . ')')
                                                      ->orderBy('c.created_at', 'DESC')
                                                      ->get()
                                                      ->getResultArray();
                        }
                    } else {
                        // If enrollment columns don't exist, show all courses
                        $builder = $this->db->table('courses c');
                        if ($hasTeacherId) {
                            $availableCourses = $builder->select('c.*, u.name as teacher_name')
                                                      ->join('users u', 'u.id = c.teacher_id')
                                                      ->orderBy('c.created_at', 'DESC')
                                                      ->get()
                                                      ->getResultArray();
                        } else {
                            $availableCourses = $builder->orderBy('c.created_at', 'DESC')
                                                      ->get()
                                                      ->getResultArray();
                        }
                    }
                } else {
                    // If no enrollments table, show all courses
                    $builder = $this->db->table('courses c');
                    if ($hasTeacherId) {
                        $availableCourses = $builder->select('c.*, u.name as teacher_name')
                                                  ->join('users u', 'u.id = c.teacher_id')
                                                  ->orderBy('c.created_at', 'DESC')
                                                  ->get()
                                                  ->getResultArray();
                    } else {
                        $availableCourses = $builder->orderBy('c.created_at', 'DESC')
                                                  ->get()
                                                  ->getResultArray();
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Student browse error: ' . $e->getMessage());
            $availableCourses = [];
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
        try {
            if ($this->db->tableExists('submissions') && $this->db->tableExists('quizzes') && $this->db->tableExists('courses')) {
                // Check if required columns exist
                $submissionFields = $this->db->getFieldNames('submissions');
                $hasStudentId = in_array('student_id', $submissionFields);
                $hasQuizId = in_array('quiz_id', $submissionFields);
                
                if ($hasStudentId && $hasQuizId) {
                    $builder = $this->db->table('submissions s');
                    $submissions = $builder->select('s.*, q.title as quiz_title, c.title as course_title, c.id as course_id')
                                         ->join('quizzes q', 'q.id = s.quiz_id')
                                         ->join('courses c', 'c.id = q.course_id')
                                         ->where('s.student_id', $this->session->get('user_id'))
                                         ->orderBy('s.submitted_at', 'DESC')
                                         ->get()
                                         ->getResultArray();
                } else {
                    // If columns don't exist, show empty submissions
                    $submissions = [];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Student grades error: ' . $e->getMessage());
            $submissions = [];
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
        $stats = [
            'enrolled_courses' => 0,
            'completed_assignments' => 0,
            'pending_assignments' => 0,
            'average_grade' => 0
        ];

        try {
            $studentId = $this->session->get('user_id');

            // Check column names in enrollments table
            $enrollmentUserColumn = null;
            if ($this->db->tableExists('enrollments')) {
                $fields = $this->db->getFieldNames('enrollments');
                if (in_array('student_id', $fields)) {
                    $enrollmentUserColumn = 'student_id';
                } elseif (in_array('user_id', $fields)) {
                    $enrollmentUserColumn = 'user_id';
                }
            }

            // Enrolled courses
            if ($this->db->tableExists('enrollments')) {
                $builder = $this->db->table('enrollments');
                if ($enrollmentUserColumn) {
                    $stats['enrolled_courses'] = $builder->where($enrollmentUserColumn, $studentId)->countAllResults();
                } else {
                    // If no user column, count all enrollments
                    $stats['enrolled_courses'] = $builder->countAllResults();
                }
            }

            // Check column names in submissions table
            $submissionUserColumn = null;
            $gradeColumn = null;
            if ($this->db->tableExists('submissions')) {
                $fields = $this->db->getFieldNames('submissions');
                if (in_array('student_id', $fields)) {
                    $submissionUserColumn = 'student_id';
                } elseif (in_array('user_id', $fields)) {
                    $submissionUserColumn = 'user_id';
                }
                
                if (in_array('grade', $fields)) {
                    $gradeColumn = 'grade';
                } elseif (in_array('score', $fields)) {
                    $gradeColumn = 'score';
                }
            }

            // Completed assignments/quizzes
            if ($this->db->tableExists('submissions')) {
                $builder = $this->db->table('submissions');
                if ($submissionUserColumn && $gradeColumn) {
                    $stats['completed_assignments'] = $builder->where($submissionUserColumn, $studentId)
                                                            ->where("$gradeColumn IS NOT NULL")
                                                            ->countAllResults();
                } elseif ($gradeColumn) {
                    // If no user column, count all completed submissions
                    $stats['completed_assignments'] = $builder->where("$gradeColumn IS NOT NULL")->countAllResults();
                } else {
                    // If no grade column, count all submissions
                    $stats['completed_assignments'] = $submissionUserColumn ? 
                        $builder->where($submissionUserColumn, $studentId)->countAllResults() :
                        $builder->countAllResults();
                }
            }

            // Pending assignments - simplified for now
            $stats['pending_assignments'] = 0;

            // Average grade/score
            if ($this->db->tableExists('submissions') && $gradeColumn) {
                $builder = $this->db->table('submissions');
                if ($submissionUserColumn) {
                    $result = $builder->selectAvg($gradeColumn)
                                    ->where($submissionUserColumn, $studentId)
                                    ->where("$gradeColumn IS NOT NULL")
                                    ->get()
                                    ->getRowArray();
                } else {
                    $result = $builder->selectAvg($gradeColumn)
                                    ->where("$gradeColumn IS NOT NULL")
                                    ->get()
                                    ->getRowArray();
                }
                $stats['average_grade'] = $result[$gradeColumn] ? round($result[$gradeColumn], 1) : 0;
            }
        } catch (\Exception $e) {
            // If there's any database error, return default stats
            log_message('error', 'Student stats error: ' . $e->getMessage());
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

            // Check if required columns exist
            $enrollmentFields = $this->db->getFieldNames('enrollments');

            // Check for user column (student_id or user_id)
            $userColumn = null;
            if (in_array('student_id', $enrollmentFields)) {
                $userColumn = 'student_id';
            } elseif (in_array('user_id', $enrollmentFields)) {
                $userColumn = 'user_id';
            }

            $hasCourseId = in_array('course_id', $enrollmentFields);

            if (!$userColumn || !$hasCourseId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Enrollment system not properly configured']);
            }

            // Check if already enrolled
            $builder = $this->db->table('enrollments');
            $existing = $builder->where($userColumn, $studentId)
                              ->where('course_id', $courseId)
                              ->get()
                              ->getRowArray();

            if ($existing) {
                return $this->response->setJSON(['success' => false, 'message' => 'Already enrolled in this course']);
            }

            // Enroll student
            $enrollmentData = [
                $userColumn => $studentId,
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

    /**
     * Student Materials Page
     */
    public function materials()
    {
        $redirect = $this->checkStudentAccess();
        if ($redirect) return $redirect;

        $student_id = $this->session->get('user_id');
        
        // Get materials for all enrolled courses
        $materials = $this->materialModel->getMaterialsForStudent($student_id);

        $data = [
            'title' => 'My Course Materials',
            'materials' => $materials,
            'user' => [
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('materials/student_materials', $data);
    }
}
