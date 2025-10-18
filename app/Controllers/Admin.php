<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    /**
     * Check if user is logged in and has admin role
     */
    private function checkAdminAccess()
    {
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Please login to access the admin dashboard.');
            return redirect()->to('/login');
        }

        if ($this->session->get('role') !== 'admin') {
            $this->session->setFlashdata('error', 'Access denied. Admin privileges required.');
            return redirect()->to('/dashboard');
        }

        return null;
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Admin Dashboard - ITE311 TALINO',
            'user_name' => $this->session->get('name'),
            'user_role' => $this->session->get('role'),
            'user_email' => $this->session->get('email')
        ];

        return view('admin_dashboard', $data);
    }
}
