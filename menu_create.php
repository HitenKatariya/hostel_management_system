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

// Menu Creation Process
if (isset($_POST['create_menu']) && isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $menu_option_1 = $_POST['menu_option_1'];
    $menu_option_2 = $_POST['menu_option_2'];
    $menu_option_3 = $_POST['menu_option_3'];

    // Insert the new menu into the menu table
    $sql = "INSERT INTO menu (option1, option2, option3, votes_option1, votes_option2, votes_option3) 
            VALUES ('$menu_option_1', '$menu_option_2', '$menu_option_3', 0, 0, 0)";

    if ($conn->query($sql) === TRUE) {
        $success = "New menu has been created successfully!";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Menu - Admin</title>
    <style>
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
        }
        .sidebar {
            width: 200px;
            background: #333;
            color: white;
            padding: 20px;
            box-sizing: border-box;
        }
        .sidebar h3 {
            color: #fff;
            font-size: 18px;
            margin-bottom: 10px;
            text-align: center;
        }
        .sidebar a {
            color: #ddd;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            padding: 8px;
            border-radius: 4px;
            transition: background 0.3s ease;
        }
        .sidebar a:hover {
            background: #555;
        }
        .main-content {
            flex-grow: 1;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border: 1px solid #0071c2;
        }
        button {
            padding: 12px;
            background: #0071c2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #005fa3;
        }
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
</head>
<body>
<div class="container">
    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
        <div class="sidebar">
            <h3>Dashboard</h3>
            <a href="?page=fees">Fees</a>
            <a href="?page=menu">Menu</a>
            <a href="?page=complaint">Complaint</a>
            <a href="?page=leave">Leave</a>
            <a href="?page=inquiry">Inquiry</a>
            <a href="?logout=true" class="logout-link">Logout</a>
        </div>
        <div class="main-content">
            <h2>Create Menu</h2>
            <form method="POST">
                <input type="text" name="menu_option_1" placeholder="Menu Option 1" required>
                <input type="text" name="menu_option_2" placeholder="Menu Option 2" required>
                <input type="text" name="menu_option_3" placeholder="Menu Option 3" required>
                <button type="submit" name="create_menu">Create Menu</button>
            </form>
            <?php if (isset($success)) { echo '<p class="success">'.$success.'</p>'; } ?>
            <?php if (isset($error)) { echo '<p class="error">'.$error.'</p>'; } ?>
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
