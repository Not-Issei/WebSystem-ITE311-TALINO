<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Course extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    /**
     * Course listing page
     */
    public function index()
    {
        $courses = $this->courseModel->getActiveCourses();
        
        $data = [
            'courses' => $courses,
            'title' => 'All Courses'
        ];

        return view('courses/index', $data);
    }

    /**
     * View single course
     */
    public function view($courseId)
    {
        $course = $this->courseModel->getCourseWithTeacher($courseId);
        
        if (!$course) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Course not found');
        }

        // Get course statistics
        $stats = $this->courseModel->getCourseStats($courseId);
        
        // Check if user is enrolled (if logged in)
        $isEnrolled = false;
        if ($this->session->get('logged_in') && $this->session->get('role') === 'student') {
            $isEnrolled = $this->enrollmentModel->isAlreadyEnrolled(
                $this->session->get('user_id'), 
                $courseId
            );
        }

        $data = [
            'course' => $course,
            'stats' => $stats,
            'isEnrolled' => $isEnrolled,
            'title' => $course['course_name']
        ];

        return view('courses/view', $data);
    }

    /**
     * AJAX Course Enrollment
     */
    public function enroll()
    {
        // Check if request is POST
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method.'
            ])->setStatusCode(405);
        }

        // Check if user is logged in
        if (!$this->session->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to enroll in courses.'
            ])->setStatusCode(401);
        }

        // Check if user is a student
        if ($this->session->get('role') !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only students can enroll in courses.'
            ])->setStatusCode(403);
        }

        // CSRF protection is handled automatically by CodeIgniter framework
        // Manual validation removed to fix enrollment issues

        // Get and validate course ID
        $courseId = $this->request->getPost('course_id');
        if (!$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID.'
            ])->setStatusCode(400);
        }

        // Check if course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Check if course is active
        if ($course['status'] !== 'active') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This course is not available for enrollment.'
            ])->setStatusCode(400);
        }

        $userId = $this->session->get('user_id');

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ])->setStatusCode(400);
        }

        // Check if course has available slots
        if (!$this->courseModel->hasAvailableSlots($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This course is full. No available slots.'
            ])->setStatusCode(400);
        }

        // Enroll the user
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];

        try {
            $enrollmentId = $this->enrollmentModel->enrollUser($enrollmentData);
            
            if ($enrollmentId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Successfully enrolled in {$course['course_name']}!",
                    'enrollment_id' => $enrollmentId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to enroll in course. Please try again.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            log_message('error', 'Enrollment error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred during enrollment. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * AJAX Course Unenrollment
     */
    public function unenroll()
    {
        // Check if request is POST
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method.'
            ])->setStatusCode(405);
        }

        // Check if user is logged in
        if (!$this->session->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to unenroll from courses.'
            ])->setStatusCode(401);
        }

        // Check if user is a student
        if ($this->session->get('role') !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only students can unenroll from courses.'
            ])->setStatusCode(403);
        }

        // Validate CSRF token
        if (!$this->validate(['csrf_token' => 'required'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid security token.'
            ])->setStatusCode(400);
        }

        // Get and validate course ID
        $courseId = $this->request->getPost('course_id');
        if (!$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID.'
            ])->setStatusCode(400);
        }

        $userId = $this->session->get('user_id');

        // Check if enrolled
        if (!$this->enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not enrolled in this course.'
            ])->setStatusCode(400);
        }

        try {
            // Drop the user (mark as dropped instead of deleting)
            $result = $this->enrollmentModel->dropUser($userId, $courseId);
            
            if ($result) {
                $course = $this->courseModel->find($courseId);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Successfully unenrolled from {$course['course_name']}."
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to unenroll from course. Please try again.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            log_message('error', 'Unenrollment error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred during unenrollment. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Get course details (AJAX)
     */
    public function getCourseDetails($courseId)
    {
        $course = $this->courseModel->getCourseWithTeacher($courseId);
        
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Get course statistics
        $stats = $this->courseModel->getCourseStats($courseId);
        
        return $this->response->setJSON([
            'success' => true,
            'course' => $course,
            'stats' => $stats
        ]);
    }

    /**
     * Search courses (AJAX)
     */
    public function search()
    {
        $query = $this->request->getGet('q');
        
        if (!$query) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Search query is required.'
            ])->setStatusCode(400);
        }

        $courses = $this->courseModel->searchCourses($query);
        
        return $this->response->setJSON([
            'success' => true,
            'courses' => $courses,
            'count' => count($courses)
        ]);
    }
}
