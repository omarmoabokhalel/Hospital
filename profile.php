<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    header('Location: log-in.html'); // Redirect to login if not logged in
    exit;
}

// Fetch user details based on session email
$email = $_SESSION['email'];
$sqlUser = "SELECT * FROM users WHERE email = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $email);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$user = $resultUser->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($user['first_name']); ?></h2>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Phone: <?php echo htmlspecialchars($user['phone_number']); ?></p>

    <a href="log-out.php">Log Out</a>
</body>
</html>
