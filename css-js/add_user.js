// ฟังก์ชันการตรวจสอบการกรอกข้อมูลฟอร์ม
document.getElementById('add-user-form').addEventListener('submit', function(e) {
    e.preventDefault(); // หยุดการส่งฟอร์ม

    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const status = document.getElementById('status').value;

    // ตรวจสอบว่าข้อมูลกรอกครบหรือไม่
    if (username && email && password && status) {
        // ส่งข้อมูลไปยัง PHP เพื่อเพิ่มผู้ใช้ใหม่
        const formData = new FormData();
        formData.append('username', username);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('status', status);

        fetch('add_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('เพิ่มผู้ใช้สำเร็จ');
                window.location.href = 'index.html'; // กลับไปที่หน้าจัดการผู้ใช้
            } else {
                alert('เกิดข้อผิดพลาด');
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert('กรุณากรอกข้อมูลให้ครบ');
    }
});
