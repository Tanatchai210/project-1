<?php
session_start();
$role = $_SESSION['status'];
if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}
require('php/connect.php');

// รับค่าจาก URL (task_id) เพื่อดึงข้อมูลงานที่ต้องการตรวจสอบ
$task_id = $_GET['task_id'];

// ดึงข้อมูลงานจากฐานข้อมูล
$sql = "SELECT * FROM tbl_tasks WHERE id = :task_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':task_id', $task_id);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM tbl_task_files WHERE task_id = :task_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':task_id', $task_id);
$stmt->execute();
$task_file = $stmt->fetch(PDO::FETCH_ASSOC);

// ตรวจสอบว่าไม่พบงานในระบบ
if (!$task) {
    echo "ไม่พบข้อมูลงานนี้";
    exit;
}

// รับความคิดเห็นหรือคำแนะนำที่ส่งจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comments = $_POST['comments'];
    $check_status = $_POST['check_status'];  // สถานะที่เลือก (ผ่าน หรือ ต้องปรับปรุง)

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE tbl_tasks SET comments = :comments,status = 'completed', check_status = :check_status WHERE id = :task_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':comments', $comments);
    $stmt->bindParam(':check_status', $check_status);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->execute();

    // แสดงข้อความแจ้งเตือนเมื่ออัปเดตสถานะสำเร็จ
    echo "<script>
            alert('อัปเดตสถานะงานเรียบร้อย!');
            window.location.href = 'Task-Review-Dashboard.php?task_id=$task_id';
          </script>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบงาน</title>
    <link rel="stylesheet" href="css-js/task-review.css"> <!-- ใส่ไฟล์ CSS -->
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
            <h2>ตรวจสอบงาน: <?php echo htmlspecialchars($task['name']); ?></h2>
            <p><strong>รายละเอียด:</strong> <?php echo nl2br(htmlspecialchars($task['detail'])); ?></p>
            <p><strong>วันที่มอบหมาย:</strong> <?php echo htmlspecialchars($task['created_at']); ?></p>
            <p><strong>กำหนดส่ง:</strong> <?php echo htmlspecialchars($task['deadline']); ?></p>

            <!-- แสดงรูปภาพ -->
            <?php if (!empty($task_file['file_name'])): ?>
    <p><strong>รูปภาพที่เกี่ยวข้อง:</strong></p>
    <img 
        src="php/uploads/<?php echo htmlspecialchars(basename($task_file['file_name'])); ?>" 
        alt="กดดาวโหลดไฟล์" 
        style="max-width: 100%; height: auto;">
    <?php else: ?>
        <p><em>ไม่มีรูปภาพที่เกี่ยวข้อง</em></p>
    <?php endif; ?>


            <!-- แสดงไฟล์แนบ -->
            <?php if (!empty($task_file['file_name'])): ?>
                <p><strong>ไฟล์แนบ:</strong></p>
                <a href="php/uploads/<?php echo htmlspecialchars($task_file['file_name']); ?>" download>ดาวน์โหลดไฟล์</a>
            <?php endif; ?>

            <!-- ฟอร์มสำหรับการให้ความคิดเห็น -->
            <form action="Task-Review.php?task_id=<?php echo $task_id; ?>" method="POST">
                <label for="comments">ความคิดเห็น/คำแนะนำ:</label>
                <textarea id="comments" name="comments" rows="5"><?php echo htmlspecialchars($task['comments']); ?></textarea>

                <label for="check_status">สถานะงาน:</label>
                <select name="check_status" id="check_status">
                    <option value="pass" <?php if ($task['check_status'] == 'pass') echo 'selected'; ?>>ผ่าน</option>
                    <option value="npass" <?php if ($task['check_status'] == 'npass') echo 'selected'; ?>>ยังไม่ผ่าน</option>
                </select>

                <button type="submit">บันทึกการตรวจสอบ</button>
            </form>
        </div>
    </main>
</body>
</html>
