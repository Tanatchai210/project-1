<?php
session_start();
require('php/connect.php');

if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
    exit();
}

$userid = $_SESSION['userid'];
$role = $_SESSION['status'];

$sql = "SELECT fname, lname, email, department FROM tbl_users WHERE id = :userid";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $department = $_POST['department'];

    // ถ้ามีการเปลี่ยนแปลงรหัสผ่าน
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE tbl_users SET fname = :fname, lname = :lname, email = :email, password = :password, department = :department WHERE id = :userid";
    } else {
        // หากไม่มีการเปลี่ยนแปลงรหัสผ่าน
        $sql = "UPDATE tbl_users SET fname = :fname, lname = :lname, email = :email, department = :department WHERE id = :userid";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':userid', $userid);

    if (!empty($password)) {
        $stmt->bindParam(':password', $password_hash);
    }

    $stmt->execute();

    echo "<script>
        alert('ข้อมูลถูกอัปเดตเรียบร้อย!');
        window.location.href = 'profile.php';
    </script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <link rel="stylesheet" href="css-js/profile.css">
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
<div class="edit-profile-container">
    <h2>แก้ไขข้อมูลส่วนตัว</h2>
    <form method="POST">
        <label for="fname">ชื่อ:</label>
        <input type="text" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required><br>

        <label for="lname">นามสกุล:</label>
        <input type="text" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required><br>

        <label for="email">อีเมล:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="password">รหัสผ่านใหม่:</label>
        <input type="password" name="password"><br>

        <label for="department">แผนก:</label>
        <input type="text" name="department" value="<?php echo htmlspecialchars($user['department']); ?>" required><br>

        <button type="submit">บันทึกข้อมูล</button><br><br>
    </form>
    <a href="profile.php?userid=<?php echo $userid; ?>"><button type="submit">กลับ</button></a>
</div>

</body>
</html>
