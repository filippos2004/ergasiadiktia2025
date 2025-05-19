<?php
session_start();

// Redirect αν δεν υπάρχει συνδεδεμένος χρήστης
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

// Σύνδεση στη βάση
$conn = new mysqli("mysql", "user", "userpass", "my_database");
if ($conn->connect_error) {
    die("Σφάλμα σύνδεσης: " . $conn->connect_error);
}

$success = '';
$error = '';

// Ενημέρωση ονόματος
if (isset($_POST['update'])) {
    $new_name  = $conn->real_escape_string($_POST['new_name']);
    $new_lname = $conn->real_escape_string($_POST['new_lname']);
    $email     = $_SESSION['user_email'];
    $sql       = "UPDATE users SET name='$new_name', lname='$new_lname' WHERE email='$email'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['user_name'] = $new_name;
        $success = 'Το όνομα ενημερώθηκε με επιτυχία.';
    } else {
        $error = 'Σφάλμα κατά την ενημέρωση: ' . $conn->error;
    }
}

// Διαγραφή προφίλ
if (isset($_POST['delete'])) {
    $email = $_SESSION['user_email'];
    $conn->query("DELETE FROM users WHERE email='$email'");
    $conn->close();
    session_destroy();
    header("Location: streamplay.php");
    exit;
}

// Φόρτωση τρεχόντων στοιχείων χρήστη
$email = $_SESSION['user_email'];
$res   = $conn->query("SELECT name, lname FROM users WHERE email='$email'");
$user  = $res->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Stream Play - Profile</title>
    <link rel="icon" href="images/img3.png" type="image/png">
    <style>
        /* Υπάρχον CSS από streamplay.php */
        body { background-image:url('images/img1.jpg'); background-size:cover; margin:0; padding:0; }
        .phrase { display:block; margin:50px auto; font-size:50px; font-family:'Courier New', monospace; background-image:url(images/img4.jpg); color:white; text-align:center; border-radius:40px; width:80%; max-width:600px; }
        .mainwindow { background-image:url(images/img4.jpg);
            width:600px;
            height:500px;
            margin:20px auto;
            padding:20px;
            border-radius:20px; }
        .userdata { color:black; font-size:18px; margin:10px 0; background-image:url(images/img1.jpg); padding:10px; border-radius:20px; }
        .userdata input { width:90%; padding:5px; border:none; border-radius:4px; }
        .otherbtns {
            margin:10px 0; padding:10px; border-radius:70px; border:4px solid #333; background-image:url(images/img1.jpg); display:block; width:100%; text-align:center; cursor:pointer; font-size:16px; }
        .feedback {
            max-width:350px;
            margin:10px auto;
            padding:8px;
            border-radius:4px;
            text-align:center; }
        .feedback.success { background:#e8f5e9; color:#2e7d32; border:1px solid #4caf50; }
        .feedback.error   { background:#ffebee; color:#c62828; border:1px solid #f44336; }
        .continuebtn {

            margin:10px 0;
            padding:10px;
            border-radius:70px;
            border:4px solid #333;
            background-image:url(images/img1.jpg);
            display:block;
            width:100%;
            text-align:center;
            cursor:pointer;
            font-size:16px;
            }
    </style>
</head>
<body>
<div class="phrase">Your Account</div>
<div class="mainwindow">
    <?php if ($success): ?>
        <div class="feedback success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="feedback error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="userdata">
            Όνομα:<br>
            <input type="text" name="new_name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="userdata">
            Επώνυμο:<br>
            <input type="text" name="new_lname" value="<?= htmlspecialchars($user['lname']) ?>" required>
        </div>
        <button class="otherbtns" type="submit" name="update">
            <i class="fa-regular fa-pen-to-square"></i> Αλλαγή Ονόματος
        </button>
    </form>

    <div class="userdata">Email: <?= htmlspecialchars($email) ?></div>

    <form method="POST" action="" onsubmit="return confirm('Το προφίλ θα διαγραφεί. Είστε σίγουροι;');">
        <button class="otherbtns" type="submit" name="delete">
            <i class="fa-regular fa-trash-can"></i> Διαγραφή Προφίλ
        </button>
    </form>

    <form action="logout.php" method="POST">
        <button class="otherbtns">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </button>
    </form>

        <nav>
          <button onclick="window.location.href='main.php'" class="continuebtn" >Continue in the website</button>
        </nav>

</div>
</body>
</html>
