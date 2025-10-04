<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'course_code',
        'course_name', 
        'description',
        'teacher_id',
        'credits',
        'max_students',
        'semester',
        'academic_year',
        'status',
        'start_date',
        'end_date'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all active courses
     */
    public function getActiveCourses()
    {
        return $this->where('status', 'active')
                    ->orderBy('course_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get courses available for a specific user (not enrolled)
     */
    public function getAvailableCoursesForUser($userId)
    {
        try {
            $builder = $this->db->table('courses c');
            
            // Check if enrollments table exists
            if (!$this->db->tableExists('enrollments')) {
                // If no enrollments table, return all active courses
                return $builder->select('c.*, u.name as teacher_name')
                              ->join('users u', 'u.id = c.teacher_id', 'left')
                              ->where('c.status', 'active')
                              ->orderBy('c.course_name', 'ASC')
                              ->get()
                              ->getResultArray();
            }
            
            // Get courses where user is not enrolled
            $courses = $builder->select('c.*, u.name as teacher_name')
                              ->join('users u', 'u.id = c.teacher_id', 'left')
                              ->where('c.status', 'active')
                              ->where("c.id NOT IN (
                                  SELECT COALESCE(course_id, 0) FROM enrollments 
                                  WHERE user_id = {$userId}
                              )")
                              ->orderBy('c.course_name', 'ASC')
                              ->get()
                              ->getResultArray();

            return $courses;
        } catch (\Exception $e) {
            log_message('error', 'CourseModel getAvailableCoursesForUser error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get course with teacher information
     */
    public function getCourseWithTeacher($courseId)
    {
        $builder = $this->db->table('courses c');
        
        return $builder->select('c.*, u.name as teacher_name, u.email as teacher_email')
                      ->join('users u', 'u.id = c.teacher_id', 'left')
                      ->where('c.id', $courseId)
                      ->get()
                      ->getRowArray();
    }

    /**
     * Check if course has available slots
     */
    public function hasAvailableSlots($courseId)
    {
        // Get course max students
        $course = $this->find($courseId);
        if (!$course) {
            return false;
        }

        // Count current enrollments
        $builder = $this->db->table('enrollments');
        $currentEnrollments = $builder->where('course_id', $courseId)
                                    ->where('status', 'active')
                                    ->countAllResults();

        return $currentEnrollments < $course['max_students'];
    }

    /**
     * Get courses by teacher
     */
    public function getCoursesByTeacher($teacherId)
    {
        return $this->where('teacher_id', $teacherId)
                    ->where('status', 'active')
                    ->orderBy('course_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get course statistics
     */
    public function getCourseStats($courseId)
    {
        $stats = [];
        
        // Total enrollments
        $builder = $this->db->table('enrollments');
        $stats['total_enrollments'] = $builder->where('course_id', $courseId)->countAllResults();
        
        // Active enrollments
        $stats['active_enrollments'] = $builder->where('course_id', $courseId)
                                             ->where('status', 'active')
                                             ->countAllResults();
        
        // Completed enrollments
        $stats['completed_enrollments'] = $builder->where('course_id', $courseId)
                                                ->where('status', 'completed')
                                                ->countAllResults();
        
        // Dropped enrollments
        $stats['dropped_enrollments'] = $builder->where('course_id', $courseId)
                                              ->where('status', 'dropped')
                                              ->countAllResults();

        return $stats;
    }

    /**
     * Search courses
     */
    public function searchCourses($query)
    {
        return $this->like('course_name', $query)
                    ->orLike('course_code', $query)
                    ->orLike('description', $query)
                    ->where('status', 'active')
                    ->orderBy('course_name', 'ASC')
                    ->findAll();
    }
}
