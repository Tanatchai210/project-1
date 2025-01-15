<?php
require_once('connect.php'); // เชื่อมต่อกับฐานข้อมูล
session_start(); // เริ่มต้น session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // ตรวจสอบข้อมูลในฐานข้อมูล
    try {
        // ดึงข้อมูลผู้ใช้ที่มีอีเมลตรงกับที่ป้อน
        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // ตรวจสอบว่าพบผู้ใช้และรหัสผ่านถูกต้องหรือไม่
        if ($user && password_verify($password, $user['password'])) {
            // เก็บข้อมูลผู้ใช้ใน session
            $_SESSION['userid'] = $user['id'];
            $_SESSION['status'] = $user['status'];

            // แบ่งสมาชิกตามสถานะ
            switch ($user['status']) {
                case 'employee':
                    echo "<script>alert('เข้าสู่ระบบสำเร็จ'); window.location.href = '../User-Dashboard.php';</script>";
                    exit;
                case 'supervisor':
                    echo "<script>alert('เข้าสู่ระบบสำเร็จ'); window.location.href = '../Supervisor-Dashboard.php';</script>";
                    exit;
                case 'admin':
                    echo "<script>alert('เข้าสู่ระบบสำเร็จ'); window.location.href = '../User-Management.php';</script>";
                    exit;
                default:
                    echo "สถานะไม่ถูกต้อง";
            }
        } else {
            // หากอีเมลหรือรหัสผ่านไม่ถูกต้อง
            echo  "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
        }

    } catch (PDOException $e) {
        // กรณีเกิดข้อผิดพลาดระหว่างการดึงข้อมูล
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}
?>
