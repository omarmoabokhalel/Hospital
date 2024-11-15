<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthtech"; // تأكد من اسم قاعدة البيانات

$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchResult = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = trim($_POST['search']);
    $query = strtolower($query);

    // البحث في قاعدة البيانات عن المرض المدخل
    $sql = "SELECT * FROM diseases WHERE LOWER(disease_name_ar) LIKE ? OR LOWER(disease_name_en) LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchQuery = "%" . $query . "%";
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResult .= "<div class='result'>
                <h3>{$row['disease_name_ar']} ({$row['disease_name_en']})</h3>
                <p><strong>الوصف:</strong> {$row['description']}</p>
                <p><strong>الوقاية:</strong> {$row['prevention']}</p>
                <p><strong>العلاج:</strong> {$row['treatment']}</p>
            </div>";
        }
    } else {
        $searchResult = "<p>لم يتم العثور على نتائج لهذا البحث.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Health Tech - Education</title>
    <link rel="stylesheet" href="education.css">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f4f4f4;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #ffffff20;
            box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo h2 {
            margin-left: 10px;
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .get-started {
            padding: 10px 20px;
            background-color: #24a1d7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .search {
            text-align: center;
            margin-left:700px;
        }

        .search h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .search-ic input {
            width: 350px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-ic input:focus {
            border-color: #24a1d7;
            outline: none;
        }

        .search button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #24a1d7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .result {
            background-color: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo.png" alt="HealthTech Logo" height="50">
            <h2>HealthTech</h2>
        </div>

        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="appointment.html">Appointment</a></li>
                <li><a href="result.html">Results</a></li>
                <li class="home"><a href="education.php">Education</a></li>
                <li><a href="detail.html">Details</a></li>
                <li><a href="about.html">About</a></li>
            </ul>
        </nav>
        <a href="log-in.html" class="get-started">Log in</a>
    </header>

    <section>
        <div class="container">
            <div class="search">
                <h2>ابحث عن معلومات صحية</h2>
                <form method="POST">
                    <div class="search-ic">
                        <input type="text" name="search" placeholder="اكتب اسم المرض" required>
                    </div>
                    <button type="submit">ابحث</button>
                </form>
            </div>
        </div>
    </section>

    <div class="results">
        <?= $searchResult ?>
    </div>
</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
