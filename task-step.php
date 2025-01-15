<?php
require('php/connect.php');
$userid = $_GET['userid'];
$taskid = $_GET['taskid'];
$task_step = $_GET['step'];

try {
    if ($task_step == "first") {
        $sql = "UPDATE tbl_tasks SET status = 'second', status_new = 'old' WHERE id = :taskid";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':taskid', $taskid);
        $stmt->execute();
        echo "<script>alert('เริ่มทำงานแล้ว'); window.location.href = 'User-Dashboard.php?userid=" . $userid . "';</script>";
    }
    if ($task_step == "second") {
        $sql = "UPDATE tbl_tasks SET status = 'success' WHERE id = :taskid";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':taskid', $taskid);
        $stmt->execute();
        echo "<script>alert('ทำงานเสร็จสิ้นแล้ว'); window.location.href = 'User-Dashboard.php?userid=" . $userid . "';</script>";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
