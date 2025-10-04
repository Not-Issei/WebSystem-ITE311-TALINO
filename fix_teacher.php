<?php
// Fix teacher account
require_once 'app/Config/Database.php';

$db = \Config\Database::connect();

// Delete existing teacher account if it exists
$builder = $db->table('users');
$builder->where('email', 'teacher@lms.com')->delete();

// Insert new teacher account with proper password hash
$teacherData = [
    'name' => 'John Teacher',
    'email' => 'teacher@lms.com',
    'password' => password_hash('teacher123', PASSWORD_DEFAULT),
    'role' => 'teacher',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
];

$builder = $db->table('users');
$result = $builder->insert($teacherData);

if ($result) {
    echo "<h2>✅ Teacher Account Fixed!</h2>";
    echo "<p><strong>Email:</strong> teacher@lms.com</p>";
    echo "<p><strong>Password:</strong> teacher123</p>";
    echo "<p><strong>Role:</strong> teacher</p>";
    
    // Verify the password works
    $builder = $db->table('users');
    $teacher = $builder->where('email', 'teacher@lms.com')->get()->getRowArray();
    
    if ($teacher && password_verify('teacher123', $teacher['password'])) {
        echo "<p style='color: green;'><strong>✅ Password verification: SUCCESS</strong></p>";
        echo "<p><a href='/login'>Click here to login as teacher</a></p>";
    } else {
        echo "<p style='color: red;'><strong>❌ Password verification: FAILED</strong></p>";
    }
} else {
    echo "<h2>❌ Failed to create teacher account</h2>";
}

// Also create admin and student if they don't exist
$adminExists = $db->table('users')->where('email', 'admin@lms.com')->get()->getRowArray();
if (!$adminExists) {
    $adminData = [
        'name' => 'Admin User',
        'email' => 'admin@lms.com',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'role' => 'admin',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    $db->table('users')->insert($adminData);
    echo "<p>✅ Admin account created</p>";
}

$studentExists = $db->table('users')->where('email', 'student@lms.com')->get()->getRowArray();
if (!$studentExists) {
    $studentData = [
        'name' => 'Jane Student',
        'email' => 'student@lms.com',
        'password' => password_hash('student123', PASSWORD_DEFAULT),
        'role' => 'student',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    $db->table('users')->insert($studentData);
    echo "<p>✅ Student account created</p>";
}

echo "<hr>";
echo "<h3>All Users in Database:</h3>";
$users = $db->table('users')->get()->getResultArray();
foreach ($users as $user) {
    echo "<p><strong>{$user['name']}</strong> ({$user['email']}) - Role: {$user['role']}</p>";
}
?>
