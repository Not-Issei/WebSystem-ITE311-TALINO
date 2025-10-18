<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Database View - ITE311 TALINO</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 1.1em;
            opacity: 0.9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .role-admin {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
        }
        .role-teacher {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            color: white;
        }
        .role-student {
            background: linear-gradient(135deg, #45b7d1, #96c93d);
            color: white;
        }
        .credentials-section {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 10px;
            border-left: 5px solid #ff6b6b;
        }
        .credentials-section h3 {
            color: #333;
            margin-top: 0;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background: rgba(255,255,255,0.7);
            border-radius: 5px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: transform 0.3s ease;
        }
        .back-link:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Users Database View</h1>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['total'] ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['admin'] ?></div>
                <div class="stat-label">Admins</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['teacher'] ?></div>
                <div class="stat-label">Teachers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['student'] ?></div>
                <div class="stat-label">Students</div>
            </div>
        </div>
        
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <h3>No users found</h3>
                <p>Make sure to run the database seeder first:</p>
                <code>php spark db:seed UserSeeder</code>
            </div>
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
        
        <div class="credentials-section">
            <h3>🔑 Default Login Credentials</h3>
            <div class="credential-item">
                <strong>Admin:</strong> admin@lms.com / admin123
            </div>
            <div class="credential-item">
                <strong>Teacher:</strong> teacher@lms.com / teacher123
            </div>
            <div class="credential-item">
                <strong>Student:</strong> student@lms.com / student123
            </div>
        </div>
        
        <a href="/login" class="back-link">← Go to Login</a>
        <a href="/" class="back-link">← Go to Home</a>
    </div>
</body>
</html>
