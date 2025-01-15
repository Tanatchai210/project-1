<?php
require('connect.php');

// ตั้งค่า timezone เป็น "Asia/Bangkok"
date_default_timezone_set('Asia/Bangkok');

// ตัวอย่างการสร้างเวลาปัจจุบัน
$created_at = date('Y-m-d H:i:s');  // เวลาในรูปแบบที่ต้องการ

// ตรวจสอบว่ามีการส่งฟอร์มมา
if (isset($_POST['create_task'])) {
    // รับข้อมูลจากฟอร์ม
    $task_name = $_POST['name'];
    $task_detail = $_POST['detail'];
    $userid = $_POST['userid'];  // ID ของผู้ที่มอบหมายงาน
    $deadline = $_POST['deadline'];
    $created_by = $_POST['created_by'];  // ID ของผู้ที่สร้างงาน
    $created_at = date('Y-m-d H:i:s');  // เวลาอัตโนมัติ

    // คำสั่ง SQL สำหรับเพิ่มงานใหม่
    $sql = "INSERT INTO tbl_tasks (name, created_at, detail, userid, deadline, created_by) 
            VALUES (:name, :created_at, :detail, :userid, :deadline, :created_by)";

    try {
        // เตรียมคำสั่ง SQL
        $stmt = $conn->prepare($sql);

        // ผูกค่ากับตัวแปร
        $stmt->bindParam(':name', $task_name);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':detail', $task_detail);
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':created_by', $created_by);

        // รันคำสั่ง SQL
        $stmt->execute();

        // แสดงข้อความแจ้งเตือนการสร้างงานสำเร็จ
        echo "<script>
                alert('งานถูกสร้างเรียบร้อย!');
                window.location.href = '../Task-Management.php'; // หรือหน้าอื่นๆ ที่คุณต้องการ
              </script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
