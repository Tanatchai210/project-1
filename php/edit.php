<?php
require('connect.php');

// รับข้อมูลจากฟอร์ม
$user_id = $_POST['user_id'] ?? '';
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$department = $_POST['department'] ?? '';
$status = $_POST['status'] ?? '';

// ตรวจสอบว่ามีการกรอกรหัสผ่านใหม่หรือไม่
if (!empty($password)) {
    // แปลงรหัสผ่านให้เป็น hash หากมีการเปลี่ยนแปลง
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE tbl_users SET fname = :fname, lname = :lname, email = :email, password = :password, department = :department, status = :status WHERE id = :user_id";
} else {
    // หากไม่มีการกรอกรหัสผ่านใหม่ ให้ไม่เปลี่ยนแปลงรหัสผ่าน
    $sql = "UPDATE tbl_users SET fname = :fname, lname = :lname, email = :email, department = :department, status = :status WHERE id = :user_id";
}

try {
    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);

    // ผูกค่ากับตัวแปร
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    if (!empty($password)) {
        $stmt->bindParam(':password', $password_hash);
    }
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':user_id', $user_id);

    // รันคำสั่ง
    $stmt->execute();

    echo "<script>
            alert('แก้ไขข้อมูลสำเร็จ!');
            window.location.href = '../User-Management.php';
        </script>";

} catch (PDOException $e) {
    // ส่งค่าผลลัพธ์หากเกิดข้อผิดพลาด
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
