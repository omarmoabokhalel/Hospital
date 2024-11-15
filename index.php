<?php
session_start(); // Start the session
include 'config.php';

if (!isset($_SESSION['email'])) {
    header('Location: log-in.html'); // Redirect to login if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Tech</title>
    <!-- Main CSS file -->
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo.png" alt="HealthTech Logo">
            <h2>HealthTech</h2>
        </div>

        <nav>
            <ul>
                <li class="home"><a href="index.php">Home</a></li>
                <li><a href="appointment.html">Appointment</a></li>
                <li><a href="result.html">Results</a></li>
                <li><a href="education.php">Education</a></li>
                <li><a href="detail.html">Details</a></li>
                <li><a href="about.html">About</a></li>
            </ul>
        </nav>
        <?php $email = $_SESSION['email'];
            $sqlUser = "SELECT * FROM users WHERE email = ?";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->bind_param("s", $email);
            $stmtUser->execute();
            $resultUser = $stmtUser->get_result();
            $user = $resultUser->fetch_assoc();?>
            <?php if (isset($user['email'])): ?>
                <a href="#" class="get-started" onclick="openOverlay()">Show Profile</a>
        <?php else: ?>
            <a href="log-in.html" class="get-started">Log In</a>
        <?php endif; ?>
    </header>


    <!-- Overlay for Profile -->
    <div class="overlay" id="profileOverlay">
        <div class="overlay-content">
            <button class="close-btn" onclick="closeOverlay()">Close</button>
            <h2>Profile Details</h2>
            <p><strong>Name:</strong> <?php echo $user['first_name'],' ',$user['last_name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Phone Number:</strong> <?php echo $user['phone_number']; ?></p>
            <!-- Add more details as needed -->
        </div>
    </div>

    <script>
        // JavaScript to open and close the overlay
        function openOverlay() {
            document.getElementById('profileOverlay').style.display = 'flex';
        }
        function closeOverlay() {
            document.getElementById('profileOverlay').style.display = 'none';
        }
    </script>

    <main id="home">
        <section class="hero">
            <div class="hero-content">
                <h2>Your health care. <br> starts here...</h2>
                <p>Integrated medical services that allow you to book appointments, follow up on tests, get medical consultations from anywhere, anytime and more.</p>
            </div>

            <div class="hero-image">
                <a href="#home"><img src="images/doctor-home.png" alt="Doctor Image"></a>
            </div>
        </section>
    </main>
</body>
</html>
