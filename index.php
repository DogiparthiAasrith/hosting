<?php
/**
 * SIMPLE PHP GUESTBOOK APPLICATION
 * 
 * This is the main page that:
 * 1. Shows a form where users can enter their name and message
 * 2. Processes the form submission and saves data to MySQL database
 * 3. Displays all saved messages below the form
 */

// Include database configuration file
// This file contains our MySQL connection settings
require_once 'db_config.php';

// Initialize variables for messages
$success_message = '';
$error_message = '';

// CHECK IF FORM WAS SUBMITTED
// When user clicks "Submit", the form sends data via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get the data from the form and remove extra spaces
    // trim() removes whitespace from beginning and end
    $name = trim($_POST['name'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // VALIDATION: Check if fields are empty
    if (empty($name) || empty($message)) {
        $error_message = 'Please fill in both name and message fields.';
    } else {
        
        // PREPARED STATEMENT: This is a secure way to insert data into database
        // It prevents SQL injection attacks by separating SQL code from user data
        // The ? symbols are placeholders that will be replaced with actual values
        $sql = "INSERT INTO messages (name, message) VALUES (?, ?)";
        
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            // Bind parameters: "ss" means both parameters are strings
            // This tells MySQL what type of data to expect
            $stmt->bind_param("ss", $name, $message);
            
            // Execute the statement (actually insert the data)
            if ($stmt->execute()) {
                $success_message = 'Your message has been saved successfully!';
                // Clear the form by resetting variables
                $name = '';
                $message = '';
            } else {
                $error_message = 'Error saving message: ' . $stmt->error;
            }
            
            // Close the prepared statement
            $stmt->close();
        } else {
            $error_message = 'Database error: ' . $conn->error;
        }
    }
}

// FETCH ALL MESSAGES FROM DATABASE
// This query gets all messages ordered by newest first
$sql = "SELECT id, name, message, created_at FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Guestbook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Simple Guestbook</h1>
        <p class="subtitle">Leave your name and a message!</p>
        
        <!-- DISPLAY SUCCESS OR ERROR MESSAGES -->
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                ✓ <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error">
                ✗ <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <!-- THE FORM -->
        <!-- action="" means submit to the same page (index.php) -->
        <!-- method="POST" means data is sent securely in the request body -->
        <form method="POST" action="" class="guestbook-form">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Enter your name"
                    required
                    maxlength="100"
                >
            </div>
            
            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea 
                    id="message" 
                    name="message" 
                    rows="4" 
                    placeholder="Enter your message"
                    required
                ></textarea>
            </div>
            
            <button type="submit" class="btn-submit">Submit Message</button>
        </form>
        
        <hr>
        
        <!-- DISPLAY ALL SAVED MESSAGES -->
        <h2>📬 All Messages (<?php echo $result->num_rows; ?>)</h2>
        
        <div class="messages-list">
            <?php
            // Check if there are any messages in the database
            if ($result->num_rows > 0) {
                // Loop through each message and display it
                // fetch_assoc() gets one row at a time as an associative array
                while ($row = $result->fetch_assoc()) {
                    // htmlspecialchars() prevents XSS attacks by converting special characters
                    $display_name = htmlspecialchars($row['name']);
                    $display_message = htmlspecialchars($row['message']);
                    $display_date = date('F j, Y, g:i a', strtotime($row['created_at']));
                    
                    echo '<div class="message-card">';
                    echo '<div class="message-header">';
                    echo '<strong>' . $display_name . '</strong>';
                    echo '<span class="message-date">' . $display_date . '</span>';
                    echo '</div>';
                    echo '<div class="message-body">' . nl2br($display_message) . '</div>';
                    echo '</div>';
                }
            } else {
                // No messages yet
                echo '<p class="no-messages">No messages yet. Be the first to leave a message!</p>';
            }
            ?>
        </div>
    </div>
    
    <footer>
        <p>Simple PHP Guestbook | Deployed on AWS Lightsail</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
// It's good practice to close connections when done
$conn->close();
?>
