<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Announcement extends BaseController
{
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
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

        // Fetch all announcements from database
        $announcements = [];
        
        // Check if announcements table exists
        if ($this->db->tableExists('announcements')) {
            $query = $this->db->query("SELECT * FROM announcements ORDER BY date_posted DESC");
            $announcements = $query->getResultArray();
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
