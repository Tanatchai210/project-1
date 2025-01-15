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
$sql = "SELECT * FROM tbl_tasks WHERE id";
$stmt = $conn->prepare($sql);
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
            <h2>การจัดการงาน</h2>
            
            <!-- ฟอร์มสร้างงานใหม่ -->
            <h3>สร้างงานใหม่</h3>
            <form action="php/Task-Management.php" method="POST">
                <label for="name">ชื่อเรื่อง:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="detail">รายละเอียด:</label>
                <textarea id="detail" name="detail" required></textarea>

                <label for="userid">ผู้มอบหมายงาน (ID):</label>
                <input type="number" id="userid" name="userid" required>

                <label for="deadline">กำหนดส่ง:</label>
                <input type="datetime-local" id="deadline" name="deadline" required>

                <label for="created_by">ผู้สร้างงาน (ID):</label>
                <input type="number" id="created_by" name="created_by" value="<?php echo $userid; ?>" readonly>

                <button type="submit" name="create_task">สร้างงาน</button>
            </form>

            
            <!-- แสดงรายการงาน -->
            <br><h3>งานที่มอบหมาย</h3>
            <table>
                <tr>
                    <th>ชื่อเรื่อง</th>
                    <th>สถานะ</th>
                    <th>กำหนดส่ง</th>
                    <th>ผู้รับผิดชอบ</th>
                    <th>จัดการ</th>
                </tr>
                <?php foreach ($tasks as $task) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['name']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                        
                        <td><?php 
                        $user_id = $task['userid'];
                        $created_id = $task['created_by'];
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
                            <a href="edit-task.php?id=<?php echo $task['id']; ?>">แก้ไข</a>
                            <a href="php/delete-task.php?id=<?php echo $task['id']; ?>" onclick="return confirm('คุณต้องการลบงานนี้?')">ลบ</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
</body>
</html>
