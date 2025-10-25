<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'course_code' => 'ITE311',
                'course_name' => 'Web Systems and Technologies',
                'description' => 'This course covers modern web development technologies including HTML5, CSS3, JavaScript, PHP, and database integration.',
                'credits' => 3,
                'teacher_id' => 1, // Assuming teacher with ID 1 exists
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'course_code' => 'ITE312',
                'course_name' => 'Database Systems',
                'description' => 'Introduction to database design, SQL, normalization, and database management systems.',
                'credits' => 3,
                'teacher_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'course_code' => 'ITE313',
                'course_name' => 'Systems Analysis and Design',
                'description' => 'Methodologies for analyzing, designing, and implementing information systems.',
                'credits' => 3,
                'teacher_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'course_code' => 'ITE314',
                'course_name' => 'Network Administration',
                'description' => 'Network configuration, security, and administration principles.',
                'credits' => 3,
                'teacher_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert courses
        $this->db->table('courses')->insertBatch($data);
    }
}
