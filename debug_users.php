<?php
// Simple debug script to check users in database
require_once 'app/Config/Database.php';

$db = \Config\Database::connect();
$builder = $db->table('users');
$users = $builder->get()->getResultArray();

echo "<h2>Users in Database:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created At</th></tr>";

foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . $user['name'] . "</td>";
    echo "<td>" . $user['email'] . "</td>";
    echo "<td>" . $user['role'] . "</td>";
    echo "<td>" . $user['created_at'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// Test password verification for teacher
$teacherEmail = 'teacher@lms.com';
$teacherPassword = 'teacher123';

$builder = $db->table('users');
$teacher = $builder->where('email', $teacherEmail)->get()->getRowArray();

echo "<h2>Teacher Account Test:</h2>";
if ($teacher) {
    echo "<p><strong>Teacher found:</strong> " . $teacher['name'] . " (" . $teacher['email'] . ")</p>";
    echo "<p><strong>Role:</strong> " . $teacher['role'] . "</p>";
    
    if (password_verify($teacherPassword, $teacher['password'])) {
        echo "<p style='color: green;'><strong>✅ Password verification: SUCCESS</strong></p>";
        echo "<p>You should be able to login with: teacher@lms.com / teacher123</p>";
    } else {
        echo "<p style='color: red;'><strong>❌ Password verification: FAILED</strong></p>";
        echo "<p>There's an issue with the password hash.</p>";
    }
} else {
    echo "<p style='color: red;'><strong>❌ Teacher account not found!</strong></p>";
    echo "<p>The seeder may not have run properly.</p>";
}
?>
