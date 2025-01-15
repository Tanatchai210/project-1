<?php
// รับข้อมูลจากคำขอ POST
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['status'])) {
    $taskId = $data['id'];
    $status = $data['status'];

    // เชื่อมต่อกับฐานข้อมูล (แก้ไขให้เหมาะสม)
    $mysqli = new mysqli("localhost", "username", "password", "database");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // อัปเดตสถานะงานในฐานข้อมูล
    $stmt = $mysqli->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $taskId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Status updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>
