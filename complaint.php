<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hostel";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = ''; // Variable to store the success or error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the complaint text and escape special characters
    $complaintText = $conn->real_escape_string($_POST['complaint_text']);
    $userId = 1; // Replace with actual user ID if session or login system is in place

    // Check if complaint text is not empty
    if (!empty($complaintText)) {
        // Insert the complaint into the database
        $sql = "INSERT INTO complaint (user_id, complaint_text) VALUES ('$userId', '$complaintText')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "<p class='success-message'>Complaint submitted successfully!</p>";
        } else {
            $successMessage = "<p class='error-message'>Error: " . $conn->error . "</p>";
        }
    } else {
        $successMessage = "<p class='error-message'>Please write a complaint before submitting.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Complaint</title>
    <style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    overflow: hidden;
    position: relative;
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
    opacity: 0.7; /* Set the opacity of the background image */
    z-index: -1; /* Place the image behind the content */
}

.container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    max-width: 500px;
    width: 100%;
    text-align: center;
    animation: fadeIn 1s ease-in-out;
}

h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1.2px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

textarea {
    width: 100%;
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 10px;
    font-size: 16px;
    margin-bottom: 15px;
    outline: none;
    resize: vertical;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}

textarea:focus {
    border-color: #ff6f91;
    box-shadow: 0 0 8px rgba(255, 111, 145, 0.5);
}

.submit-btn {
    background-color: #33ddff;
    color: #fff;
    padding: 12px 25px;
    border: none;
    border-radius: 25px;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.4s ease, box-shadow 0.4s ease;
    position: relative;
    overflow: hidden;
    outline: none;
}

.submit-btn:hover {
    background-color: #0087ff;
    box-shadow: 0 8px 15px rgba(255, 59, 107, 0.3);
}

.submit-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 300%;
    height: 300%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0) 70%);
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.5s ease;
    border-radius: 50%;
}

.submit-btn:hover::before {
    transform: translate(-50%, -50%) scale(1);
}

.success-message, .error-message {
    color: #4CAF50;
    margin-top: 20px;
    font-weight: bold;
    animation: slideIn 0.5s ease forwards;
    text-align: center;
}

.error-message {
    color: #f44336;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Submit a Complaint</h2>
        
        <!-- Display the success or error message above the submit button -->
        <?php if ($successMessage) echo $successMessage; ?>
        
        <form action="complaint.php" method="post">
            <textarea name="complaint_text" rows="5" placeholder="Write your complaint here..."></textarea><br>
            <button type="submit" class="submit-btn">Submit Complaint</button>
        </form>
    </div>
</body>
</html>
