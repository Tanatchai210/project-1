<?php
session_start();
require('php/connect.php');

if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}

$userid = $_SESSION['userid'];
$role = $_SESSION['status'];

$sql = "SELECT fname, lname, email, department FROM tbl_users WHERE id = :userid";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลส่วนตัว</title>
    <link rel="stylesheet" href="css-js/profile.css"> <!-- ใส่ path ของไฟล์ CSS -->
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
<div class="profile-container">
    <h2>ข้อมูลส่วนตัว</h2>
    <table>
        <tr>
            <th>ชื่อ</th>
            <td><?php echo htmlspecialchars($user['fname']); ?></td>
        </tr>
        <tr>
            <th>นามสกุล</th>
            <td><?php echo htmlspecialchars($user['lname']); ?></td>
        </tr>
        <tr>
            <th>อีเมล</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <th>แผนก</th>
            <td><?php echo htmlspecialchars($user['department']); ?></td>
        </tr>
    </table>
    <a href="edit-profile.php?userid=<?php echo $userid; ?>" class="edit-button">แก้ไขข้อมูล</a>
    <?php 
    if ($role == "supervisor") {
        echo '<a href="Supervisor-Dashboard.php?userid=' . $userid . '" class="edit-button">หน้าหลัก</a>';
    }
    if ($role == "employee") {
        echo '<a href="User-Dashboard.php?userid=' . $userid . '" class="edit-button">หน้าหลัก</a>';
    }
    if ($role == "admin") {
        echo '<a href="User-Management.php?userid=' . $userid . '" class="edit-button">หน้าหลัก</a>';
    }
    ?>
</div>

</body>
</html>
