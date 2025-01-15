<?php
// เรียกเชื่อมต่อฐานข้อมูล
require('connect.php');

// ตรวจสอบว่าได้ส่ง `task_id` มาใน URL หรือไม่
if (isset($_GET['id'])) {
    // รับค่าจาก URL
    $task_id = $_GET['id'];

    // สร้างคำสั่ง SQL เพื่อลบงานจากฐานข้อมูล
    $sql = "DELETE FROM tbl_tasks WHERE id = :id";
    
    try {
        // เตรียมคำสั่ง SQL
        $stmt = $conn->prepare($sql);
        
        // ผูกค่ากับตัวแปร
        $stmt->bindParam(':id', $task_id);
        
        // รันคำสั่ง SQL
        $stmt->execute();
        
        // ส่งกลับผู้ใช้ไปที่หน้า Task Management หรือหน้าที่ต้องการหลังจากลบเสร็จ
        echo "<script>
                alert('ลบงานสำเร็จ!');
                window.location.href = '../Task-Management.php'; // หรือหน้าที่คุณต้องการ
              </script>";
    } catch (PDOException $e) {
        // หากเกิดข้อผิดพลาดในการลบ
        echo "<script>
                alert('เกิดข้อผิดพลาดในการลบงาน: " . $e->getMessage() . "');
                window.location.href = '../Task-Management.php'; 
              </script>";
    }
} else {
    // หากไม่ได้ส่ง `task_id` มา
    echo "<script>
            alert('ไม่พบข้อมูลงาน');
            window.location.href = '../Task-Management.php';
          </script>";
}
?>
