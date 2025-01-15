// ขออนุญาตแสดงการแจ้งเตือน
function requestNotificationPermission() {
    if ('Notification' in window) {
        Notification.requestPermission()
            .then(permission => {
                if (permission === 'granted') {
                    console.log('อนุญาตการแจ้งเตือนแล้ว');
                } else {
                    console.log('ปฏิเสธการแจ้งเตือน');
                }
            });
    } else {
        console.log('เบราว์เซอร์นี้ไม่รองรับการแจ้งเตือน');
    }
}

// เก็บ ID ของงานที่เคยแจ้งเตือนแล้ว
let notifiedTasks = [];

function checkNewTasks() {
    fetch('php/check-new.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // แปลงข้อมูลเป็น JSON
        })
        .then(data => {
            console.log('Response Data:', data); // Debug ข้อมูลที่ได้รับ

            // ตรวจสอบว่าได้รับข้อมูลที่ถูกต้อง
            if (data.success && data.tasks.length > 0) {
                data.tasks.forEach(task => {
                    if (!notifiedTasks.includes(task.id)) { // ถ้าหากงานยังไม่เคยแจ้งเตือน
                        showDesktopNotification(
                            'มีงานใหม่!',
                            `ชื่อเรื่อง: ${task.name}, วันที่: ${task.created_at}`
                        );

                        // เพิ่ม ID งานที่ได้รับการแจ้งเตือน
                        notifiedTasks.push(task.id);

                        // เปลี่ยนสถานะงานเป็น "old" ในฐานข้อมูล
                        updateTaskStatusToOld(task.id);
                    }
                });
            } else {
                console.log('ไม่มีงานใหม่');
            }
        })
        .catch(error => console.error('Error fetching tasks:', error));
}

// ฟังก์ชันสำหรับแสดงการแจ้งเตือน
function showDesktopNotification(title, body) {
    if ('Notification' in window && Notification.permission === 'granted') {
        const notification = new Notification(title, {
            body: body,
            icon: 'notification-icon.png' // ไอคอนแจ้งเตือน
        });

        notification.onclick = function () {
            window.focus();
        };
    }
}

// ฟังก์ชันอัปเดตสถานะงานเป็น "old"
function updateTaskStatusToOld(taskId) {
    fetch('php/update-task-status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: taskId, status_new: 'old' })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Updated task status:', data);
    })
    .catch(error => console.error('Error updating task status:', error));
}

// เรียกฟังก์ชันตรวจสอบทุก 10 วินาที
setInterval(checkNewTasks, 10000);
