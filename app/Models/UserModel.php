<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
        'student_id',
        'department',
        'phone',
        'address',
        'specialization',
        'year_level',
        'status',
        'last_login',
        'last_login_ip',
        'registration_ip',
        'email_verified',
        'email_verification_token',
        'password_reset_token',
        'password_reset_expires'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[admin,teacher,student]'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email address is already registered.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Verify user password for login
     */
    public function verifyPassword($email, $password)
    {
        $user = $this->where('email', $email)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $this->update($user['id'], [
                'last_login' => date('Y-m-d H:i:s'),
                'last_login_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
            ]);
            
            return $user;
        }
        
        return false;
    }

    /**
     * Get user statistics for admin dashboard
     */
    public function getUserStats()
    {
        $stats = [];

        // Total users
        $stats['total_users'] = $this->countAllResults();

        // Users by role
        $stats['admin_count'] = $this->where('role', 'admin')->countAllResults();
        $stats['teacher_count'] = $this->where('role', 'teacher')->countAllResults();
        $stats['student_count'] = $this->where('role', 'student')->countAllResults();

        // Recent registrations (last 7 days)
        $stats['recent_registrations'] = $this->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))->countAllResults();

        // Today's registrations
        $stats['today_registrations'] = $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults();

        // Active users (logged in within last 30 days)
        $stats['active_users'] = $this->where('last_login >=', date('Y-m-d H:i:s', strtotime('-30 days')))->countAllResults();

        return $stats;
    }

    /**
     * Get recent users
     */
    public function getRecentUsers($limit = 10)
    {
        return $this->select('id, name, email, role, created_at')
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)
                   ->where('status', 'active')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Search users
     */
    public function searchUsers($query)
    {
        return $this->like('name', $query)
                   ->orLike('email', $query)
                   ->orLike('employee_id', $query)
                   ->orLike('student_id', $query)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Generate unique employee ID
     */
    public function generateEmployeeId($role = 'EMP')
    {
        do {
            $id = strtoupper($role) . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $existing = $this->where('employee_id', $id)->first();
        } while ($existing);

        return $id;
    }

    /**
     * Generate unique student ID
     */
    public function generateStudentId()
    {
        do {
            $id = 'STU' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $existing = $this->where('student_id', $id)->first();
        } while ($existing);

        return $id;
    }

    /**
     * Update user status
     */
    public function updateUserStatus($userId, $status)
    {
        return $this->update($userId, ['status' => $status]);
    }

    /**
     * Get user profile with additional info
     */
    public function getUserProfile($userId)
    {
        $user = $this->find($userId);
        
        if ($user) {
            // Remove password from profile
            unset($user['password']);
            
            // Add additional stats based on role
            if ($user['role'] === 'student') {
                // Add enrollment stats if enrollments table exists
                $db = \Config\Database::connect();
                if ($db->tableExists('enrollments')) {
                    $user['enrollment_count'] = $db->table('enrollments')
                                                   ->where('user_id', $userId)
                                                   ->where('status', 'active')
                                                   ->countAllResults();
                }
            } elseif ($user['role'] === 'teacher') {
                // Add course stats if courses table exists
                $db = \Config\Database::connect();
                if ($db->tableExists('courses')) {
                    $user['course_count'] = $db->table('courses')
                                              ->where('teacher_id', $userId)
                                              ->where('status', 'active')
                                              ->countAllResults();
                }
            }
        }
        
        return $user;
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null)
    {
        $builder = $this->where('email', $email);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->first() !== null;
    }

    /**
     * Get users with pagination
     */
    public function getUsersPaginated($perPage = 10, $role = null)
    {
        $builder = $this->select('id, name, email, role, employee_id, student_id, department, status, created_at');
        
        if ($role) {
            $builder->where('role', $role);
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->paginate($perPage);
    }
}
