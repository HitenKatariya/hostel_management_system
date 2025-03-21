<?php
session_start();

// Database connection (update with your DB credentials)
$servername = "localhost";
$username = "root"; // Change to your DB username
$password = ""; // Change to your DB password
$dbname = "hostel"; // Change to your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin Login Process
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $login_error = "Invalid username or password.";
    }
}

// Admin Logout Process
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Admin</title>
    <style>
        <style>
    /* General Styles */
    body, html {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    
    .container {
        display: flex;
        max-width: 1200px;
        margin: 50px auto;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    
    /* Sidebar Styles */
    .sidebar {
        width: 220px;
        background: #333;
        color: white;
        padding: 20px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }
    
    .sidebar h3 {
        color: #fff;
        font-size: 20px;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .sidebar a {
        color: #ddd;
        text-decoration: none;
        display: block;
        margin: 10px 0;
        padding: 12px;
        border-radius: 4px;
        transition: background 0.3s ease;
        text-align: center;
    }
    
    .sidebar a:hover {
        background: #555;
    }
    
    .logout-link {
        margin-top: auto;
        background: #ff4d4d;
        color: white;
        text-align: center;
        padding: 10px;
        border-radius: 4px;
        transition: background 0.3s ease;
    }
    
    .logout-link:hover {
        background: #ff3333;
    }
    
    /* Main Content Styles */
    .main-content {
        flex-grow: 1;
        padding: 30px;
        background: #f9f9f9;
        border-left: 1px solid #ddd;
    }
    
    h2 {
        color: #333;
        margin-bottom: 20px;
    }
    
    p {
        color: #666;
    }
    
    /* Login Form Styles */
    form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    
    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        transition: border 0.3s ease;
    }
    
    input[type="text"]:focus, input[type="password"]:focus {
        border: 1px solid #0071c2;
    }
    
    button {
        width: 100%;
        padding: 12px;
        background: #0071c2;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s ease;
        margin-top: 10px;
    }
    
    button:hover {
        background: #005fa3;
    }
    
    /* Success and Error Messages */
    .error, .success {
        padding: 10px;
        margin-top: 20px;
        border-radius: 4px;
    }
    
    .error {
        background: #f8d7da;
        color: #721c24;
    }
    
    .success {
        background: #d4edda;
        color: #155724;
    }
</style>

    </style>
</head>
<body>
<div class="container">
    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
        <div class="sidebar">
            <h3>Dashboard</h3>
            <a href="fees_fetched.php">Fees</a>
            <a href="menu_create.php">Menu</a>
            <a href="complaint_fetched.php">Complaint</a>
            <a href="leave_fetched.php">Leave</a>
            <a href="inquiry_fetched.php">Inquiry</a>
            <a href="?logout=true" class="logout-link">Logout</a>
        </div>
        <div class="main-content">
            <h2>Welcome to the Admin Dashboard</h2>
            <p>Select an option from the sidebar to view details.</p>
        </div>
    <?php else: ?>
        <div class="main-content">
            <h2>Admin Login</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <?php if (isset($login_error)) { echo '<p class="error">'.$login_error.'</p>'; } ?>
            </form>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

<?php
$conn->close();
?>
