

<?php


if (isset($_POST['submit'])) {
    // Σύνδεση με βάση δεδομένων (χρησιμοποιούμε το όνομα του container ως host)
    $conn = new mysqli("mysql", "user", "userpass", "my_database");

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        die("Σφάλμα σύνδεσης: " . $conn->connect_error);
    }

    // Λήψη δεδομένων από τη φόρμα

    $name = $conn->real_escape_string($_POST['name']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash κωδικού

    // Έλεγχος αν υπάρχει ήδη email
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<p style='color:red;'>this email already exists go fuck yourself.</p>";
    } else {
        // Εισαγωγή στη βάση
        $sql = "INSERT INTO users (name,lname, email, password,username ) VALUES ('$name','$lname', '$email', '$password', '$username')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['user_email']=$email;
            $_SESSION['user_name']=$name;
            header("location:profile.php");
            exit;
        } else {
            echo "Σφάλμα: " . $conn->error;
        }
    }

    $conn->close();

}
?>
