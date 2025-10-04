<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminDashboard extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    /**
     * Check if user is logged in and has admin role
     */
    private function checkAdminAccess()
    {
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/login');
        }

        if ($this->session->get('role') !== 'admin') {
            $this->session->setFlashdata('error', 'Access denied. Admin privileges required.');
            return redirect()->to('/dashboard');
        }

        return null;
    }

    /**
     * Admin Dashboard
     */
    public function index()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        // Get statistics for admin dashboard
        $stats = $this->getAdminStats();

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'stats' => $stats
        ];

        return view('dashboards/admin', $data);
    }

    /**
     * User Management
     */
    public function users()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        // Get all users
        $builder = $this->db->table('users');
        $users = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'users' => $users
        ];

        return view('dashboards/admin_users', $data);
    }

    /**
     * System Settings
     */
    public function settings()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('dashboards/admin_settings', $data);
    }

    /**
     * Get admin statistics
     */
    private function getAdminStats()
    {
        $stats = [];

        // Total users
        $builder = $this->db->table('users');
        $stats['total_users'] = $builder->countAllResults();

        // Users by role
        $builder = $this->db->table('users');
        $stats['admin_count'] = $builder->where('role', 'admin')->countAllResults();

        $builder = $this->db->table('users');
        $stats['teacher_count'] = $builder->where('role', 'teacher')->countAllResults();

        $builder = $this->db->table('users');
        $stats['student_count'] = $builder->where('role', 'student')->countAllResults();

        // Total courses (if table exists)
        if ($this->db->tableExists('courses')) {
            $builder = $this->db->table('courses');
            $stats['total_courses'] = $builder->countAllResults();
        } else {
            $stats['total_courses'] = 0;
        }

        // Recent registrations (last 7 days)
        $builder = $this->db->table('users');
        $stats['recent_registrations'] = $builder->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))->countAllResults();

        // Today's registrations
        $builder = $this->db->table('users');
        $stats['today_registrations'] = $builder->where('DATE(created_at)', date('Y-m-d'))->countAllResults();

        // Get recent users (last 5)
        $builder = $this->db->table('users');
        $stats['recent_users'] = $builder->select('name, email, role, created_at')
                                        ->orderBy('created_at', 'DESC')
                                        ->limit(5)
                                        ->get()
                                        ->getResultArray();

        return $stats;
    }

    /**
     * Update user role (AJAX endpoint)
     */
    public function updateUserRole()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        if ($this->request->getMethod() === 'POST') {
            $userId = $this->request->getPost('user_id');
            $newRole = $this->request->getPost('role');

            // Validate role
            if (!in_array($newRole, ['admin', 'teacher', 'student'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid role']);
            }

            // Don't allow changing own role
            if ($userId == $this->session->get('user_id')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Cannot change your own role']);
            }

            $builder = $this->db->table('users');
            $result = $builder->where('id', $userId)->update(['role' => $newRole, 'updated_at' => date('Y-m-d H:i:s')]);

            if ($result) {
                return $this->response->setJSON(['success' => true, 'message' => 'User role updated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update user role']);
            }
        }

        return $this->response->setStatusCode(405);
    }
}
