<?php
session_start();

// Αν ο χρήστης είναι ήδη συνδεδεμένος
if (isset($_SESSION['user_email'])) {
    header("Location: profile.php");
    exit;
}

if (isset($_POST['submit'])) {
    $conn = new mysqli("mysql", "user", "userpass", "my_database");

    if ($conn->connect_error) {
        die("Σφάλμα σύνδεσης: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: profile.php");
            exit;
        } else {
            $error = "wrong password.";
        }
    } else {
        $error = "user not found.";
    }

    $conn->close();
}
 if (!empty($error)) echo "<p style='color:red;'>$error</p>";
?>
