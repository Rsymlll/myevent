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

    // Insert user data into the database
    $sql = "INSERT INTO users (name, email, password, phone, address) 
            VALUES ('$name', '$email', '$password', '$phone', '$address')";

    if ($conn->query($sql) === TRUE) {
        // If registration is successful, show a success popup using JavaScript
        echo "<script>
                alert('You have successfully registered!');
                window.location.href = 'login.html'; // Redirect to the login page after the alert
              </script>";
        exit();  // Stop the script after displaying the alert and redirect
    } else {
        // Display an error if registration fails
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
