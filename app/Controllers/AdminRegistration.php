<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminRegistration extends BaseController
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

    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            // Validation rules for admin registration
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
                'admin_code' => 'required',
                'department' => 'required|min_length[2]|max_length[100]',
                'phone' => 'required|min_length[10]|max_length[15]',
                'address' => 'required|min_length[10]'
            ];

            if (!$this->validate($rules)) {
                return view('auth/register_admin', [
                    'validation' => $this->validator,
                    'old_input' => $this->request->getPost()
                ]);
            }

            // Verify admin code
            $adminCode = $this->request->getPost('admin_code');
            if ($adminCode !== 'ADMIN123') {
                $this->session->setFlashdata('error', 'Invalid admin registration code.');
                return view('auth/register_admin', [
                    'old_input' => $this->request->getPost()
                ]);
            }

            // Generate employee ID
            $employeeId = 'EMP' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Prepare user data
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'admin',
                'employee_id' => $employeeId,
                'department' => $this->request->getPost('department'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'status' => 'active',
                'registration_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            try {
                // Insert user
                if ($this->userModel->insert($userData)) {
                    $this->session->setFlashdata('success', 'Admin account created successfully! You can now login.');
                    return redirect()->to('/login');
                } else {
                    $this->session->setFlashdata('error', 'Failed to create admin account. Please try again.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Admin registration error: ' . $e->getMessage());
                $this->session->setFlashdata('error', 'An error occurred during registration. Please try again.');
            }
        }

        return view('auth/register_admin');
    }
}
