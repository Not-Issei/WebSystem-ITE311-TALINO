<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminDashboard extends BaseController
{
    protected $db;
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
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

        try {
            // Get statistics for admin dashboard
            $stats = $this->getAdminStats();

            // Get students and teachers for display
            $builder = $this->db->table('users');
            $students = $builder->where('role', 'student')
                               ->where('status', 'active')
                               ->orderBy('created_at', 'DESC')
                               ->limit(10)
                               ->get()
                               ->getResultArray();

            // Reset builder for teachers query
            $builder = $this->db->table('users');
            $teachers = $builder->where('role', 'teacher')
                               ->where('status', 'active')
                               ->orderBy('created_at', 'DESC')
                               ->limit(10)
                               ->get()
                               ->getResultArray();

            // Reset builder for pending count
            $builder = $this->db->table('users');
            $pendingCount = $builder->where('status', 'pending')->countAllResults();

            $data = [
                'user' => [
                    'id' => $this->session->get('user_id'),
                    'name' => $this->session->get('name'),
                    'email' => $this->session->get('email'),
                    'role' => $this->session->get('role')
                ],
                'stats' => $stats,
                'students' => $students ?? [],
                'teachers' => $teachers ?? [],
                'pending_count' => $pendingCount ?? 0
            ];

            return view('admin_dashboard', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Admin Dashboard Error: ' . $e->getMessage());
            
            // Return basic dashboard with empty data
            $data = [
                'user' => [
                    'id' => $this->session->get('user_id'),
                    'name' => $this->session->get('name'),
                    'email' => $this->session->get('email'),
                    'role' => $this->session->get('role')
                ],
                'stats' => [
                    'total_users' => 0,
                    'admin_count' => 0,
                    'teacher_count' => 0,
                    'student_count' => 0
                ],
                'students' => [],
                'teachers' => [],
                'pending_count' => 0,
                'error' => 'Error loading dashboard data: ' . $e->getMessage()
            ];

            return view('admin_dashboard', $data);
        }
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
        // Use UserModel for better statistics
        $stats = $this->userModel->getUserStats();
        
        // Add recent users
        $stats['recent_users'] = $this->userModel->getRecentUsers(5);

        // Pending users count
        $stats['pending_users'] = $this->userModel->where('status', 'pending')->countAllResults();

        // Total courses (if table exists)
        if ($this->db->tableExists('courses')) {
            $builder = $this->db->table('courses');
            $stats['total_courses'] = $builder->countAllResults();
        } else {
            $stats['total_courses'] = 0;
        }

        return $stats;
    }

    /**
     * Get pending user approvals
     */
    public function pendingApprovals()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        // Get pending users
        $pendingUsers = $this->userModel->where('status', 'pending')
                                       ->orderBy('created_at', 'DESC')
                                       ->findAll();

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'pending_users' => $pendingUsers,
            'stats' => $this->getAdminStats()
        ];

        return view('dashboards/admin_approvals', $data);
    }

    /**
     * Approve user account
     */
    public function approveUser()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $userId = $this->request->getPost('user_id');
        
        if ($this->userModel->update($userId, ['status' => 'active'])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User approved successfully!'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to approve user.'
        ]);
    }

    /**
     * Reject user account
     */
    public function rejectUser()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $userId = $this->request->getPost('user_id');
        
        if ($this->userModel->delete($userId)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User registration rejected and removed.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to reject user.'
        ]);
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
