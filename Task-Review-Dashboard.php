<?php 
session_start();
require('php/connect.php');

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['userid']) || ($_SESSION['status'] !== 'supervisor' && $_SESSION['status'] !== 'admin')) {
    header('Location: index.php');
    exit;
}


// ดึงข้อมูลผู้ใช้จาก session
$userid = $_SESSION['userid'];
$role = $_SESSION['status'];

// ดึงงานทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM tbl_tasks WHERE created_by = :userid ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าจัดการงาน</title>
    <link rel="stylesheet" href="css-js/task-management.css">
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
            <!-- แสดงรายการงาน -->
            <br><h3>หน้าตรวจสอบงาน</h3>
            <table>
                <tr>
                    <th>ชื่อเรื่อง</th>
                    <th>สถานะ</th>
                    <th>กำหนดส่ง</th>
                    <th>ผู้รับผิดชอบ</th>
                    <th>จัดการ</th>
                </tr>
                
                <?php foreach ($tasks as $task) : ?>
                    <?php
                    if(htmlspecialchars($task['status']) == 'success'){
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['name']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                        
                        <td><?php 
                        $user_id = $task['userid'];
                        $sql = "SELECT * FROM tbl_users WHERE id = :user_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':user_id', $user_id);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($user_id == $row['id']){
                            echo htmlspecialchars($row['fname']).' '. htmlspecialchars($row['lname']);
                        }
                        ?></td>

                        <td>
                            <a href="Task-Review.php?task_id=<?php echo $task['id']; ?>">ตรวจสอบงาน</a>
                        </td>
                    </tr>
                    <?php } ?>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
</body>
</html>
