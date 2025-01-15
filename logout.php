<?php
// เริ่ม session
session_start();

// ลบข้อมูล session ทั้งหมด
session_unset();

// ทำลาย session
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้า login หรือหน้าหลัก
echo "<script>
    alert('ออกจากระบบสำเร็จ!');
    window.location.href = 'index.php';
</script>";
exit;
?>
