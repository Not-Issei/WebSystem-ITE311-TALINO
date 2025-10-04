<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class StudentRegistration extends BaseController
{
    protected $db;
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->userModel = new \App\Models\UserModel();
    }

    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
                'department' => 'required',
                'year_level' => 'required|integer|in_list[1,2,3,4]',
                'phone' => 'permit_empty|min_length[10]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return view('auth/register_student', [
                    'validation' => $validation,
                    'old_input' => $this->request->getPost()
                ]);
            }

            // Generate student ID
            $studentId = 'STU' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Prepare user data
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'student',
                'student_id' => $studentId,
                'department' => $this->request->getPost('department'),
                'phone' => $this->request->getPost('phone') ?: null,
                'address' => null, // Optional field removed from form
                'year_level' => (int)$this->request->getPost('year_level'),
                'status' => 'pending', // New students need admin approval
                'registration_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            try {
                // Use database builder instead of UserModel to avoid potential issues
                $builder = $this->db->table('users');
                
                if ($builder->insert($userData)) {
                    $this->session->setFlashdata('success', 'Registration successful! Your account is pending admin approval. You will be notified once approved.');
                    return redirect()->to('/login');
                } else {
                    $this->session->setFlashdata('error', 'Failed to create student account. Please try again.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Student registration error: ' . $e->getMessage());
                $this->session->setFlashdata('error', 'Database error: ' . $e->getMessage());
            }
        }

        return view('auth/register_student');
    }
}
