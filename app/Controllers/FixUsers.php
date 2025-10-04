<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class FixUsers extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Delete existing users to start fresh
        $builder = $this->db->table('users');
        $builder->truncate();

        // Create all test users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@lms.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'John Teacher',
                'email' => 'teacher@lms.com',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role' => 'teacher',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Jane Student',
                'email' => 'student@lms.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $builder = $this->db->table('users');
        $result = $builder->insertBatch($users);

        $output = '<h2>🔧 User Account Fix Results</h2>';
        
        if ($result) {
            $output .= '<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;">';
            $output .= '<h3>✅ All accounts created successfully!</h3>';
            $output .= '</div>';

            // Verify each account
            foreach ($users as $userData) {
                $builder = $this->db->table('users');
                $user = $builder->where('email', $userData['email'])->get()->getRowArray();
                
                $password = '';
                switch ($userData['role']) {
                    case 'admin': $password = 'admin123'; break;
                    case 'teacher': $password = 'teacher123'; break;
                    case 'student': $password = 'student123'; break;
                }

                $output .= '<div style="border: 1px solid #ddd; padding: 10px; margin: 10px 0; border-radius: 5px;">';
                $output .= '<h4>' . $userData['role'] . ' Account</h4>';
                $output .= '<p><strong>Name:</strong> ' . $user['name'] . '</p>';
                $output .= '<p><strong>Email:</strong> ' . $user['email'] . '</p>';
                $output .= '<p><strong>Password:</strong> ' . $password . '</p>';
                
                if (password_verify($password, $user['password'])) {
                    $output .= '<p style="color: green;"><strong>✅ Password verification: SUCCESS</strong></p>';
                } else {
                    $output .= '<p style="color: red;"><strong>❌ Password verification: FAILED</strong></p>';
                }
                $output .= '</div>';
            }

            $output .= '<div style="background: #cce5ff; padding: 15px; border-radius: 5px; margin: 20px 0;">';
            $output .= '<h3>🎯 Ready to Test!</h3>';
            $output .= '<p><a href="' . base_url('/login') . '" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Login Page</a></p>';
            $output .= '</div>';

        } else {
            $output .= '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;">';
            $output .= '<h3>❌ Failed to create accounts</h3>';
            $output .= '</div>';
        }

        // Show all users in database
        $builder = $this->db->table('users');
        $allUsers = $builder->get()->getResultArray();
        
        $output .= '<hr><h3>📋 All Users in Database:</h3>';
        $output .= '<table style="width: 100%; border-collapse: collapse; margin: 10px 0;">';
        $output .= '<tr style="background: #f8f9fa;"><th style="border: 1px solid #ddd; padding: 8px;">ID</th><th style="border: 1px solid #ddd; padding: 8px;">Name</th><th style="border: 1px solid #ddd; padding: 8px;">Email</th><th style="border: 1px solid #ddd; padding: 8px;">Role</th></tr>';
        
        foreach ($allUsers as $user) {
            $output .= '<tr>';
            $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $user['id'] . '</td>';
            $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $user['name'] . '</td>';
            $output .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $user['email'] . '</td>';
            $output .= '<td style="border: 1px solid #ddd; padding: 8px;"><span style="background: ' . 
                      ($user['role'] == 'admin' ? '#dc3545' : ($user['role'] == 'teacher' ? '#28a745' : '#007bff')) . 
                      '; color: white; padding: 2px 8px; border-radius: 3px;">' . ucfirst($user['role']) . '</span></td>';
            $output .= '</tr>';
        }
        $output .= '</table>';

        return $output;
    }
}
