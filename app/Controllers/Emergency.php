<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Emergency extends BaseController
{
    public function logout()
    {
        // Force logout by destroying session
        $session = \Config\Services::session();
        $session->destroy();
        
        // Clear all session data
        session_destroy();
        
        echo "<h2>🚪 Emergency Logout Complete</h2>";
        echo "<p style='color: green;'>✅ Session destroyed successfully</p>";
        echo "<p style='color: green;'>✅ All user data cleared</p>";
        echo "<hr>";
        echo "<h3>What to do next:</h3>";
        echo "<p>1. <a href='" . base_url('/fix-users') . "'>Fix User Accounts</a> - Create fresh accounts</p>";
        echo "<p>2. <a href='" . base_url('/login') . "'>Login Again</a> - Try logging in with fresh accounts</p>";
        echo "<p>3. <a href='" . base_url('/') . "'>Go Home</a> - Return to homepage</p>";
        
        echo "<hr>";
        echo "<h3>Test Accounts:</h3>";
        echo "<ul>";
        echo "<li><strong>Admin:</strong> admin@lms.com / admin123</li>";
        echo "<li><strong>Teacher:</strong> teacher@lms.com / teacher123</li>";
        echo "<li><strong>Student:</strong> student@lms.com / student123</li>";
        echo "</ul>";
    }
    
    public function clearAll()
    {
        // Emergency function to clear everything and start fresh
        $session = \Config\Services::session();
        $session->destroy();
        session_destroy();
        
        echo "<h2>🧹 Emergency System Reset</h2>";
        echo "<p style='color: green;'>✅ All sessions cleared</p>";
        echo "<p style='color: green;'>✅ Ready for fresh start</p>";
        
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>🔄 Next Steps:</h3>";
        echo "<ol>";
        echo "<li><strong>Fix Accounts:</strong> <a href='" . base_url('/fix-users') . "'>Click here to create fresh user accounts</a></li>";
        echo "<li><strong>Test Login:</strong> Go to login page and try with test accounts</li>";
        echo "<li><strong>Check Dashboards:</strong> Each role should redirect to proper dashboard</li>";
        echo "</ol>";
        echo "</div>";
        
        echo "<p><a href='" . base_url('/') . "' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Start Fresh - Go to Homepage</a></p>";
    }
}
