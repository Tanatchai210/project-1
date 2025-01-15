<?php
require('php/connect.php');
session_start();

// ตรวจสอบว่าเป็นผู้ใช้ที่ล็อกอินหรือไม่
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit;
}

$userid = $_SESSION['userid'];
$role = $_SESSION['status'];

// ดึงข้อมูลภาพรวมงานทั้งหมด
$sql_overview = "SELECT 
                    (SELECT COUNT(*) FROM tbl_tasks WHERE status IN ('first','second','success')) AS pending_tasks,
                    (SELECT COUNT(*) FROM tbl_tasks WHERE check_status = 'npass') AS npass_tasks,
                    (SELECT COUNT(*) FROM tbl_tasks WHERE status = 'completed') AS completed_tasks";
$stmt_overview = $conn->prepare($sql_overview);
$stmt_overview->execute();
$overview = $stmt_overview->fetch(PDO::FETCH_ASSOC);

// ดึงข้อมูลประสิทธิภาพพนักงานและหัวหน้างาน
$sql_performance = "SELECT 
                        tbl_users.fname, 
                        tbl_users.lname, 
                        COUNT(tbl_tasks.id) AS task_count 
                    FROM tbl_tasks
                    JOIN tbl_users ON tbl_tasks.userid = tbl_users.id
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="css-js/Admin-Dashboard.css"> <!-- รวม CSS -->
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
            <h2>ภาพรวมงานทั้งหมดในระบบ</h2>
            <div class="overview">
                <div class="card">
                    <h3>งานที่รอดำเนินการ</h3>
                    <p><?php echo $overview['pending_tasks']; ?> งาน</p>
                </div>
                <div class="card">
                    <h3>งานที่รอหัวหน้าตรวจ</h3>
                    <p><?php echo $overview['npass_tasks']; ?> งาน</p>
                </div>
                <div class="card">
                    <h3>งานเสร็จสิ้น</h3>
                    <p><?php echo $overview['completed_tasks']; ?> งาน</p>
                </div>
            </div>

            <h2>สถิติการมอบหมายงาน/ประสิทธิภาพของพนักงาน</h2>
            <table>
                <thead>
                    <tr>
                        <th>ชื่อพนักงาน</th>
                        <th>จำนวนงานที่ได้รับมอบหมาย</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($performance_data as $data) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data['fname']) . ' ' . htmlspecialchars($data['lname']); ?></td>
                            <td><?php echo htmlspecialchars($data['task_count']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
