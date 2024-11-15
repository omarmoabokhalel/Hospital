<?php
session_start();
include 'config.php'; // لربط قاعدة البيانات

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['first_name'], $_POST['email'], $_POST['phone'], 
              $_POST['national_id'], $_POST['age'], $_POST['gender'], 
              $_POST['hospital'], $_POST['specialty'], $_POST['doctor'], 
              $_POST['appointment_date'])
    ) {
        // تخزين القيم المرسلة في متغيرات
        $first_name = $_POST['first_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $national_id = $_POST['national_id'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $hospital = $_POST['hospital'];
        $specialty = $_POST['specialty']; // التخصص
        $doctor = $_POST['doctor'];
        $appointment_date = $_POST['appointment_date'];

        // التأكد من ملء جميع الحقول
        if (!empty($first_name) && !empty($email) && !empty($phone) && 
            !empty($national_id) && !empty($age) && !empty($gender) && 
            !empty($hospital) && !empty($specialty) && !empty($doctor) && 
            !empty($appointment_date)) {
            
            // استعلام إدخال البيانات في قاعدة البيانات
            $sql = "INSERT INTO appointments (first_name, email, phone, national_id, age, gender, hospital, specialty, doctor, appointment_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            // ربط المتغيرات بالاستعلام
            $stmt->bind_param("ssssisssss", $first_name, $email, $phone, $national_id, $age, $gender, $hospital, $specialty, $doctor, $appointment_date);

            // تنفيذ الاستعلام
            if ($stmt->execute()) {
                $_SESSION['message'] = 'تم الحجز بنجاح!';
            } else {
                $_SESSION['message'] = "حدث خطأ أثناء الحجز. حاول مرة أخرى.";
            }
            $stmt->close(); // اغلاق الstmt بعد الاستخدام
        } else {
            $_SESSION['message'] = "يجب ملء جميع الحقول.";
        }
    } else {
        $_SESSION['message'] = "لم يتم استلام البيانات بشكل صحيح.";
    }

    // إعادة التوجيه إلى صفحة الحجز
    header("Location: appointment.html");
    exit();
}
?>
