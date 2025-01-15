<?php
require('connect.php');

$userid = $_GET['userid'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $file = $_FILES['file'];

    // ตรวจสอบว่าอัปโหลดไฟล์สำเร็จหรือไม่
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($file['name']);
        $fileTmpPath = $file['tmp_name'];
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . $fileName;

        // ตรวจสอบและสร้างโฟลเดอร์หากไม่มี
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        

        // ย้ายไฟล์ไปยังโฟลเดอร์ปลายทาง
        if (move_uploaded_file($fileTmpPath, $filePath)) {
            // บันทึกข้อมูลไฟล์ในฐานข้อมูล
            $sql = "INSERT INTO tbl_task_files (task_id, file_name, file_path) VALUES (:task_id, :file_name, :file_path)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->bindParam(':file_name', $fileName);
            $stmt->bindParam(':file_path', $filePath);

            if ($stmt->execute()) {
                $sql = "SELECT * FROM tbl_tasks WHERE id = :task_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':task_id', $task_id);
                
                if($stmt->execute()){
                    $sql = "UPDATE tbl_tasks SET status = 'success' WHERE id = :task_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':task_id', $task_id);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    echo "<script>
                        alert('อัปโหลดไฟล์สำเร็จ!');
                        window.location.href = '../User-Dashboard.php?userid=$userid';
                    </script>";
                }
                
            } else {
                echo "<script>
                        alert('บันทึกไฟล์ในฐานข้อมูลล้มเหลว');
                        window.location.href = '../task-detail.php?userid=$userid';
                    </script>";
            }
        } else {
            echo "<script>
                    alert('เกิดข้อผิดพลาดในการอัปโหลดไฟล์');
                    window.location.href = '../task-detail.php?userid=$userid';
                </script>";
        }
    } else {
        echo "<script>
                alert('ไม่มีไฟล์ที่ถูกอัปโหลด');
                window.location.href = '../task-detail.php?id=$userid';
            </script>";
    }
} else {
    echo "<script>
            alert('ไม่อนุญาตให้เข้าถึงหน้านี้โดยตรง');
            window.location.href = '../User-Dashboard.php';
        </script>";
}
?>
