<?php
require('php/connect.php');
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}

$userid = $_SESSION['userid'];
$role = $_SESSION['status'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้ใช้</title>
    <link rel="stylesheet" href="css-js/add_user.css">
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
    <div class="container"> 

        <div class="main-content">
            <h1>เพิ่มผู้ใช้ใหม่</h1>

            <form action="php/add_user.php" method="POST">
                <div class="form-group">
                    <label for="fname">ชื่อ:</label>
                    <input type="text" id="fname" name="fname" placeholder="กรุณากรอกชื่อจริง" required>
                </div>

                <div class="form-group">
                    <label for="lname">นามสกุล:</label>
                    <input type="text" id="lname" name="lname" placeholder="กรุณากรอกนามสกุล" required>
                </div>

                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" id="email" name="email" placeholder="กรุณากรอกอีเมล" required>
                </div>

                <div class="form-group">
                    <label for="password">รหัสผ่าน:</label>
                    <input type="password" id="password" name="password" placeholder="กรุณากรอกรหัสผ่าน" required>
                </div>

                <div class="form-group">
                    <label for="department">แผนก:</label>
                    <select id="department" name="department">
                        <option value="คอมพิวเตอร์ธุรกิจ">คอมพิวเตอร์ธุรกิจ</option>
                        <option value="เทคโนโลยีสารสนเทศ">เทคโนโลยีสารสนเทศ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">สถานะ:</label>
                    <select id="status" name="status">
                        <option value="employee">นักเรียน</option>
                        <option value="supervisor">คุณครู</option>
                    </select>
                </div>

                <button type="submit">เพิ่มผู้ใช้</button>
            </form>

            <br><button onclick="window.location.href='User-Management.php'">กลับ</button>
        </div>
    </div>

    <script src="css-js/add_user.js"></script>
</body>
</html>
