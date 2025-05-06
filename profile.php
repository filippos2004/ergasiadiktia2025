<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

echo "<h2>Καλώς ήρθες, " . $_SESSION['user_name'] . "!</h2>";
echo "<p>Email: " . $_SESSION['user_email'] . "</p>";
echo "<a href='logout.php'>Αποσύνδεση</a>";
?>
