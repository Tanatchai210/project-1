<?php
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
    <title>รายละเอียดงาน</title>
    <link rel="stylesheet" href="css-js/task-detail.css">
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
            <div class="task-details">
                <?php
                require('php/connect.php');
                $task_id = $_GET['id']; // รับ ID ของงานจาก URL
                $sql = "SELECT * FROM tbl_tasks WHERE id = :task_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':task_id', $task_id);
                $stmt->execute();
                $task = $stmt->fetch();

                if ($task) {
                    echo "<h2>ชื่อเรื่อง: " . htmlspecialchars($task['name']) . "</h2>";
                    echo "<p><strong>รายละเอียด:</strong> " . htmlspecialchars($task['detail']) . "</p>";
                    echo "<p><strong>วันที่มอบหมาย:</strong> " . htmlspecialchars($task['created_at']) . "</p>";
                    echo "<p><strong>กำหนดส่ง:</strong> " . htmlspecialchars($task['deadline']) . "</p>";
                } else {
                    echo "<p>ไม่พบรายละเอียดงาน</p>";
                }
                ?>
            </div>

            <div class="upload-section">
                <h3>อัปโหลดไฟล์และรูปภาพ</h3>
                <form action="php/upload-file.php?userid=<?php echo $userid ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                    <div class="form-group">
                        <label for="file">เลือกไฟล์:</label>
                        <input type="file" name="file" id="file" accept="image/*,.pdf,.doc,.docx,.xlsx,.ppt,.pptx" required>
                    </div>
                    <button type="submit" class="upload-btn">อัปโหลด</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
