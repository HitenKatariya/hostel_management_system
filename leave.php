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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the leave details from the form and escape special characters
    $userId = 1; // Replace with actual user ID from session if available
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $reason = $conn->real_escape_string($_POST['reason']);

    // Prepare the SQL query to insert the data (without status)
    $sql = "INSERT INTO `leave` (user_id, start_date, end_date, reason) VALUES ('$userId', '$startDate', '$endDate', '$reason')";

    // Execute the query and check for success or failure
    if ($conn->query($sql) === TRUE) {
        $successMessage = "<p class='success-message'>Leave request submitted successfully!</p>";
    } else {
        $successMessage = "<p class='error-message'>Error: " . $conn->error . "</p>";
    }
}

$conn->close();

// Get the current date in YYYY-MM-DD format for the min date
$currentDate = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Leave</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.2px;
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
        }

        textarea:focus {
            border-color: #ff6f91;
            box-shadow: 0 0 8px rgba(255, 111, 145, 0.5);
        }

        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            outline: none;
        }

        .submit-btn {
            background-color: #33ddff;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #0087ff;
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
    <script>
        // Function to set minimum end date based on start date
        function validateDates() {
            var startDate = document.getElementById("start_date").value;
            var endDate = document.getElementById("end_date");
            
            if (startDate) {
                endDate.setAttribute("min", startDate); // Set end date min to start date
                if (endDate.value < startDate) {
                    endDate.value = startDate; // Reset end date if it's before start date
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Request Leave</h2>

        <!-- Display success or error message -->
        <?php if ($successMessage) echo $successMessage; ?>

        <!-- Leave Request Submission Form -->
        <form action="leave.php" method="post">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required min="<?php echo $currentDate; ?>" onchange="validateDates()"><br>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required min="<?php echo $currentDate; ?>" onchange="validateDates()"><br>

            <label for="reason">Reason:</label>
            <textarea name="reason" rows="5" placeholder="Enter the reason for the leave" required></textarea><br>

            <button type="submit" class="submit-btn">Submit Leave Request</button>
        </form>
    </div>
</body>
</html>
