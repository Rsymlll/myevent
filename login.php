<?php
session_start();  // Start a new session
$conn = new mysqli('localhost', 'root', '', 'myevent');

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the user exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the user data from the database
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user information in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            
            // If login is successful, show an alert using JavaScript and redirect
            echo "<script>
                    alert('You are successfully logged in!');
                    window.location.href = 'dashboard.html'; // Redirect to the dashboard or home page
                  </script>";
            exit();  // Stop the script after the alert and redirect
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with that email.";
    }
}
?>
