<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSecurityColumnsToUsers extends Migration
{
    public function up()
    {
        // Add security-related columns to users table
        $fields = [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'blocked', 'suspended'],
                'default' => 'active',
                'after' => 'role'
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status'
            ],
            'last_login_ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'after' => 'last_login'
            ],
            'registration_ip' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'after' => 'last_login_ip'
            ],
            'email_verified' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'registration_ip'
            ],
            'email_verification_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email_verified'
            ],
            'password_reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email_verification_token'
            ],
            'password_reset_expires' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'password_reset_token'
            ]
        ];

        $this->forge->addColumn('users', $fields);
        
        // Add indexes for performance
        $this->forge->addKey('last_login');
        $this->forge->addKey('status');
        $this->forge->addKey('email_verified');
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('users', [
            'status',
            'last_login',
            'last_login_ip',
            'registration_ip',
            'email_verified',
            'email_verification_token',
            'password_reset_token',
            'password_reset_expires'
        ]);
    }
}
