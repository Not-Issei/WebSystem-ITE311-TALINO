<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Error - ITE311 TALINO</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        h1 {
            color: #ff6b6b;
            font-size: 3em;
            margin-bottom: 20px;
        }
        .error-message {
            background: #ffe6e6;
            border: 1px solid #ffcccc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            color: #cc0000;
        }
        .suggestion {
            background: #e6f3ff;
            border: 1px solid #cce6ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            color: #0066cc;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>⚠️ Database Error</h1>
        
        <div class="error-message">
            <strong>Error:</strong> <?= htmlspecialchars($error) ?>
        </div>
        
        <div class="suggestion">
            <strong>Suggestion:</strong> <?= htmlspecialchars($suggestion) ?>
        </div>
        
        <p>Please check:</p>
        <ul style="text-align: left;">
            <li>XAMPP MySQL service is running</li>
            <li>Database 'lms_talino' exists</li>
            <li>Users table has been created (run migrations)</li>
            <li>Database credentials in app/Config/Database.php are correct</li>
        </ul>
        
        <a href="/" class="back-link">← Go to Home</a>
        <a href="/login" class="back-link">← Go to Login</a>
    </div>
</body>
</html>
