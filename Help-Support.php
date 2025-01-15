<?php
session_start();
$role = $_SESSION['status'];
// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}
require('php/connect.php');

// ดึงข้อมูลคำถามที่พบบ่อยจากฐานข้อมูล
$sql = "SELECT * FROM tbl_faq ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบว่ามีการส่งคำถามถึงฝ่ายสนับสนุน
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $support_sql = "INSERT INTO tbl_support (name, email, message) VALUES (:name, :email, :message)";
    $support_stmt = $conn->prepare($support_sql);
    $support_stmt->bindParam(':name', $name);
    $support_stmt->bindParam(':email', $email);
    $support_stmt->bindParam(':message', $message);
    $support_stmt->execute();

    echo "<script>alert('ส่งคำถามเรียบร้อยแล้ว!');</script>";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ช่วยเหลือและสนับสนุน</title>
    <link rel="stylesheet" href="css-js/Help-Support.css">
</head>
<body>
<nav>
    <ul>
        <?php
            if($role == "supervisor"){
                echo '<li><a href="Supervisor-Dashboard.php">หน้าแดชบอร์ด</a></li>';
                echo '<li><a href="Task-Management.php">หน้าจัดการงาน</a></li>';
                echo '<li><a href="Task-Review-Dashboard.php">หน้าตรวจสอบงาน</a></li>';
                echo '<li><a href="Team-Reports.php">หน้ารายงานทีม</a></li>';
                echo '<li><a href="profile.php">ข้อมูลส่วนตัว</a></li>';
                echo '<li><a href="logout.php">ออกจากระบบ</a></li>';
            }
            if($role == "employee"){
                echo '<li><a href="User-Dashboard.php">หน้าแดชบอร์ด</a></li>';
                echo '<li><a href="profile.php">ข้อมูลส่วนตัว</a></li>';
                echo '<li><a href="logout.php">ออกจากระบบ</a></li>';
            }
            if($role == "admin"){
                echo '<li><a href="Admin-Dashboard.php">แดชบอร์ด</a></li>';
                echo '<li><a href="User-Management.php">หน้าจัดการผู้ใช้</a></li>';
                echo '<li><a href="Admin-Task-Management.php">หน้าจัดการงาน</a></li>';
                echo '<li><a href="Reports-and-Analytics.php">หน้ารายงานและสถิติ</a></li>';
                echo '<li><a href="profile.php">ข้อมูลส่วนตัว</a></li>';
                echo '<li><a href="Help-Support.php">หน้าช่วยเหลือ</a></li>';
                echo '<li><a href="logout.php">ออกจากระบบ</a></li>';
            }
        ?>
    </ul>
</nav>
    <main>
        <section class="faq-section">
            <h2>คำถามที่พบบ่อย (FAQ)</h2>
            <div class="faq-list">
                <?php foreach ($faqs as $faq): ?>
                    <div class="faq-item">
                        <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                        <p><?php echo htmlspecialchars($faq['answer']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="support-section">
            <h2>ติดต่อฝ่ายสนับสนุน</h2>
            <form action="" method="POST">
                <label for="name">ชื่อ:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">อีเมล:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="message">ข้อความ:</label>
                <textarea id="message" name="message" required></textarea>
                
                <button type="submit">ส่งคำถาม</button>
            </form>
        </section>
    </main>
</body>
</html>
