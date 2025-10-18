<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Teacher extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    /**
     * Check if user is logged in and has teacher role
     */
    private function checkTeacherAccess()
    {
        if (!$this->session->get('logged_in')) {
            $this->session->setFlashdata('error', 'Please login to access the teacher dashboard.');
            return redirect()->to('/login');
        }

        if ($this->session->get('role') !== 'teacher') {
            $this->session->setFlashdata('error', 'Access denied. Teacher privileges required.');
            return redirect()->to('/dashboard');
        }

        return null;
    }

    /**
     * Teacher Dashboard
     */
    public function dashboard()
    {
        $redirect = $this->checkTeacherAccess();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Teacher Dashboard - ITE311 TALINO',
            'user_name' => $this->session->get('name'),
            'user_role' => $this->session->get('role'),
            'user_email' => $this->session->get('email')
        ];

        return view('teacher_dashboard', $data);
    }
}
