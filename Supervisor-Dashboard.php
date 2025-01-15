<?php
require('php/connect.php');
session_start();
$role = $_SESSION['status'];
// ตรวจสอบการล็อกอินของหัวหน้างาน
if (!isset($_SESSION['userid']) || $_SESSION['status'] !== 'supervisor') {
    header('Location: index.php');
    exit;
}

// ดึงข้อมูลภาพรวมงานและสถิติจากฐานข้อมูล
$teamStats = [
    'assigned' => 0,
    'in_progress' => 0,
    'completed' => 0,
];

$employeePerformance = [];

try {
    // งานในทีม
    $stmt = $conn->prepare("SELECT status, COUNT(*) as count FROM tbl_tasks GROUP BY status");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tasks as $task) {
        $status = $task['status'];
        $count = $task['count'];

        if ($status === 'first') $teamStats['first'] = $count;
        if ($status === 'second') $teamStats['second'] = $count;
        if ($status === 'success') $teamStats['success'] = $count;
        if ($status === 'completed') $teamStats['completed'] = $count;
    }

    // สถิติพนักงาน
    $stmt = $conn->prepare("
        SELECT 
    u.id AS userid, 
    u.fname, 
    u.lname, 
    COUNT(CASE WHEN t.status = 'first' THEN 1 END) AS tasks_first,
    COUNT(CASE WHEN t.status = 'completed' THEN 1 END) AS tasks_completed,
    COUNT(CASE WHEN t.status = 'second' THEN 1 END) AS tasks_second,
    COUNT(CASE WHEN t.status = 'success' THEN 1 END) AS tasks_success,
    COUNT(CASE WHEN t.status IN ('first', 'success', 'second') THEN 1 END) AS total_tasks
    FROM tbl_users u
    LEFT JOIN tbl_tasks t ON t.userid = u.id
    GROUP BY u.id, u.fname, u.lname
    ORDER BY total_tasks DESC;


    ");
    $stmt->execute();
    $employeePerformance = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - หัวหน้างาน</title>
    <link rel="stylesheet" href="css-js/Supervisor-Dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<div class="dashboard-container">
    <h1>Dashboard หัวหน้างาน</h1>
    
    <!-- ภาพรวมงานในทีม -->
    <section class="overview">
        <h2>ภาพรวมงานในทีม</h2>
        <canvas id="teamOverviewChart"></canvas>
    </section>

    <!-- สถิติประสิทธิภาพของพนักงาน -->
    <section class="performance">
        <h2>สถิติประสิทธิภาพของพนักงาน</h2>
        <table>
            <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>จำนวนงานทั้งหมด</th>
                    <th>จำนวนงานที่ยังไมเริ่มทำ</th>
                    <th>จำนวนงานที่กำลังทำ</th>
                    <th>จำนวนงานที่รอตรวจ</th>
                    <th>จำนวนงานที่เสร็จสิ้น</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employeePerformance as $employee): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employee['fname'] . ' ' . $employee['lname']); ?></td>
                        <td><?php echo $employee['total_tasks']; ?></td>
                        <td><?php echo $employee['tasks_first']; ?></td>
                        <td><?php echo $employee['tasks_second']; ?></td>
                        <td><?php echo $employee['tasks_success']; ?></td>
                        <td><?php echo $employee['tasks_completed']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

<script src="scripts.js"></script>
<script>
    const teamStats = <?php echo json_encode($teamStats); ?>;

    // สร้างกราฟภาพรวมงานในทีม
    const ctx = document.getElementById('teamOverviewChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['งานที่มอบหมาย', 'งานกำลังทำ','งานที่รอตรวจ', 'งานเสร็จสิ้น'],
            datasets: [{
                data: [teamStats.first, teamStats.second,teamStats.success, teamStats.completed],
                backgroundColor: ['#ff6384', '#36a2eb','#5485f6', '#4caf50'],
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
</body>
</html>
