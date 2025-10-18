<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the New Academic Year 2025-2026',
                'content' => 'We are excited to welcome all students to the new academic year! This semester brings new opportunities, challenges, and exciting learning experiences. Please make sure to check your course schedules and attend the orientation sessions scheduled for next week. We wish everyone a successful and productive academic year ahead.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'title' => 'Important: Midterm Examination Schedule Released',
                'content' => 'The midterm examination schedule has been officially released and is now available on the student portal. All students are required to review their examination dates and times carefully. Please note that make-up examinations will only be allowed for students with valid medical certificates or emergency situations. Contact the registrar\'s office for any scheduling conflicts.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'title' => 'Library Hours Extended During Finals Week',
                'content' => 'To support students during the upcoming finals week, the university library will extend its operating hours. The library will be open from 7:00 AM to 11:00 PM, Monday through Sunday. Additional study spaces and computer workstations will be available. Please bring your student ID for access after regular hours.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
            ],
            [
                'title' => 'Student Portal Maintenance Notice',
                'content' => 'The student portal will undergo scheduled maintenance this weekend (Saturday, 2:00 AM - 6:00 AM). During this time, access to grades, course materials, and other portal services may be temporarily unavailable. We apologize for any inconvenience and appreciate your patience as we work to improve our systems.',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert sample announcements
        $this->db->table('announcements')->insertBatch($data);
        
        echo "AnnouncementSeeder: " . count($data) . " sample announcements inserted successfully.\n";
    }
}
