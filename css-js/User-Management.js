// ฟังก์ชันการค้นหาผู้ใช้
function searchUser() {
    const searchQuery = document.getElementById("search").value;

    // ตรวจสอบว่าผู้ใช้กรอกคำค้นหาหรือไม่
    if (searchQuery.trim() !== "") {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "search_user.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // แสดงผลลัพธ์ที่ได้จาก PHP
                document.getElementById("user-table-body").innerHTML = xhr.responseText;
            }
        };

        xhr.send("query=" + encodeURIComponent(searchQuery));
    } else {
        alert("กรุณากรอกคำค้นหาก่อน");
    }
}

// ฟังก์ชันแก้ไขผู้ใช้
function editUser(userId) {
    window.location.href = `edit_user.php?id=${userId}`;
}

// ฟังก์ชันลบผู้ใช้
function deleteUser(userId) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?')) {
        fetch(`delete_user.php?id=${userId}`, { method: 'DELETE' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('ลบผู้ใช้สำเร็จ');
                    searchUser();  // อัพเดทข้อมูลหลังจากลบ
                } else {
                    alert('เกิดข้อผิดพลาด');
                }
            })
            .catch(error => console.error('Error:', error));
    }
}
