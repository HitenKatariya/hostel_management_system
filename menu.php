<?php
session_start();

// Set up the database connection
$conn = new mysqli("localhost", "root", "", "hostel");

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the current user's username from the session
$username = $_SESSION['username'];

// Fetch the latest menu
$sql = "SELECT id, option1, option2, option3, votes_option1, votes_option2, votes_option3, created_at FROM menu ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $menu = $result->fetch_assoc();

    // Reset the voting status if a new menu is created
    $latest_menu_id = $menu['id'];
    if (!isset($_SESSION['last_menu_id']) || $_SESSION['last_menu_id'] != $latest_menu_id) {
        // Reset all users' voting status
        $conn->query("UPDATE users SET has_voted = 0");
        $_SESSION['last_menu_id'] = $latest_menu_id;
    }
} else {
    echo "<p>No menu has been set for today.</p>";
    exit();
}

// Fetch the user's voting status from the `users` table
$sql = "SELECT has_voted FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Check if the user has already voted for the latest menu
$disabled = ($user && $user['has_voted'] == 1); // Disable voting if the user has voted

// Handle the voting submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote_option']) && !$disabled) {
    $vote = intval($_POST['vote_option']);
    if ($vote >= 1 && $vote <= 3) {
        // Update the vote count for the selected option
        $column = "votes_option" . $vote;
        $sql = "UPDATE menu SET $column = $column + 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $menu['id']);
        if ($stmt->execute()) {
            // Mark the user as having voted
            $update_vote_status = "UPDATE users SET has_voted = 1 WHERE username = ?";
            $stmt = $conn->prepare($update_vote_status);
            $stmt->bind_param("s", $username);
            $stmt->execute();

            $_SESSION['has_voted'] = true; // Store that the user has voted
            echo "<script>alert('Thank you for voting!'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Error while voting. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Invalid vote option.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Menu</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            text-align: center;
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('DIVINEHOSTEL.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.7;
            z-index: -1;
        }

        h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-top: 30px;
            width: 100%;
            max-width: 400px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 20px 0;
            font-size: 18px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #2980b9;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1e6a92;
        }

        button:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }

        .vote-count {
            color: #7f8c8d;
            font-size: 12px;
        }

        p {
            color: #e74c3c;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Today's Menu</h2>
    <?php if ($menu): ?>
        <form method="POST" action="">
            <ul>
                <li>
                    <?php echo $menu['option1']; ?> - 
                    <button type="submit" name="vote_option" value="1" <?php if ($disabled) echo 'disabled'; ?>>Vote</button> 
                    <span class="vote-count">(Votes: <?php echo $menu['votes_option1']; ?>)</span>
                </li>
                <li>
                    <?php echo $menu['option2']; ?> - 
                    <button type="submit" name="vote_option" value="2" <?php if ($disabled) echo 'disabled'; ?>>Vote</button> 
                    <span class="vote-count">(Votes: <?php echo $menu['votes_option2']; ?>)</span>
                </li>
                <li>
                    <?php echo $menu['option3']; ?> - 
                    <button type="submit" name="vote_option" value="3" <?php if ($disabled) echo 'disabled'; ?>>Vote</button> 
                    <span class="vote-count">(Votes: <?php echo $menu['votes_option3']; ?>)</span>
                </li>
            </ul>
        </form>
    <?php else: ?>
        <p>No menu has been set for today.</p>
    <?php endif; ?>
</body>
</html>

<?php $conn->close(); ?>
