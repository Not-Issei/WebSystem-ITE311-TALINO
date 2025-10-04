<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'Admin User',
                'email'      => 'admin@lms.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'       => 'John Teacher',
                'email'      => 'teacher@lms.com',
                'password'   => password_hash('teacher123', PASSWORD_DEFAULT),
                'role'       => 'teacher',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'       => 'Jane Student',
                'email'      => 'student@lms.com',
                'password'   => password_hash('student123', PASSWORD_DEFAULT),
                'role'       => 'student',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'       => 'Alice Johnson',
                'email'      => 'alice@example.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'student',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'name'       => 'Bob Wilson',
                'email'      => 'bob@example.com',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'teacher',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
