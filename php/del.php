<?php
require('connect.php');

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST
if (isset($_GET['user_id'])) {
    // รับ ID ของผู้ใช้จากฟอร์ม
    $user_id = $_GET['user_id'];

    // สร้างคำสั่ง SQL เพื่อลบข้อมูลผู้ใช้
    $sql = "DELETE FROM tbl_users WHERE id = :user_id";

    try {
        // เตรียมคำสั่ง SQL
        $stmt = $conn->prepare($sql);

        // ผูกค่ากับตัวแปร
        $stmt->bindParam(':user_id', $user_id);

        // รันคำสั่ง
        $stmt->execute();

        // แจ้งผลลัพธ์การลบ
        echo "<script>
                alert('ลบข้อมูลผู้ใช้สำเร็จ!');
                window.location.href = '../User-Management.php';
            </script>";
    } catch (PDOException $e) {
        // หากเกิดข้อผิดพลาด
        echo "<script>
                alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "');
                window.location.href = '../User-Management.php';
            </script>";
    }
} else {
    echo "<script>
            alert('ไม่พบข้อมูลที่ต้องการลบ');
            window.location.href = ../'User-Management.php';
        </script>";
}
?>
