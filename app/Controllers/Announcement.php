<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;
use CodeIgniter\HTTP\ResponseInterface;

class Announcement extends BaseController
{
    protected $db;
    protected $session;
    protected $announcementModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->announcementModel = new AnnouncementModel();
    }

    /**
     * Check if user is logged in
     */
    private function checkAccess()
    {
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Please login to access announcements.');
            return redirect()->to('/login');
        }

        return null;
    }

    /**
     * Display all announcements
     */
    public function index()
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        // Fetch all announcements using AnnouncementModel, ordered by created_at DESC (newest first)
        $announcements = [];
        
        try {
            // Check if announcements table exists
            if ($this->db->tableExists('announcements')) {
                $announcements = $this->announcementModel->getAllAnnouncements();
            }
        } catch (\Exception $e) {
            // Handle any database errors gracefully
            log_message('error', 'Error fetching announcements: ' . $e->getMessage());
            $announcements = [];
        }

        $data = [
            'title' => 'Announcements - ITE311 TALINO',
            'announcements' => $announcements,
            'user_role' => $this->session->get('role'),
            'user_name' => $this->session->get('name')
        ];

        return view('announcements', $data);
    }
}
