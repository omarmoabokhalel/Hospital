<?php
session_start();
include 'config.php'; // Include your database connection

function generateUserId($conn) {
    $result = $conn->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = (int)$row['id'];  
        $newId = str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);  
    } else {
        $newId = '00001';  
    }
    return $newId;
}

// Process the registration form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $national_id = $_POST['national_id'];

    // Generate a new user ID
    $newId = generateUserId($conn); // تأكد من أن لديك دالة generateUserId

    // Insert user into the database
    $sql = "INSERT INTO users (id, email, password, first_name, last_name, phone_number, national_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $newId, $email, $password, $first_name, $last_name, $phone_number, $national_id);
    
    if ($stmt->execute()) {
        // Store the first name in the session
        $_SESSION['first_name'] = $first_name;
        // Redirect to home page
        header('Location: index.php'); // تأكد من أن هذه هي الصفحة الرئيسية
        exit();
    } else {
        echo "حدث خطأ أثناء التسجيل. حاول مرة أخرى.";
    }
}
?>
