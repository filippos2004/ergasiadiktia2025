

<?php

if (isset($_POST['submit'])) {
    // Σύνδεση με βάση δεδομένων (χρησιμοποιούμε το όνομα του container ως host)
    $conn = new mysqli("mysql", "user", "userpass", "my_database");

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        die("Σφάλμα σύνδεσης: " . $conn->connect_error);
    }

    // Λήψη δεδομένων από τη φόρμα
    $success="";
    $error="";
    $name = $conn->real_escape_string($_POST['name']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash κωδικού

    // Έλεγχος αν υπάρχει ήδη email
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error="Email already exists please choose another one.";
    } else {
        // Εισαγωγή στη βάση
        $sql = "INSERT INTO users (firstname,lastname, email, password,username ) VALUES ('$name','$lname', '$email', '$password', '$username')";
        if ($conn->query($sql) === TRUE) {
            $success ="Signup successfull please go back and login";
        } else {
            echo "Σφάλμα: " . $conn->error;
        }
    }

    $conn->close();

}
?>









<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Stream Play</title>
    <link rel="icon" href="images/img3.png" type="image/png">
    <style>
        {
            flex: 0 0 200px;
            padding: 10px;
            border: 1px solid #4caf50;
            border-radius: 4px;
            background: #e8f5e9;
            color: #2e7d32;
            display: <?= $success ? 'block' : 'none' ?>;

        }
        {
            border-color: #f44336;
            background: #ffebee;
            color: #c62828;
        }
        .phrase{
            display:block;
            margin-right:550px;
            margin-left:550px;
            font-size:50px;
            font-family:'Courier New', monospace;
            background-image: url("images/img4.jpg");
            color:white;
            text-align:center;
            border-radius:40px;
        }
        .phrase2{
            display:block;
            margin-right:550px;
            margin-left:550px;
            margin-top:10px;
            margin-bottom:100px;
            font-size:25px;
            font-family:'Courier New', monospace;
            color:black;
            text-align:center;
        }
        .btn1{
            display:block;
            margin-right:1000px;
            margin-left:auto;
            font-size:30px;
            font-family:'Courier New', monospace;
            background-color:black;
            color:white;
            text-align:center;
            border-radius:40px;
            padding-left:30px;
            padding-right:30px;
            border-width:6px;
            cursor: pointer;
            transition: transform 0.1s ease;

        }
        .btn2{
            display:block;
            margin-right:1000px;
            margin-left: auto ;
            margin-top: 40px;
            margin-bottom:40px;
            font-size:30px;
            font-family:'Courier New', monospace;
            color:white;
            background-color:black;
            text-align:center;
            border-radius:40px;
            padding-left:30px;
            padding-right:30px;
            border-width:6px;
            cursor: pointer;
            transition: transform 0.1s ease;
        }
        .image{
            display:block;
            margin-top:50px;
            margin-bottom:-400px;
        }
        .exbar{
            background-color:white;
            width:50px;
        }
        .signupform {
            background-image: url("images/img4.jpg");
            display:block;
            width:500px;
            height:600px;
            border-radius:20px;
            margin-left:600px;
            margin-right:600px;
            margin-bottom:50px;
            margin-top:-70px;
            transition: transform 0.1s ease;
        }
        .signin{
            background-image: url("images/img4.jpg");
            display:block;
            width:500px;
            height:250px;
            border-radius:20px;
            border-width:20px;
            margin-left:800px;
            margin-right:600px;
            margin-bottom:50px;
            margin-top:-70px;
            transition: transform 0.1s ease
        }
        .fonts{
            color:white;
            font-family:'Courier New', monospace;
            font-size:30px;
            margin-left:180px;
            margin-right:10px;
            text-align: center;
        }
        .textype{
            background-color:black;
            border-radius:20px;
            border-color:white;
            border-width:2px;
            margin-left:5px;
            margin-right:5px;
            margin-top:10px;
            margin-bottom:30px;
            font-size:15px;
            width:480px;
            height:25px;
            color:white;
        }
        .submit{
            background-color:black;
            color:white;
            border-radius:10px;
            font-size:20px;
            font-family:'verdana', monospace;
            margin-left:140px;
            margin-right:150px;
            width:200px;
            height:50px;
        }
        .signin-yes{animation: pop 0.3s forwards;}
        .signupform-yes{animation: pop 0.3s forwards;}
        .hide { display: none;}
        .btn2-pop{ animation: pop 0.3s forwards;}
        .btn1-pop{ animation: pop 0.3s forwards;}
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        @keyframes yes {
            0% { transform: scale(1.2); }
            50% { transform: scale(1); }
            100% { transform: scale(1.2); }
        }
        .extr{
            background-color:black;
            color:white;
            border-radius:5px;
            font-size:16px;
            font-family:'Courier New', monospace;
            margin-left:1025px;
            margin-top:-100px;
            border-radius:50px;
            border-width:10px;
        }
        .back{
            background-color:black;
            color:white;
            border-radius:5px;
            font-size:16px;
            font-family:'Courier New', monospace;
            margin-left:-460px;
            margin-top:-100px;
            border-radius:50px;
            border-width:10px;
        }
        .font{
            color:white;
            font-family:'Courier New', monospace;
            font-size:30px;
            margin-left:125px;
            margin-right:10px;
            text-align: center;
        }
    </style>
