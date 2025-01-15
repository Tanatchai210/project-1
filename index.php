<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #eef2f7;
        }

        .login-container {
            background: #ffffff;
            padding: 30px 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .login-container h1 {
            font-size: 24px;
            color: #333333;
            margin-bottom: 20px;
        }

        .login-container p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-container button {
            background-color: #007BFF;
            color: #ffffff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container a {
            display: block;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
            font-size: 14px;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #aaa;
        }

        .footer a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>เข้าสู่ระบบ</h1>
        <form action="php/login.php" method="POST">
            <input type="email" name="email" placeholder="อีเมล" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <a href="forgot-password.php">ลืมรหัสผ่านใช่ไหม?</a>
        <a href="register.php">ลงทะเบียน</a>
        <div class="footer">
            © ตรวจสอบและมอบหมายงาน 2025
        </div>
    </div>
</body>
</html>
