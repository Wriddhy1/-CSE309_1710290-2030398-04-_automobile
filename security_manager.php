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

// Check if the user is logged in and is a security manager
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'security_manager') {
    header("Location: index.php");
    exit();
}

$message = $error = "";

// Handle status updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['new_status'];
    $user_id = $_POST['user_id'];
    
    // Start transaction
    $conn->begin_transaction();

    try {
        // Update complaint status
        $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $complaint_id);
        $stmt->execute();

        // Create notification
        $notification_message = "Your complaint (ID: $complaint_id) status has been updated to: $new_status";
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $user_id, $notification_message);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        $message = "Complaint status updated successfully and user notified.";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $error = "Error updating complaint status: " . $e->getMessage();
    }

    $stmt->close();
}

// Fetch all complaints
$sql = "SELECT c.*, u.name AS user_name FROM complaints c JOIN users u ON c.user_id = u.id ORDER BY c.submission_time DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Manager Dashboard - IUB Parking System</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            overflow: auto;
            padding: 0 20px;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .status-form {
            display: flex;
            justify-content: space-between;
        }
        .status-select {
            padding: 5px;
            margin-right: 5px;
        }
        .status-submit {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .status-submit:hover {
            background-color: #45a049;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
        }
        .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            margin-top: 20px;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        .pagination a:hover:not(.active) {background-color: #ddd;}
        /* ... (styles remain unchanged) ... */
    </style>
</head>
<body>
    <div class="container">
        <h1>Security Manager Dashboard</h1>
        <?php
        if ($message) echo "<p class='message'>$message</p>";
        if ($error) echo "<p class='error'>$error</p>";
        ?>
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Item Description</th>
                <th>Last Seen Location</th>
                <th>Additional Details</th>
                <th>Submission Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["id"]."</td>";
                    echo "<td>".$row["user_name"]." (".$row["user_id"].")</td>";
                    echo "<td>".$row["item_description"]."</td>";
                    echo "<td>".$row["last_seen_location"]."</td>";
                    echo "<td>".$row["additional_details"]."</td>";
                    echo "<td>".$row["submission_time"]."</td>";
                    echo "<td>".$row["status"]."</td>";
                    echo "<td>
                            <form method='post' class='status-form'>
                                <input type='hidden' name='complaint_id' value='".$row["id"]."'>
                                <input type='hidden' name='user_id' value='".$row["user_id"]."'>
                                <select name='new_status' class='status-select'>
                                    <option value='pending' ".($row["status"] == 'pending' ? 'selected' : '').">Pending</option>
                                    <option value='in_progress' ".($row["status"] == 'in_progress' ? 'selected' : '').">In Progress</option>
                                    <option value='resolved' ".($row["status"] == 'resolved' ? 'selected' : '').">Resolved</option>
                                </select>
                                <input type='submit' name='update_status' value='Update' class='status-submit'>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No complaints found.</td></tr>";
            }
            ?>
        </table>
        <a href="index.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>