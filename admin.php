<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iub_parking_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$admin_id = $_SESSION['user_id'];

// Handle delete action
if (isset($_POST['delete'])) {
    $id_to_delete = $_POST['delete'];
    
    // Check if the user to be deleted is an admin
    $check_admin_sql = "SELECT user_type FROM users WHERE id = ?";
    $check_stmt = $conn->prepare($check_admin_sql);
    $check_stmt->bind_param("s", $id_to_delete);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $user_data = $check_result->fetch_assoc();
    $check_stmt->close();

    if ($user_data['user_type'] !== 'admin') {
        $delete_sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("s", $id_to_delete);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all user information and their latest parking record
$sql = "SELECT u.*, 
               pr.entry_time, 
               pr.exit_time
        FROM users u
        LEFT JOIN (
            SELECT user_id, MAX(entry_time) as latest_entry
            FROM parking_records
            GROUP BY user_id
        ) latest_pr ON u.id = latest_pr.user_id
        LEFT JOIN parking_records pr ON latest_pr.user_id = pr.user_id AND latest_pr.latest_entry = pr.entry_time
        ORDER BY u.id";

$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUB Parking System - Admin Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color:  #C0C0C0;
        }
        h1 { 
            color: #c45656;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
            background-color: white;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background-color: #c45656; 
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #c45656;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }
        .back-link:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        .delete-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <div class="container">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>User Type</th>
                <th>Vehicle Type</th>
                <th>Chassis Number</th>
                <th>Digital Plate Number</th>
                <th>Phone Number</th>
                <th>Latest Entry Time</th>
                <th>Latest Exit Time</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["user_type"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["vehicle_type"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["chassis_number"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["digital_plate_number"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["phone_number"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["entry_time"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["exit_time"]) . "</td>";
                    echo "<td>";
                    if ($row["user_type"] !== 'admin') {
                        echo "<form method='post' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>";
                        echo "<button type='submit' name='delete' value='" . $row["id"] . "' class='delete-btn'>Delete</button>";
                        echo "</form>";
                    } else {
                        echo "<button class='delete-btn' disabled>Delete</button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No users found</td></tr>";
            }
            ?>
        </table>
    </div>

    <a href="index.php" class="back-link">Back to Main Page</a>
</body>
</html>