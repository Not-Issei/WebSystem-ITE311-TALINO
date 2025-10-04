<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TestController extends BaseController
{
    public function student()
    {
        echo "<h2>🔍 Student Dashboard Test</h2>";
        
        // Check if session is working
        $session = \Config\Services::session();
        echo "<h3>Session Data:</h3>";
        echo "<pre>";
        var_dump($session->get());
        echo "</pre>";
        
        // Check if user is logged in
        if (!$session->get('logged_in')) {
            echo "<p style='color: red;'>❌ User is NOT logged in</p>";
            echo "<p><a href='" . base_url('/login') . "'>Go to Login Page</a></p>";
        } else {
            echo "<p style='color: green;'>✅ User is logged in</p>";
            echo "<p><strong>User Role:</strong> " . $session->get('role') . "</p>";
            
            if ($session->get('role') !== 'student') {
                echo "<p style='color: orange;'>⚠️ User is not a student (Role: " . $session->get('role') . ")</p>";
            } else {
                echo "<p style='color: green;'>✅ User has student role</p>";
                
                // Try to load the student dashboard
                try {
                    $studentController = new \App\Controllers\StudentDashboard();
                    echo "<p style='color: green;'>✅ StudentDashboard controller loaded successfully</p>";
                    
                    // Check if view file exists
                    if (file_exists(APPPATH . 'Views/dashboards/student.php')) {
                        echo "<p style='color: green;'>✅ Student view file exists</p>";
                    } else {
                        echo "<p style='color: red;'>❌ Student view file missing</p>";
                    }
                    
                } catch (Exception $e) {
                    echo "<p style='color: red;'>❌ Error loading StudentDashboard: " . $e->getMessage() . "</p>";
                }
            }
        }
        
        echo "<hr>";
        echo "<h3>Quick Actions:</h3>";
        echo "<p><a href='" . base_url('/fix-users') . "'>Fix User Accounts</a></p>";
        echo "<p><a href='" . base_url('/login') . "'>Login Page</a></p>";
        echo "<p><a href='" . base_url('/logout') . "'>Logout</a></p>";
    }
}
