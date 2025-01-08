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

function registerUser($conn, $id, $name, $user_type, $password, $vehicle_type, $chassis_number, $digital_plate_number, $phone_number) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (id, name, user_type, password, vehicle_type, chassis_number, digital_plate_number, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $id, $name, $user_type, $hashed_password, $vehicle_type, $chassis_number, $digital_plate_number, $phone_number);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function validateUser($conn, $id, $password) {
    $stmt = $conn->prepare("SELECT id, user_type, password FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            return $row;
        }
    }
    return false;
}

function checkParkingStatus($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM parking_records WHERE user_id = ? ORDER BY entry_time DESC LIMIT 1");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($row = $result->fetch_assoc()) {
        return $row['exit_time'] === null;
    }
    return false;
}

function getNotifications($conn, $user_id) {
    $notifications = array();
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = FALSE ORDER BY created_at DESC");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($notification = $result->fetch_assoc()) {
        $notifications[] = $notification;
    }
    $stmt->close();
    return $notifications;
}

function markNotificationsAsRead($conn, $user_id) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->close();
}

$notifications = array();
$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $user = validateUser($conn, $_POST['id'], $_POST['password']);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['user_type'];
            $notifications = getNotifications($conn, $_SESSION['user_id']);
        } else {
            $error = "Invalid credentials";
        }
    } elseif (isset($_POST['logout'])) {
        session_destroy();
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['register']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
        if (registerUser($conn, $_POST['id'], $_POST['name'], $_POST['user_type'], $_POST['password'], $_POST['vehicle_type'], $_POST['chassis_number'], $_POST['digital_plate_number'], $_POST['phone_number'])) {
            $message = "User registered successfully";
        } else {
            $error = "Error registering user";
        }
    } elseif (isset($_SESSION['user_id'])) {
        $action = $_POST['action']; // 'entry' or 'exit'
        $user_id = $_SESSION['user_id'];

        if ($action == 'entry') {
            if (!checkParkingStatus($conn, $user_id)) {
                $stmt = $conn->prepare("INSERT INTO parking_records (user_id, entry_time) VALUES (?, NOW())");
                $stmt->bind_param("s", $user_id);
                
                if ($stmt->execute()) {
                    $message = "Entry recorded successfully";
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "You are already inside the parking. Please exit first.";
            }
        } else {
            if (checkParkingStatus($conn, $user_id)) {
                $stmt = $conn->prepare("UPDATE parking_records SET exit_time = NOW() WHERE user_id = ? AND exit_time IS NULL");
                $stmt->bind_param("s", $user_id);
                
                if ($stmt->execute()) {
                    $message = "Exit recorded successfully";
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "You are not currently inside the parking.";
            }
        }
    }
}

if (isset($_SESSION['user_id'])) {
    $notifications = getNotifications($conn, $_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUB Parking System</title>
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
            min-height: 80vh;
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
        .admin-panel {
            display: flex;
            justify-content: space-between;
        }
        .parking-controls, .registration-form {
            width: 60%;
        }
        form { margin-bottom: 20px; }
        input, button, select {
            margin: 10px 0;
            padding: 10px;
            display: block;
            width: calc(100% - 22px);
            border: 1px solid #ddd;
            border-radius: 5px;
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
        .notifications {
            margin-top: 20px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
        }
        .notification {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .discover-button {
            display: inline-block;
            margin: 10px 0;
            padding: 12px 25px;
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            transition: all 0.3s;
            text-align: center;
            width: calc(100% - 50px);
        }
        .discover-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>IUB Parking System</h1>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="about.php" class="discover-button">Discover Our Smart Parking System</a>
            <form method="post">
                <h2>Login</h2>
                <input type="text" name="id" placeholder="7-digit ID" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        <?php else: ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_type']); ?></p>
            
            <?php if (!empty($notifications)): ?>
                <div class="notifications">
                    <h2>Notifications</h2>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification">
                            <p><?php echo htmlspecialchars($notification['message']); ?></p>
                            <small>Received on: <?php echo $notification['created_at']; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($_SESSION['user_type'] == 'admin'): ?>
                <div class="admin-panel">
                    <div class="parking-controls">
                        <h2>Parking Controls</h2>
                        <form method="post">
                            <button type="submit" name="action" value="entry">Enter Parking</button>
                            <button type="submit" name="action" value="exit">Exit Parking</button>
                        </form>
                        <form method="post">
                            <button type="submit" name="logout">Logout</button>
                        </form>
                        <a href="admin.php">Admin Dashboard</a>
                    </div>
                    <div class="registration-form">
                        <h2>Register New User</h2>
                        <form method="post">
                            <input type="text" name="id" placeholder="7-digit ID" required>
                            <input type="text" name="name" placeholder="Full Name" required>
                            <select name="user_type" required>
                                <option value="student">Student</option>
                                <option value="faculty">Faculty</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                                <option value="security_manager">Security Manager</option>
                            </select>
                            <input type="password" name="password" placeholder="Password" required>
                            <input type="text" name="vehicle_type" placeholder="Vehicle Type" required>
                            <input type="text" name="chassis_number" placeholder="Chassis Number" required>
                            <input type="text" name="digital_plate_number" placeholder="Digital Plate Number" required>
                            <input type="tel" name="phone_number" placeholder="Phone Number" required>
                            <button type="submit" name="register">Register User</button>
                        </form>
                    </div>
                </div>
            <?php elseif ($_SESSION['user_type'] == 'security_manager'): ?>
                <a href="security_manager.php" style="display: inline-block; margin: 10px 0; padding: 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Security Manager Dashboard</a>
                <form method="post">
                    <button type="submit" name="action" value="entry">Enter Parking</button>
                    <button type="submit" name="action" value="exit">Exit Parking</button>
                </form>
                <form method="post">
                    <a href="complaint.php" style="display: inline-block; margin: 10px 0; padding: 10px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Report Stolen Item</a>
                    <button type="submit" name="logout">Logout</button>
                </form>
            <?php else: ?>
                <form method="post">
                    <button type="submit" name="action" value="entry">Enter Parking</button>
                    <button type="submit" name="action" value="exit">Exit Parking</button>
                </form>
                <form method="post">
                    <a href="complaint.php" style="display: inline-block; margin: 10px 0; padding: 10px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Report Stolen Item</a>
                    <button type="submit" name="logout">Logout</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        if (!empty($message)) {
            echo "<p class='message'>$message</p>";
        }
        if (!empty($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
    </div>
</body>
</html>
<?php
if (isset($_SESSION['user_id']) && !empty($notifications)) {
    markNotificationsAsRead($conn, $_SESSION['user_id']);
}

$conn->close();
?>