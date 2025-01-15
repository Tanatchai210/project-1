<?php
require('php/connect.php');
session_start();
$role = $_SESSION['status'];
// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

// รายงานการทำงานรายบุคคล
$sql_individual = "SELECT 
                        tbl_users.fname, 
                        tbl_users.lname, 
                        COUNT(tbl_tasks.id) AS total_tasks,
                        SUM(CASE WHEN tbl_tasks.status = 'completed' THEN 1 ELSE 0 END) AS completed_tasks
                   FROM tbl_tasks
                   JOIN tbl_users ON tbl_tasks.userid = tbl_users.id
                   GROUP BY tbl_users.id";
$stmt_individual = $conn->prepare($sql_individual);
$stmt_individual->execute();
$individual_report = $stmt_individual->fetchAll();

// รายงานการทำงานทีมและแผนก
$sql_department = "SELECT 
                        tbl_users.department,
                        COUNT(tbl_tasks.id) AS total_tasks,
                        SUM(CASE WHEN tbl_tasks.status = 'completed' THEN 1 ELSE 0 END) AS completed_tasks
                   FROM tbl_tasks
                   JOIN tbl_users ON tbl_tasks.userid = tbl_users.id
                   GROUP BY tbl_users.department";
$stmt_department = $conn->prepare($sql_department);
$stmt_department->execute();
$department_report = $stmt_department->fetchAll();

// สถิติการมอบหมายและสำเร็จของงาน
$sql_statistics = "SELECT 
                        COUNT(*) AS total_tasks,
                        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_tasks
                   FROM tbl_tasks";
$stmt_statistics = $conn->prepare($sql_statistics);
$stmt_statistics->execute();
$statistics = $stmt_statistics->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports and Analytics</title>
    <link rel="stylesheet" href="css-js/reports.css"> <!-- ไฟล์ CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- เพิ่ม Chart.js -->
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
            <h2>รายงานการทำงานรายบุคคล</h2>
            <table>
                <thead>
                    <tr>
                        <th>ชื่อพนักงาน</th>
                        <th>งานทั้งหมด</th>
                        <th>งานที่เสร็จสิ้น</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($individual_report as $report) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['fname']) . " " . htmlspecialchars($report['lname']); ?></td>
                            <td><?php echo $report['total_tasks']; ?></td>
                            <td><?php echo $report['completed_tasks']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h2>รายงานการทำงานตามแผนก</h2>
            <table>
                <thead>
                    <tr>
                        <th>แผนก</th>
                        <th>งานทั้งหมด</th>
                        <th>งานที่เสร็จสิ้น</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($department_report as $report) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($report['department']); ?></td>
                            <td><?php echo $report['total_tasks']; ?></td>
                            <td><?php echo $report['completed_tasks']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <h2>สถิติการมอบหมายและสำเร็จของงาน</h2>
            <canvas id="taskChart"></canvas>    
        </div>
    </main>
    <script>
        // สร้างกราฟด้วย Chart.js
        const ctx = document.getElementById('taskChart').getContext('2d');
        const taskChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['งานทั้งหมด', 'งานที่เสร็จสิ้น'],
                datasets: [{
                    label: 'สถิติการมอบหมายและสำเร็จของงาน',
                    data: [<?php echo $statistics['total_tasks']; ?>, <?php echo $statistics['completed_tasks']; ?>],
                    backgroundColor: ['#FF6384', '#36A2EB'],
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>
