<?php
require('php/connect.php');
session_start();
$userid = $_SESSION['userid'];
$role = $_SESSION['status'];
// ตรวจสอบการล็อกอินของหัวหน้างาน
if (!isset($_SESSION['userid']) || $_SESSION['status'] !== 'supervisor') {
    header('Location: index.php');
    exit;
}

// ตรวจสอบว่าผู้ใช้เป็นหัวหน้างานหรือไม่
$userid = $_SESSION['userid'];
$sql = "SELECT * FROM tbl_users WHERE id = :userid";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ดึงข้อมูลงานที่ทำสำเร็จ
$sql_completed = "SELECT * FROM tbl_tasks WHERE status = 'success' ORDER BY created_at DESC";
$stmt_completed = $conn->prepare($sql_completed);
$stmt_completed->execute();
$completed_tasks = $stmt_completed->fetchAll();

// ดึงข้อมูลประสิทธิภาพของสมาชิกในทีม
$sql_performance = "SELECT tbl_users.fname, tbl_users.lname, COUNT(tbl_tasks.id) AS task_count
                    FROM tbl_tasks
                    JOIN tbl_users ON tbl_tasks.userid = tbl_users.id
                    WHERE tbl_tasks.status = 'completed'
                    GROUP BY tbl_users.id";
$stmt_performance = $conn->prepare($sql_performance);
$stmt_performance->execute();
$performance_data = $stmt_performance->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานทีม</title>
    <link rel="stylesheet" href="css-js/team-reports.css"> <!-- รวม CSS -->
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
                <h2>รายงานงานที่ทีมทำสำเร็จ</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ชื่อเรื่อง</th>
                            <th>วันที่มอบหมาย</th>
                            <th>สถานะ</th>
                            <th>ผู้ทำงาน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completed_tasks as $task) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['name']); ?></td>
                                <td><?php echo htmlspecialchars($task['created_at']); ?></td>
                                <td><?php echo $task['check_status'] == 'pass' ? 'เสร็จสิ้น' : 'ต้องปรับปรุง'; ?></td>
                                <td>
                                    <?php 
                                    $user_id = $task['userid'];
                                    $stmt_user = $conn->prepare("SELECT fname, lname FROM tbl_users WHERE id = :userid");
                                    $stmt_user->bindParam(':userid', $user_id);
                                    $stmt_user->execute();
                                    $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
                                    echo $user_data['fname'] . ' ' . $user_data['lname'];
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h2>ประสิทธิภาพของสมาชิกในทีม</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ชื่อ</th>
                            <th>จำนวนงานที่ทำสำเร็จ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($performance_data as $performance) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($performance['fname']) . ' ' . htmlspecialchars($performance['lname']); ?></td>
                                <td><?php echo htmlspecialchars($performance['task_count']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
