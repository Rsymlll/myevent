<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'myevent');

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = preg_replace('/\D/', '', $_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    if (!$email) {
        echo "<script>
                alert('Invalid email address. Please try again.');
                window.location.href = 'register.html';
              </script>";
        exit();
    }

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo "<script>
                alert('This email is already registered. Please use a different email.');
                window.location.href = 'register.html';
              </script>";
        exit();
    } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $phone, $address);

        if ($stmt->execute()) {
            // Registration successful
            echo "<script>
                    alert('You have successfully registered!');
                    window.location.href = 'login.html';
                  </script>";
            exit();
        } else {
            // Registration failed
            die("Error: " . $stmt->error);
        }
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>
