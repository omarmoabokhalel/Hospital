<?php
session_start();
include 'config.php'; // ربط قاعدة البيانات

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // افتراض أن لديك حقول: email و password و phone
    if (isset($_POST['email'], $_POST['password'], $_POST['phone'])) {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور
        $phone = $_POST['phone'];

        // استعلام الإدخال في قاعدة البيانات
        $sql = "INSERT INTO users (email, password, phone) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $password, $phone);

        if ($stmt->execute()) {
            // تسجيل الدخول تلقائياً بعد التسجيل
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;

            // إعادة التوجيه إلى الصفحة الرئيسية
            header("Location: index.php");
            exit();
        } else {
            echo "حدث خطأ أثناء التسجيل. حاول مرة أخرى.";
        }
    }
}
?>
