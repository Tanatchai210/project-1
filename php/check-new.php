<?php
require('connect.php');

try {
    $sql = "SELECT id, name, created_at FROM tbl_tasks WHERE status_new = 'new' ORDER BY created_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($tasks) {
        echo json_encode(['success' => true, 'tasks' => $tasks]);
    } else {
        echo json_encode(['success' => true, 'tasks' => []]); // ส่งอาร์เรย์ว่างถ้าไม่มีงานใหม่
    }
} catch (PDOException $e) {
    // ส่งข้อความข้อผิดพลาด
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
