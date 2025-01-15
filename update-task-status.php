<?php
session_start();
// เชื่อมต่อฐานข้อมูล
require('php/connect.php');

// ตรวจสอบว่ามีการส่ง `task_id` และ `status` ผ่าน URL หรือไม่
if (isset($_GET['id']) && isset($_GET['status'])) {
    $task_id = $_GET['id'];
    $status = $_GET['status'];

    // ตรวจสอบว่า status ที่ส่งมาถูกต้องหรือไม่ (ควรเป็น "first", "second", หรือ "third")
    $valid_statuses = ['success'];
    if (!in_array($status, $valid_statuses)) {
        echo "<script>
                alert('สถานะไม่ถูกต้อง');
                window.location.href = 'Task-Management.php';
              </script>";
        exit;
    }

    // สร้างคำสั่ง SQL เพื่ออัปเดตสถานะของงาน
    $sql = "UPDATE tbl_tasks SET check_status = 'pass' WHERE id = :task_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':task_id', $task_id);

    // รันคำสั่ง SQL
    if ($stmt->execute()) {
        echo "<script>
                alert('อัปเดตสถานะงานเรียบร้อย!');
                window.location.href = 'Task-Management.php';
              </script>";
    } else {
        echo "<script>
                alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
                window.location.href = 'Task-Management.php';
              </script>";
    }
} else {
    // หากไม่ได้ส่ง `task_id` หรือ `status` มาทาง URL
    echo "<script>
            alert('ข้อมูลไม่ครบถ้วน');
            window.location.href = 'Task-Management.php';
          </script>";
    exit;
}
?>