</head>
<body style="background-image:url('images/img1.jpg');">
<img src="images/img2.png" class="image">
<div class="phrase">Stream Play</div>
<div class="phrase2">your #1 streaming website</div>

<button id="btn1" class="btn1" onclick="signin()">Sign-in</button>
<button id="btn2"class="btn2" onclick="signup()">Sign-up</button>

<form method="POST" action="" id="id1" class="signupform hide" >
    <i id="id2" class="fonts hide">First-Name</i><br>
    <input id="id3" class="textype hide" type="text" name="name" placeholder="  How shall we call you?"><br>
    <i id="id11" class="fonts hide">Last-Name</i>
    <input id="id12" class="textype hide" type="text" name="lname" placeholder=" Give us your Last Name"><br>
    <i id="id13"class="fonts hide">Username</i>
    <input id="id14" class="textype hide" type="text" name="username" placeholder=" Give us your Username"><br>
    <i id="id4" class="fonts hide">E-mail</i><br>
    <input id="id5" class="textype hide" type="text" name="email" placeholder="  What's your e-mail?"><br>
    <i id="id6" class="fonts hide">Password</i><br>
    <input id="id8" class="textype hide" type="password" name="password" placeholder="  Tell us your secret!"><br>
    <button id="id7" class="submit hide" type="submit" name="submit" onclick="save()">Sign-up</button>
    <div id="signupMessage" class="">

    </div>

</form>
<button id="id9" class="extr hide" onclick="togglePassword()">
    <i class="fa-regular fa-eye-slash"></i>
</button>
<button id="id10" class="back hide" onclick="back()"><i class="fa-solid fa-house"></i>
</button>
<form method="POST" action="login.php">
    <div  id="ic1" class="signin hide">
        <i id="ic2" class="font hide">Welcome back!</i><br>
        <input id="ic3" class="textype hide" name="email" type="text" placeholder="Enter your e-mail here!"><br>
        <input id="ic4" class="textype hide" name="password" type="password" placeholder="Enter your password here!"><br>
        <button id="ic5" class="submit hide" name="submit">Log-in</button>
    </div>
</form>
<audio id="a" src="audio/pop.mp3" preload="auto"></audio>
<audio id="b" src="audio/success.mp3" preload="auto"></audio>
<script>
    function signup() {
        document.getElementById("a").play();
        const btn2 = document.getElementById("btn2");
        btn2.classList.add("btn2-pop");

        btn2.addEventListener("animationend", function handler() {
            btn2.classList.remove("btn2-pop");
            btn2.removeEventListener("animationend", handler);
        });
        const bubble = document.getElementById("id1");
        bubble.classList.add("signupform-yes");

        setTimeout(() => {
            document.getElementById("btn1").classList.add("hide");
            document.getElementById("btn2").classList.add("hide");

            ["id1", "id2", "id3", "id4", "id5", "id6", "id7", "id8", "id9", "id10","id11","id12","id13","id14"].forEach(id => {
                document.getElementById(id).classList.remove("hide");
            });
        }, 300);
    }
    function back(){
        document.getElementById("a").play();
        const back1= document.getElementById("id1");
        back1.classList.add("hide");
        const back2 = document.getElementById("btn1");
        back2.classList.remove("hide");
        const back3 = document.getElementById("btn2");
        back3.classList.remove("hide");
        const back4= document.getElementById("id9");
        back4.classList.add("hide");
        const back5= document.getElementById("id10");
        back5.classList.add("hide");
    }
    function signin() {
        document.getElementById("a").play();
        const btn2 = document.getElementById("btn1");
        btn2.classList.add("btn1-pop");

        btn2.addEventListener("animationend", function handler() {
            btn2.classList.remove("btn1-pop");
            btn2.removeEventListener("animationend", handler);
        });
        const bubble = document.getElementById("ic1");
        bubble.classList.add("signin-yes");

        setTimeout(() => {
            document.getElementById("btn1").classList.add("hide");
            document.getElementById("btn2").classList.add("hide");

            ["ic1", "ic2", "ic3", "ic4", "ic5","id10"].forEach(id => {
                document.getElementById(id).classList.remove("hide");
            });
        }, 300);
    }
    function togglePassword() {
        document.getElementById("a").play();
        const pwdInput = document.getElementById("id8");
        const toggleBtn = document.getElementById("id9");
        const isPassword = pwdInput.type === "password";
        pwdInput.type = isPassword ? "text" : "password";
        toggleBtn.innerHTML = isPassword
            ? '<i class="fa-regular fa-eye"></i></i>'
            : '<i class="fa-regular fa-eye-slash"></i>';

    }
    function save(){
        document.getElementById("a").play();

    }
</script>
</body>
</html>



