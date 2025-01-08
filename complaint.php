<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iub_parking_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_complaint'])) {
    $user_id = $_SESSION['user_id'];
    $item_description = $_POST['item_description'];
    $last_seen_location = $_POST['last_seen_location'];
    $additional_details = $_POST['additional_details'];

    $stmt = $conn->prepare("INSERT INTO complaints (user_id, item_description, last_seen_location, additional_details, submission_time) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $user_id, $item_description, $last_seen_location, $additional_details);

    if ($stmt->execute()) {
        $message = "Your complaint has been submitted successfully. The security manager will review it shortly.";
    } else {
        $error = "Error submitting complaint. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - IUB Parking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('parking_background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 600px;
            width: 90%;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        form { margin-bottom: 20px; }
        input, textarea, button {
            margin: 10px 0;
            padding: 10px;
            display: block;
            width: calc(100% - 22px);
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        textarea {
            height: 100px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        h1, h2 {
            color: #333;
        }
        .message {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submit Complaint</h1>
        <form method="post">
            <input type="text" name="item_description" placeholder="Description of stolen item" required>
            <input type="text" name="last_seen_location" placeholder="Last seen location" required>
            <textarea name="additional_details" placeholder="Additional details"></textarea>
            <button type="submit" name="submit_complaint">Submit Complaint</button>
        </form>
        <?php
        if ($message) echo "<p class='message'>$message</p>";
        if ($error) echo "<p class='error'>$error</p>";
        ?>
        <a href="index.php" class="back-link">Back to Main Page</a>
    </div>
</body>
</html>