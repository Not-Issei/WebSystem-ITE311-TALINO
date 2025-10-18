<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
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
     * Display registration form and handle registration
     */
    public function register()
    {
        // If user is already logged in, redirect to role-specific dashboard
        if ($this->session->get('user_id')) {
            $role = $this->session->get('role');
            switch ($role) {
                case 'admin':
                    return redirect()->to('/admin/dashboard');
                case 'teacher':
                    return redirect()->to('/teacher/dashboard');
                case 'student':
                    return redirect()->to('/announcements');
                default:
                    return redirect()->to('/dashboard');
            }
        }

        if ($this->request->getMethod() === 'POST') {
            // Set validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => [
                    'label' => 'Name',
                    'rules' => 'required|min_length[2]|max_length[100]|alpha_space',
                    'errors' => [
                        'required' => 'Name is required.',
                        'min_length' => 'Name must be at least 2 characters long.',
                        'max_length' => 'Name cannot exceed 100 characters.',
                        'alpha_space' => 'Name can only contain letters and spaces.'
                    ]
                ],
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required.',
                        'valid_email' => 'Please enter a valid email address.',
                        'is_unique' => 'This email is already registered.'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password is required.',
                        'min_length' => 'Password must be at least 6 characters long.'
                    ]
                ],
                'password_confirm' => [
                    'label' => 'Confirm Password',
                    'rules' => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Please confirm your password.',
                        'matches' => 'Password confirmation does not match.'
                    ]
                ]
            ]);

            if ($validation->withRequest($this->request)->run()) {
                // Hash the password
                $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

                // Sanitize and prepare user data
                $userData = [
                    'name' => $this->sanitizeInput($this->request->getPost('name')),
                    'email' => filter_var($this->request->getPost('email'), FILTER_SANITIZE_EMAIL),
                    'password' => $hashedPassword,
                    'role' => 'student', // Default role
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Insert user into database
                $builder = $this->db->table('users');
                if ($builder->insert($userData)) {
                    $this->session->setFlashdata('success', 'Registration successful! Please login with your credentials.');
                    return redirect()->to('/login');
                } else {
                    $this->session->setFlashdata('error', 'Registration failed. Please try again.');
                }
            } else {
                $this->session->setFlashdata('validation', $validation);
            }
        }

        return view('auth/register');
    }

    /**
     * Display login form and handle login
     */
    public function login()
    {
        // If user is already logged in, redirect to role-specific dashboard
        if ($this->session->get('user_id')) {
            $role = $this->session->get('role');
            switch ($role) {
                case 'admin':
                    return redirect()->to('/admin/dashboard');
                case 'teacher':
                    return redirect()->to('/teacher/dashboard');
                case 'student':
                    return redirect()->to('/announcements');
                default:
                    return redirect()->to('/dashboard');
            }
        }

        if ($this->request->getMethod() === 'POST') {
            // Set validation rules
            $validation = \Config\Services::validation();
            $validation->setRules([
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email is required.',
                        'valid_email' => 'Please enter a valid email address.'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required.'
                    ]
                ]
            ]);

            if ($validation->withRequest($this->request)->run()) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                // Verify user credentials using UserModel
                $user = $this->userModel->verifyPassword($email, $password);

                if ($user) {
                    // Check user status
                    if ($user['status'] === 'pending') {
                        $this->session->setFlashdata('error', 'Your account is pending admin approval. Please wait for approval before logging in.');
                        return view('auth/login');
                    } elseif ($user['status'] === 'suspended') {
                        $this->session->setFlashdata('error', 'Your account has been suspended. Please contact the administrator.');
                        return view('auth/login');
                    } elseif ($user['status'] !== 'active') {
                        $this->session->setFlashdata('error', 'Your account is not active. Please contact the administrator.');
                        return view('auth/login');
                    }
                    
                    // Regenerate session ID for security
                    $this->session->regenerate();
                    
                    // Create session data
                    $sessionData = [
                        'user_id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'employee_id' => $user['employee_id'] ?? null,
                        'student_id' => $user['student_id'] ?? null,
                        'logged_in' => true,
                        'login_time' => time()
                    ];

                    $this->session->set($sessionData);
                    $this->session->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');
                    
                    // Redirect based on user role
                    switch ($user['role']) {
                        case 'admin':
                            return redirect()->to('/admin/dashboard');
                        case 'teacher':
                            return redirect()->to('/teacher/dashboard');
                        case 'student':
                            return redirect()->to('/announcements');
                        default:
                            return redirect()->to('/dashboard');
                    }
                } else {
                    $this->session->setFlashdata('error', 'Invalid email or password.');
                }
            } else {
                $this->session->setFlashdata('validation', $validation);
            }
        }

        return view('auth/login');
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        $this->session->destroy();
        $this->session->setFlashdata('success', 'You have been logged out successfully.');
        return redirect()->to('/login');
    }

    /**
     * Protected dashboard page
     */
    public function dashboard()
    {
        // Check if user is logged in
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/login');
        }

        $data = [
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('auth/dashboard', $data);
    }

    /**
     * Sanitize input to remove special characters and potential security threats
     */
    private function sanitizeInput($input)
    {
        // Remove HTML tags and special characters
        $input = strip_tags($input);
        
        // Remove or replace special characters, keeping only alphanumeric, spaces, and basic punctuation
        $input = preg_replace('/[^a-zA-Z0-9\s\.\-_]/', '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        // Convert to proper case for names
        $input = ucwords(strtolower($input));
        
        return $input;
    }
}
