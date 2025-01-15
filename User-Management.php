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
    <title>หน้าจัดการผู้ใช้</title>
    <link rel="stylesheet" href="css-js/User-Management.css">
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
            <h1>จัดการผู้ใช้</h1>

            <div class="search-bar">
                <input type="text" id="search" placeholder="ค้นหาผู้ใช้...">
                <button onclick="searchUser()">ค้นหา</button>
            </div><br>

            <table id="user-table">
                <thead>
                    <tr>
                        <th>ชื่อ</th>
                        <th>นามกุล</th>
                        <th>อีเมล</th>
                        <th>แผนก</th>
                        <th>สถานะ</th>
                        <th></th>
                    </tr>
                </thead>
                <?php 
                    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE id");
                    $stmt->execute();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                ?>
                <tbody>
                    <td><?php echo $row['fname']; ?></td>
                    <td><?php echo $row['lname']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td><?php if($row['status'] == "employee"){echo "พนักงาน";} if($row['status'] == "supervisor"){echo "หัวหน้างาน";} if($row['status'] == "admin"){echo "แอดมิน";} ?></td>
                    <td><a href="edit.php?userid=<?php echo $row['id']; ?>" class="btn btn-primary ">แก้ไขข้อมูล</a><a href="php/del.php?userid=<?php echo $row['id']; ?>" class="btn btn-danger">ลบผู้ใช้นี้</a></td>
                </tbody>
                <?php } ?>
            </table>

            <br><button id="add-user-btn" onclick="window.location.href='add_user.php'">เพิ่มผู้ใช้</button>
        </div>
    </div>

    <script src="css-js/User-Management.js"></script>
</body>
</html>
