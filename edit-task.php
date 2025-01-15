<?php
// เรียกเชื่อมต่อฐานข้อมูล
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}

$userid = $_SESSION['userid'];
$role = $_SESSION['status'];
require('php/connect.php');
$role = $_SESSION['status'];
// ตรวจสอบว่าได้ส่ง `task_id` มาใน URL หรือไม่
if (isset($_GET['id'])) {
    // รับค่าจาก URL
    $task_id = $_GET['id'];

    // สร้างคำสั่ง SQL เพื่อดึงข้อมูลงานที่ต้องการแก้ไข
    $sql = "SELECT * FROM tbl_tasks WHERE id = :task_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$task) {
        // หากไม่พบงานในฐานข้อมูล
        echo "<script>
                alert('ไม่พบงานที่ต้องการแก้ไข');
                window.location.href = 'Task-Management.php';
              </script>";
        exit;
    }
} else {
    // หากไม่ได้ส่ง `task_id` มาใน URL
    echo "<script>
            alert('ไม่พบข้อมูลงาน');
            window.location.href = 'Task-Management.php';
          </script>";
    exit;
}

// ตรวจสอบการส่งข้อมูลฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $task_name = $_POST['name'];
    $task_detail = $_POST['detail'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    // สร้างคำสั่ง SQL เพื่ออัปเดตข้อมูลงาน
    $sql = "UPDATE tbl_tasks SET name = :name, detail = :detail, deadline = :deadline, status = :status WHERE id = :task_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $task_name);
    $stmt->bindParam(':detail', $task_detail);
    $stmt->bindParam(':deadline', $deadline);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':task_id', $task_id);

    // รันคำสั่ง SQL
    if ($stmt->execute()) {
        if($role == "admin"){
            echo "<script>
                alert('อัปเดตงานเรียบร้อย!');
                window.location.href = 'Admin-Task-Management.php';
              </script>";
        }else{
            echo "<script>
                alert('อัปเดตงานเรียบร้อย!');
                window.location.href = 'Task-Management.php';
              </script>";
        }
    } else {
        echo "<script>
                alert('เกิดข้อผิดพลาดในการอัปเดตงาน');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขงาน</title>
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
</nav><br>

    <main>
        <h2>แก้ไขงาน</h2>
        <form action="edit-task.php?id=<?php echo $task_id; ?>" method="POST">
            <label for="name">ชื่อเรื่อง:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($task['name']); ?>" required>

            <label for="detail">รายละเอียด:</label>
            <textarea id="detail" name="detail" required><?php echo htmlspecialchars($task['detail']); ?></textarea>

            <label for="deadline">กำหนดส่ง:</label>
            <input type="datetime-local" id="deadline" name="deadline" value="<?php echo $task['deadline']; ?>" required>

            <label for="status">สถานะ:</label>
            <select id="status" name="status" required>
                <option value="first" <?php echo ($task['status'] == 'first') ? 'selected' : ''; ?>>ยังไม่เริ่มทำงาน</option>
                <option value="second" <?php echo ($task['status'] == 'second') ? 'selected' : ''; ?>>กำลังทำงาน</option>
                <option value="success" <?php echo ($task['status'] == 'success') ? 'selected' : ''; ?>>เสร็จสิ้น</option>
            </select><br>

            <button type="submit">อัปเดตงาน</button>
        </form>
    </main>
</body>
</html>
