<?php
require('php/connect.php');
session_start();
$role = $_SESSION['status'];
if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}
$userid = $_GET['userid'] ?? '';

// ตรวจสอบว่าได้รับ user_id หรือไม่
if (empty($userid)) {
    echo "ไม่มีรหัสผู้ใช้";
    exit();
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT * FROM tbl_users WHERE id = :userid";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit();
}

// ส่งข้อมูลผู้ใช้ไปยัง HTML เพื่อแสดงในฟอร์ม
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <link rel="stylesheet" href="css-js/edit.css">
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
            <li><button onclick="requestNotificationPermission()" class="primary-btn" >เปิดการแจ้งเตือน</button></li>
            <script src="css-js/java.js"></script>
        </ul>
    </nav>
    <div class="container">

        <div class="main-content">
            <h1>แก้ไขข้อมูลผู้ใช้</h1>

            <form action="php/edit.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $userid; ?>">

                <div class="form-group">
                    <label for="fname">ชื่อ:</label>
                    <input type="text" id="fname" name="fname" value="<?php echo $user['fname']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="lname">นามสกุล:</label>
                    <input type="text" id="lname" name="lname" value="<?php echo $user['lname']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">รหัสผ่าน (หากต้องการเปลี่ยน):</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="department">แผนก:</label>
                    <input type="text" id="department" name="department" value="<?php echo $user['department']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="status">สถานะ:</label>
                    <select id="status" name="status">
                        <option value="supervisor" <?php echo ($user['status'] == 'supervisor') ? 'selected' : ''; ?>>หัวหน้างาน</option>
                        <option value="employee" <?php echo ($user['status'] == 'employee') ? 'selected' : ''; ?>>พนักงาน</option>
                    </select>
                </div>

                <button type="submit">บันทึกการเปลี่ยนแปลง</button>
            </form>

            <button onclick="window.location.href='User-Management.php'" type="submit">กลับ</button>
        </div>
    </div>

    <script src="css-js/edit.js"></script>
</body>
</html>
