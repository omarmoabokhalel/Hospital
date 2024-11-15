<?php


session_start();
include 'config.php'; // Include your database connection


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check the 'doctors' table
    $sqlDoctor = "SELECT * FROM doctors WHERE email = ?";
    $stmtDoctor = $conn->prepare($sqlDoctor);
    $stmtDoctor->bind_param("s", $email);
    $stmtDoctor->execute();
    $resultDoctor = $stmtDoctor->get_result();

    if ($resultDoctor->num_rows == 1) {
        $doctor = $resultDoctor->fetch_assoc();
        
        // Doctor found, log them in
        $_SESSION['email'] = $doctor['email'];
        $_SESSION['doctor_id'] = $doctor['id']; // Store doctor ID in session
    
        // Print the session values for debugging
        echo "Logged in as Doctor: " . $_SESSION['email']; // For debugging purposes
        header('Location: doctor-page.php'); // Redirect to doctor page
        exit;
    }
    

    // If not found in 'doctors', check the 'users' table
    $sqlUser = "SELECT * FROM users WHERE email = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("s", $email);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();

    if ($resultUser->num_rows == 1) {
        $user = $resultUser->fetch_assoc();
        
        // User found, log them in
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['id']; // Store user ID in session

        header('Location: index.php'); // Redirect to main page
        exit;
    }

    // If neither login is successful
    echo "البريد الإلكتروني غير موجود.";
}


?>
