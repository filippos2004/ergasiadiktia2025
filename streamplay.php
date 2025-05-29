<?php
$feedback = "";
$error="";
$success="";
$username="";
$password="";
$email="";
$name="";
$lname="";

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
    if (empty($name) || empty($lname) || empty($email) || empty($username) || empty($password)){
        $feedback="Please fill all the fields";

    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $feedback="Invalid email address";

    } else if ($check->num_rows > 0) {
        $feedback="Email already exists please choose another one.";
    } else {
        // Εισαγωγή στη βάση
        $sql = "INSERT INTO users (firstname,lastname, email, password,username ) VALUES ('$name','$lname', '$email', '$password', '$username')";
        if ($conn->query($sql) === TRUE) {
            $feedback ="Signup successfull please go back and login";
        } else {
            $feedback =  "Σφάλμα: " . $conn->error;
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

        .helpdiv{
            cursor: pointer;
            color white;
            background-color:black;
            margin-left:1700px;
            margin-right: 750px;
            border-radius:50px;
            margin-top:-350px;

        }
        .help1{
            cursor: pointer;
            width:50px;
            height:50px;
            display:block;
            margin-right:auto;
            margin-left:-50px;
            font-size:20px;
            font-family:'Courier New', monospace;
            color:white;
            text-align:center;
            background-image: url("images/img4.jpg");
            border-radius: 40px;
            transition: transform 0.1s ease;
            box-shadow: 0 0 10px 5px white;
            transition: box-shadow 0.2s ease;

        }
        .aboutbtns{
            cursor: pointer;
            color white;
            background-color:black;
            margin-left:1700px;
            margin-right: 750px;
            border-radius:50px;
            margin-top:25px;
            margin-bottom:100px;

        }
        .about1{

            cursor: pointer;

            width:50px;
            height:50px;
            border-radius:50px;
            display:block;
            margin-right:auto;
            margin-left:-50px;
            margin-top:40px;
            margin-bottom:auto;
            font-size:20px;
            font-family:'Courier New', monospace;
            color:white;
            text-align:center;
            background-image: url("images/img4.jpg");
            border-radius: 40px;
            transition: transform 0.1s ease;
            box-shadow: 0 0 10px 5px white;
            transition: box-shadow 0.2s ease;
        }
        .phrase{
            margin: auto;
            max-width: 1000px;
            min-width: 1000px;
            display:block;
            margin-right:550px;
            margin-left:550px;
            font-size:50px;
            font-family:'Courier New', monospace;
            background-image: url("images/wow.gif");
            color:white;
            text-align:center;
            border-radius:40px;
            box-shadow: 0 0 10px 10px white;
        }
        .phrase2{
            margin: auto;
            max-width: 1000px;
            min-width: 1000px;
            display:block;
            margin-right:550px;
            margin-left:550px;
            margin-top:10px;
            margin-bottom:100px;
            font-size:25px;
            font-family:'Courier New', monospace;
            color:white;
            text-align:center;

        }
        .btn1{
            margin: auto;
            max-width: 2000px;
            position: relative;
            display:block;
            margin-right:790px;
            margin-left:auto;
            font-size:30px;
            font-family:'Courier New', monospace;
            background-color:black;
            color:white;
            text-align:center;
            border-radius:40px;
            padding-left:30px;
            padding-right:30px;
            border-width:2px;
            cursor: pointer;
            transition: transform 0.1s ease;
            box-shadow: 0 0 10px 5px white;
            transition: box-shadow 0.2s ease;
        }
        .btn1:active {box-shadow: 0 0 15px red;}
        .btn2:active {box-shadow: 0 0 15px red;}
        .about1:active {box-shadow: 0 0 15px red;}
        .help1:active {box-shadow: 0 0 15px red;}
        .btn2{
            display:block;
            max-width: 2000px;
            margin-right:790px;
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
            border-width:2px;
            cursor: pointer;
            transition: transform 0.1s ease;
            box-shadow: 0 0 10px 5px white;
            transition: box-shadow 0.2s ease;
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
            background-image: url("images/wow.gif");
            display:block;
            width:500px;
            height:600px;
            border-radius:20px;
            margin-left:790px;
            margin-right:auto;
            margin-bottom:50px;
            margin-top:-80px;
            transition: transform 0.1s ease;
            box-shadow: 0 0 10px 5px white;
        }
        .signin{
            background-image: url("images/wow.gif");
            display:block;
            width:500px;
            height:250px;
            border-radius:20px;
            border-width:20px;
            margin-left:800px;
            margin-right:auto;
            margin-bottom:10px;
            margin-top:100px;
            transition: transform 0.1s ease;
            box-shadow: 0 0 10px 5px white;

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
        .help1-pop{ animation: pop 0.3s forwards;}
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
            font-size:16px;
            font-family:'Courier New', monospace;
            margin-left:1219px;
            margin-top:-100px;
            border-radius:50px;
            border-width:10px;
        }
        .back{
            background-color:black;
            color:white;
            font-size:16px;
            font-family:'Courier New', monospace;
            margin-left:-460px;
            margin-top:-100px;
            border-radius:50px;
            border-width:10px;

        }
        .backk{
            background-color:black;
            color:white;
            font-size:16px;
            font-family:'Courier New', monospace;
            margin-left:0px;
            margin-right: 10px;
            margin-top:10px;
            margin-bottom:10px;
            border-radius:50px;
            border-width:10px;
        }
        .extrr{
            background-color:black;
            color:white;
            font-size:16px;
            font-family:'Courier New', monospace;
            margin-left:380px;
            margin-right: 0px;
            margin-top:100px;
            margin-bottom:10px;
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
        .soundbtn {
            margin-right: auto;
            margin-left: -50px;
            background-image: url("images/img4.jpg");
            width: 50px;
            height: 50px;
            color: white;
            border-radius: 50px;
            box-shadow: 0 0 10px 5px white;
            cursor: pointer;
            transition: box-shadow 0.2s ease;
        }
        .soundbtn:active {box-shadow: 0 0 15px red;}
    </style>
</head>
<body style="background-image:url('images/bckgrd.gif');background-repeat: no-repeat;background-size: cover;">
<img src="images/img2.png" class="image">
<div class="phrase">Stream Play</div>
<div class="phrase2">your #1 streaming website</div>
<div id="divbtn" class="singbtns">
    <button id="btn1" class="btn1" onclick="signin()">Sign-in</button>
    <button id="btn2" class="btn2" onclick="signup()">Sign-up</button>
</div>
<div id="helpdiv" class="helpdiv">
    <button id="help" class="help1"  onclick="window.location.href='help.html'"><i class="fa-solid fa-question"></i>
    </button>
</div>
<div id="aboutbtn" class="aboutbtns">
    <button id="about" class="about1" onclick="window.location.href='about.html'"><i class="fa-solid fa-circle-info"></i></button>
    <br>
    <br>
    <button class="soundbtn" onclick="playSound()"><i class="fa-solid fa-music"></i></button>
</div>


<form method="POST" action="" >
    <div id="id1" class="signupform hide">
        <i id="id2" class="fonts hide">Name</i><br>
        <input id="id3" class="textype hide" type="text" name="name" placeholder="  How shall we call you?"><br>
        <i id="id11" class="fonts hide">Last name</i>
        <input id="id12" class="textype hide" type="text" name="lname" placeholder=" Give us your Last Name"><br>
        <i id="id13"class="fonts hide">Username</i>
        <input id="id14" class="textype hide" type="text" name="username" placeholder=" Give us your Username"><br>
        <i id="id4" class="fonts hide">E-mail</i><br>
        <input id="id5" class="textype hide" type="text" name="email" placeholder="  What's your e-mail?"><br>
        <i id="id6" class="fonts hide">Password</i><br>
        <input id="id8" class="textype hide" type="password" name="password" placeholder="  Tell us your secret!"><br>
        <button id="id7" class="submit hide" type="submit" name="submit" onclick="save()">Eγγραφή</button>
    </div>

</form>
<button id="id9" class="extr hide" onclick="togglePassword()">
    <i class="fa-regular fa-eye-slash"></i>
</button>
<button id="id10" class="back hide" onclick="back()"><i class="fa-solid fa-house"></i></button>




<div id="ic1" class="signin hide">

    <i id="ic2" class="font hide">Welcome back!</i><br>
    <form method="POST" action="login.php" id="icform"   >

        <input id="ic3" class="textype hide" name="email" type="text" placeholder="Enter your e-mail here!"><br>
        <input id="ic4" class="textype hide" name="password" type="password" placeholder="Enter your password here!"><br>
        <button id="ic5" class="submit hide" name="submit">Log-in</button>
    </form>
    <button id="id30" class="backk hide" onclick="backk()">
        <i class="fa-solid fa-house"></i></button>
    <button id="id31" class="extrr hide" onclick="togglePassword1()">
        <i class="fa-regular fa-eye-slash"></i>
    </button>
</div>
<audio id="bgAudio" autoplay muted>
    <source src="audio/chill.mp3" type="audio/mpeg">
</audio>
<audio id="a" src="audio/pop.mp3" preload="auto"></audio>
<audio id="b" src="audio/success.mp3" preload="auto"></audio>
<audio id="welcomeSound" src="audio/chill.mp3"></audio>
<script>
    function playSound() {
        const audio = document.getElementById("welcomeSound");
        const button = document.getElementById("soundButton");

        audio.volume = 0.5;

        if (audio.paused) {
            audio.play();
            button.textContent = "Stop Sound";
        } else {
            audio.pause();
            audio.currentTime = 0;
            button.textContent = "Play Sound";
        }
    }
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
            document.getElementById("helpdiv").classList.add("hide");
            document.getElementById("aboutbtn").classList.add("hide");


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
        const back6= document.getElementById("helpdiv");
        back6.classList.remove("hide");
        const back7= document.getElementById("aboutbtn");
        back7.classList.remove("hide");


    }
    function backk(){
        document.getElementById("a").play();
        const back1= document.getElementById("ic1");
        back1.classList.add("hide");
        const back2 = document.getElementById("btn1");
        back2.classList.remove("hide");
        const back3 = document.getElementById("btn2");
        back3.classList.remove("hide");
        const back4= document.getElementById("id31");
        back4.classList.add("hide");
        const back5= document.getElementById("id30");
        back5.classList.add("hide");
        const back6= document.getElementById("helpdiv");
        back6.classList.remove("hide");
        const back7= document.getElementById("aboutbtn");
        back7.classList.remove("hide");


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
            document.getElementById("helpdiv").classList.add("hide");
            document.getElementById("aboutbtn").classList.add("hide");


            ["ic1", "ic2", "ic3", "ic4", "ic5", "id31"].forEach(id => {
                document.getElementById(id).classList.remove("hide");
            });
            document.getElementById("id30").classList.remove("hide");
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
    function togglePassword1() {
        document.getElementById("a").play();
        const pwdInput1 = document.getElementById("ic4")
        const toggleBtn1 = document.getElementById("id31");
        const isPassword1 = pwdInput1.type === "password";
        pwdInput1.type = isPassword1 ? "text" : "password";
        toggleBtn1.innerHTML = isPassword1
            ? '<i class="fa-regular fa-eye"></i></i>'
            : '<i class="fa-regular fa-eye-slash"></i>';
    }
    function save(){
        document.getElementById("a").play();

    }
    function feedbackmessage(feedback_msg) {
        if(feedback_msg){
            alert(feedback_msg);
        }
    }
    feedbackmessage("<?php echo $feedback; ?>");


</script>
</body>
</html>