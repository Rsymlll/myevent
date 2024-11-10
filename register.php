<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'myevent');

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Check if the email already exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If email already exists, show an alert and stop the script
        echo "<script>
                alert('This email is already registered. Please use a different email.');
                window.location.href = 'register.html'; // Stay on the registration page
              </script>";
        exit();  // Stop further execution if email exists
    } else {
        // If email does not exist, insert new user data into the database
        $sql = "INSERT INTO users (name, email, password, phone, address) 
                VALUES ('$name', '$email', '$password', '$phone', '$address')";

        if ($conn->query($sql) === TRUE) {
            // If registration is successful, show a success popup and redirect to login page
            echo "<script>
                    alert('You have successfully registered!');
                    window.location.href = 'login.html'; // Redirect to login page
                  </script>";
            exit();  // Stop the script after the alert and redirect
        } else {
            // Display an error if registration fails
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
