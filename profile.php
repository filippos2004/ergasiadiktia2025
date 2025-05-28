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
    die("Couldnt connect: " . $conn->connect_error);
}

$success = '';
$error = '';

// Ενημέρωση ονόματος
if (isset($_POST['update'])) {
    $new_name  = $conn->real_escape_string($_POST['new_name']);
    $new_lname = $conn->real_escape_string($_POST['new_lname']);
    $email     = $_SESSION['user_email'];
    $sql       = "UPDATE users SET firstname='$new_name', lastname='$new_lname' WHERE email='$email'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['user_name'] = $new_name;
        $success = 'Your name has been updated successfully! .';
    } else {
        $error = 'error your name could be changed ' . $conn->error;
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
$res   = $conn->query("SELECT firstname, lastname FROM users WHERE email='$email'");
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
        body { background-image:url('images/bckgrd.gif'); background-size:cover; margin:0; padding:0; }
        .phrase { box-shadow: 0 0 10px 5px white; display:block; margin:50px auto; font-size:50px; font-family:'Courier New', monospace; background-image:url(images/wow.gif); color:white; text-align:center; border-radius:40px; width:80%; max-width:600px; }
        .mainwindow { background-image:url(images/wow.gif);
            width:600px;
            height:500px;
            margin:20px auto;
            padding:20px;
            border-radius:20px;
            box-shadow: 0 0 10px 5px white}

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
<div class="phrase">Ο λογαριασμός σας </div>
<div class="mainwindow">
    <?php if ($success): ?>
        <div class="feedback success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="feedback error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="userdata">
            Name:<br>
            <input type="text" name="new_name" value="<?= htmlspecialchars($user['firstname']) ?>" required>
        </div>
        <div class="userdata">
          Last name :<br>
            <input type="text" name="new_lname" value="<?= htmlspecialchars($user['lastname']) ?>" required>
        </div>
        <button class="otherbtns" type="submit" name="update">
            <i class="fa-regular fa-pen-to-square"></i> Change name
        </button>
    </form>

    <div class="userdata">Email: <?= htmlspecialchars($email) ?></div>

    <form method="POST" action="" onsubmit="return confirm('Το προφίλ θα διαγραφεί. Είστε σίγουροι;');">
        <button class="otherbtns" type="submit" name="delete">
            <i class="fa-regular fa-trash-can"></i> Delete profile
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
    <audio id="a" src="audio/pop.mp3" preload="auto"></audio>
</div>
</body>
</html>
<script>
function playAudio() {
    documenet.getElementById("a").play();
}
</script>
