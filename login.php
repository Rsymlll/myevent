<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'myevent');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            echo "<script>
                    alert('You are successfully logged in!');
                    window.location.href = 'dashboard.html';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href = 'login.html';</script>";
        }
    } else {
        echo "<script>alert('No user found with that email.'); window.location.href = 'login.html';</script>";
    }
}
?>
