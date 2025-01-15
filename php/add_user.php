<?php

require('connect.php');
// รับข้อมูลจากฟอร์ม
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$department = $_POST['department'] ?? '';
$status = $_POST['status'] ?? '';

// แปลงรหัสผ่านให้เป็น hash
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// สร้างคำสั่ง SQL เพื่อเพิ่มผู้ใช้ใหม่
$sql = "INSERT INTO tbl_users (fname, lname, email, password, department, status) VALUES (:fname, :lname, :email, :password, :department, :status)";

try {
    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);

    // ผูกค่ากับตัวแปร
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password_hash);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':status', $status);

    // รันคำสั่ง
    $stmt->execute();

    // ส่งค่าผลลัพธ์กลับในรูปแบบ JSON
    echo "<script>
            alert('เพิ่มข้อมูลสำเร็จ!');
            window.location.href = '../User-Management.php';
        </script>";
} catch (PDOException $e) {
    // ส่งค่าผลลัพธ์หากเกิดข้อผิดพลาด
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
