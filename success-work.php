<?php
session_start();
require('php/connect.php');

if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}

$userid = $_SESSION['userid'];
$role = $_SESSION['status'];

$stmt = $conn->prepare("SELECT * FROM tbl_users WHERE id = :userid");
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลักของผู้ใช้</title>
    <link rel="stylesheet" href="css-js/User-Dashboard.css"> <!-- รวม CSS -->
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
        <div class="container">
            <div class="main-content">
            <a href="User-Dashboard.php?userid=<?php echo $userid; ?>" class="task-detail-btn">ดูงานที่ได้รับมอบหมาย</a><br><br>
                <h2>งานที่มอบหมายให้คุณ</h2>
                <div class="task-list">
                    <!-- รายการงานที่มอบหมายให้กับผู้ใช้ -->
                    <?php

                    // สมมุติว่าเรามีฟังก์ชันเพื่อดึงข้อมูลงานจากฐานข้อมูล
                    $sql = "SELECT * FROM tbl_tasks WHERE userid = :userid ORDER BY created_at DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':userid', $userid);
                    $stmt->execute();
                    $tasks = $stmt->fetchAll();

                    foreach ($tasks as $task) {
                        if($task['status'] == "success"){
                            echo "<div class='task-item'>";
                            echo "<h3><strong>ชื่อเรื่อง : </strong>" . htmlspecialchars($task['name']) . "</h3>";
                            echo "<p><strong>สถานะ : </strong> เสร็จสิ้น</p>";
                            echo "<p><strong>วันที่มอบหมาย : </strong> " . htmlspecialchars($task['created_at']) . "</p>";
                            if(htmlspecialchars($task['check_status']) == "pass"){
                                echo "<p><strong>สถานะการตรวจสอบ : </strong> ผ่าน</p>";
                            }else{
                                echo "<p><strong>สถานะการตรวจสอบ : </strong> ไม่ผ่าน</p>";
                            }
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
