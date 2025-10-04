<?php
/**
 * Debug page to view all users in the database
 */

// Database configuration - adjust these values to match your setup
$host = 'localhost';
$dbname = 'lms_talino'; // Your actual database name
$username = 'root';
$password = ''; // Change if you have a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id, name, email, role, created_at, updated_at FROM users ORDER BY role, name");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Database View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .role-admin {
            background-color: #dc3545;
            color: white;
        }
        .role-teacher {
            background-color: #28a745;
            color: white;
        }
        .role-student {
            background-color: #007bff;
            color: white;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            min-width: 120px;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #495057;
        }
        .stat-label {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Users Database View</h1>
        
        <?php
        // Calculate statistics
        $totalUsers = count($users);
        $adminCount = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
        $teacherCount = count(array_filter($users, fn($u) => $u['role'] === 'teacher'));
        $studentCount = count(array_filter($users, fn($u) => $u['role'] === 'student'));
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= $totalUsers ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $adminCount ?></div>
                <div class="stat-label">Admins</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $teacherCount ?></div>
                <div class="stat-label">Teachers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $studentCount ?></div>
                <div class="stat-label">Students</div>
            </div>
        </div>
        
        <?php if (empty($users)): ?>
            <p style="text-align: center; color: #6c757d; font-style: italic;">
                No users found. Make sure to run the database seeder first.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="role-badge role-<?= htmlspecialchars($user['role']) ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td><?= htmlspecialchars($user['updated_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div style="margin-top: 30px; padding: 15px; background-color: #e9ecef; border-radius: 5px;">
            <h3>📝 Default Login Credentials:</h3>
            <ul>
                <li><strong>Admin:</strong> admin@lms.com / admin123</li>
                <li><strong>Teacher:</strong> teacher@lms.com / teacher123</li>
                <li><strong>Student:</strong> student@lms.com / student123</li>
            </ul>
        </div>
    </div>
</body>
</html>
