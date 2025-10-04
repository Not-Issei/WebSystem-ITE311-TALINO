<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrollment_date',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Enroll a user in a course
     */
    public function enrollUser($data)
    {
        // Check if already enrolled
        if ($this->isAlreadyEnrolled($data['user_id'], $data['course_id'])) {
            return false;
        }

        // Set enrollment date if not provided
        if (!isset($data['enrollment_date'])) {
            $data['enrollment_date'] = date('Y-m-d H:i:s');
        }

        // Set default status
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        return $this->insert($data);
    }

    /**
     * Check if user is already enrolled in course
     */
    public function isAlreadyEnrolled($userId, $courseId)
    {
        $enrollment = $this->where('user_id', $userId)
                          ->where('course_id', $courseId)
                          ->where('status', 'active')
                          ->first();

        return $enrollment !== null;
    }

    /**
     * Get user's enrolled courses with course details
     */
    public function getUserEnrollments($userId)
    {
        try {
            // Check if required tables exist
            if (!$this->db->tableExists('courses')) {
                return [];
            }

            $builder = $this->db->table('enrollments e');
            
            return $builder->select('e.*, c.course_code, c.course_name, c.description, c.credits, u.name as teacher_name')
                          ->join('courses c', 'c.id = e.course_id')
                          ->join('users u', 'u.id = c.teacher_id', 'left')
                          ->where('e.user_id', $userId)
                          ->where('e.status', 'active')
                          ->orderBy('e.enrollment_date', 'DESC')
                          ->get()
                          ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'EnrollmentModel getUserEnrollments error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get enrollment statistics for a user
     */
    public function getUserEnrollmentStats($userId)
    {
        $stats = [];

        // Total enrolled courses
        $stats['total_enrolled'] = $this->where('user_id', $userId)
                                       ->where('status', 'active')
                                       ->countAllResults();

        // Completed courses
        $stats['completed_courses'] = $this->where('user_id', $userId)
                                          ->where('status', 'completed')
                                          ->countAllResults();

        // Dropped courses
        $stats['dropped_courses'] = $this->where('user_id', $userId)
                                        ->where('status', 'dropped')
                                        ->countAllResults();

        // Recent enrollments (last 30 days)
        $stats['recent_enrollments'] = $this->where('user_id', $userId)
                                           ->where('enrollment_date >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                                           ->countAllResults();

        return $stats;
    }

    /**
     * Unenroll user from course
     */
    public function unenrollUser($userId, $courseId)
    {
        return $this->where('user_id', $userId)
                   ->where('course_id', $courseId)
                   ->delete();
    }

    /**
     * Drop user from course (mark as dropped instead of deleting)
     */
    public function dropUser($userId, $courseId)
    {
        return $this->where('user_id', $userId)
                   ->where('course_id', $courseId)
                   ->set(['status' => 'dropped', 'updated_at' => date('Y-m-d H:i:s')])
                   ->update();
    }

    /**
     * Complete user's course
     */
    public function completeUserCourse($userId, $courseId)
    {
        return $this->where('user_id', $userId)
                   ->where('course_id', $courseId)
                   ->set(['status' => 'completed', 'updated_at' => date('Y-m-d H:i:s')])
                   ->update();
    }

    /**
     * Get course enrollment statistics
     */
    public function getCourseEnrollmentStats($courseId)
    {
        $stats = [];

        // Total enrollments
        $stats['total'] = $this->where('course_id', $courseId)->countAllResults();

        // Active enrollments
        $stats['active'] = $this->where('course_id', $courseId)
                               ->where('status', 'active')
                               ->countAllResults();

        // Completed enrollments
        $stats['completed'] = $this->where('course_id', $courseId)
                                  ->where('status', 'completed')
                                  ->countAllResults();

        // Dropped enrollments
        $stats['dropped'] = $this->where('course_id', $courseId)
                                ->where('status', 'dropped')
                                ->countAllResults();

        return $stats;
    }

    /**
     * Get enrolled students for a course
     */
    public function getCourseStudents($courseId)
    {
        $builder = $this->db->table('enrollments e');
        
        return $builder->select('e.*, u.name, u.email, u.student_id')
                      ->join('users u', 'u.id = e.user_id')
                      ->where('e.course_id', $courseId)
                      ->where('e.status', 'active')
                      ->orderBy('u.name', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get recent enrollments
     */
    public function getRecentEnrollments($limit = 10)
    {
        $builder = $this->db->table('enrollments e');
        
        return $builder->select('e.*, u.name as student_name, c.course_name, c.course_code')
                      ->join('users u', 'u.id = e.user_id')
                      ->join('courses c', 'c.id = e.course_id')
                      ->orderBy('e.enrollment_date', 'DESC')
                      ->limit($limit)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get enrollment by user and course
     */
    public function getEnrollment($userId, $courseId)
    {
        return $this->where('user_id', $userId)
                   ->where('course_id', $courseId)
                   ->first();
    }
}
