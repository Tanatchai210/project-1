// script.js

document.getElementById('edit-user-form').addEventListener('submit', function(event) {
    // Prevent form submission if there is an error
    if (!validateForm()) {
        event.preventDefault();
    }
});

function validateForm() {
    let fname = document.getElementById('fname').value;
    let lname = document.getElementById('lname').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let department = document.getElementById('department').value;
    let status = document.getElementById('status').value;
    let errorMessage = '';

    // ตรวจสอบว่าฟิลด์ชื่อและนามสกุลไม่ว่าง
    if (fname.trim() === '') {
        errorMessage += 'กรุณากรอกชื่อ\n';
    }

    if (lname.trim() === '') {
        errorMessage += 'กรุณากรอกนามสกุล\n';
    }

    // ตรวจสอบอีเมลว่าเป็นรูปแบบที่ถูกต้อง
    if (email.trim() === '') {
        errorMessage += 'กรุณากรอกอีเมล\n';
    } else if (!validateEmail(email)) {
        errorMessage += 'รูปแบบอีเมลไม่ถูกต้อง\n';
    }

    // หากกรอกรหัสผ่านให้ตรวจสอบความยาว
    if (password && password.length < 6) {
        errorMessage += 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร\n';
    }

    // ตรวจสอบแผนกและสถานะ
    if (department.trim() === '') {
        errorMessage += 'กรุณากรอกแผนก\n';
    }

    if (status.trim() === '') {
        errorMessage += 'กรุณาเลือกสถานะ\n';
    }

    // ถ้ามีข้อผิดพลาดแสดงข้อความเตือน
    if (errorMessage !== '') {
        alert(errorMessage);
        return false;
    }

    return true;
}

// ฟังก์ชันตรวจสอบรูปแบบอีเมล
function validateEmail(email) {
    const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return re.test(email);
}
