<?php
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION['email'])) {
    // If the doctor is not logged in, redirect to the login page
    header('Location: log-in.php');
    exit;
}

include 'config.php';

// Get the doctor's email from the session
$email = $_SESSION['email'];

// Step 1: Select the doctor's details from the 'doctors' table using the email
$sql_doctor = "SELECT * FROM doctors WHERE email = ?";
$stmt_doctor = $conn->prepare($sql_doctor);
$stmt_doctor->bind_param("s", $email); // Use 's' for string type
$stmt_doctor->execute();
$result_doctor = $stmt_doctor->get_result();

// Step 2: Check if the doctor details are found
if ($result_doctor->num_rows === 1) {
    $doctor = $result_doctor->fetch_assoc();
} else {
    // If no doctor details are found, display an error message
    echo "Doctor details not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="doctor.css">
</head>
<body>
    <h2>Welcome, Dr. <?php echo htmlspecialchars($doctor['name']); ?></h2>
    <h3>Doctor Details</h3>
    <p><b>Name:</b> <?php echo htmlspecialchars($doctor['name']); ?></p>
    <p><b>Email:</b> <?php echo htmlspecialchars($doctor['email']); ?></p>
    <p><b>Phone:</b> <?php echo htmlspecialchars($doctor['phone']); ?></p>
    <p><b>Hospital:</b> <?php echo htmlspecialchars($doctor['hospital']); ?></p>
    <p><b>Specialty:</b> <?php echo htmlspecialchars($doctor['specialty']); ?></p>
    
    <h2>Appointments</h2>
    <div class="appointments">
        <?php
        // Step 3: Retrieve appointments for the logged-in doctor
        $sql_appointments = "SELECT * FROM appointments WHERE doctor = ?";
        $stmt_appointments = $conn->prepare($sql_appointments);
        $stmt_appointments->bind_param("s", $doctor['name']); // Assuming 'doctor' field matches the doctor's name
        $stmt_appointments->execute();
        $result_appointments = $stmt_appointments->get_result();

        // Step 4: Display appointments
        if ($result_appointments->num_rows > 0) {
            while ($row = $result_appointments->fetch_assoc()) { ?>
                <div class="appointment">
                    <p><b>Patient Name:</b> <?php echo htmlspecialchars($row['first_name']); ?></p>
                    <p><b>Email:</b> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><b>Phone:</b> <?php echo htmlspecialchars($row['phone']); ?></p>
                    <p><b>National ID:</b> <?php echo htmlspecialchars($row['national_id']); ?></p>
                    <p><b>Age:</b> <?php echo htmlspecialchars($row['age']); ?></p>
                    <p><b>Gender:</b> <?php echo htmlspecialchars($row['gender']); ?></p>
                    <p><b>Hospital:</b> <?php echo htmlspecialchars($row['hospital']); ?></p>
                    <p><b>Specialty:</b> <?php echo htmlspecialchars($row['specialty']); ?></p>
                    <p><b>Doctor:</b> <?php echo htmlspecialchars($row['doctor']); ?></p>
                    <p><b>Appointment Date:</b> <?php echo htmlspecialchars($row['appointment_date']); ?></p>
                </div>
                <hr> <!-- Optional: Add a separator between appointments -->
            <?php }
        } else {
            echo "<p>No appointments found.</p>";
        }
        ?>
    </div>
</body>
</html>