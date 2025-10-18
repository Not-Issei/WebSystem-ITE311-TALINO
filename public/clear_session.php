<?php
// Direct session clearing - bypasses CodeIgniter
session_start();
session_destroy();

// Clear all cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Cleared</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #cce5ff; color: #004085; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; }
    </style>
</head>
<body>
    <h1>🧹 Session Cleared Successfully!</h1>
    
    <div class="success">
        <h3>✅ What was cleared:</h3>
        <ul>
            <li>All PHP sessions destroyed</li>
            <li>All browser cookies cleared</li>
            <li>User authentication data removed</li>
        </ul>
    </div>
    
    <div class="info">
        <h3>🔄 Next Steps:</h3>
        <ol>
            <li><strong>Close all browser tabs</strong> for localhost</li>
            <li><strong>Clear browser cache</strong> (Ctrl+Shift+Delete)</li>
            <li><strong>Restart your browser</strong> or use incognito mode</li>
            <li><strong>Fix user accounts</strong> using the button below</li>
            <li><strong>Try logging in fresh</strong></li>
        </ol>
    </div>
    
    <h3>🎯 Quick Actions:</h3>
    <a href="/fix-users" class="btn btn-success">Fix User Accounts</a>
    <a href="/login" class="btn">Go to Login</a>
    <a href="/" class="btn btn-warning">Homepage</a>
    
    <hr>
    <h3>📝 Test Accounts (after fixing):</h3>
    <ul>
        <li><strong>Admin:</strong> admin@lms.com / admin123</li>
        <li><strong>Teacher:</strong> teacher@lms.com / teacher123</li>
        <li><strong>Student:</strong> student@lms.com / student123</li>
    </ul>
    
    <div class="info">
        <h4>🔍 If you're still having issues:</h4>
        <p>The "Whoops!" errors suggest there might be a CodeIgniter configuration issue. Try:</p>
        <ol>
            <li>Restart your XAMPP server</li>
            <li>Check if all files are properly saved</li>
            <li>Make sure your database is running</li>
        </ol>
    </div>
</body>
</html>
