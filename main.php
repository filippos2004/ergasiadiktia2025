<?php
session_start();
// Redirect αν δεν υπάρχει συνδεδεμένος χρήστης
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

// YouTube API Key
$API_KEY = 'AIzaSyAQSFkuyunDzlotbB39jSNNTtKXc2SCN0o';

// DB σύνδεση
$conn = new mysqli("mysql","user","userpass","my_database");
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);
$user = $_SESSION['user_email'];

// Δημιουργία playlist
if (isset($_POST['create_playlist'])) {
    $pname = $conn->real_escape_string($_POST['playlist_name']);
    $pub   = isset($_POST['is_public']) ? 1 : 0;
    $st = $conn->prepare("INSERT INTO playlists (user_email,name,is_public) VALUES (?,?,?)");
    $st->bind_param("ssi", $user, $pname, $pub);
    $st->execute(); $st->close();
    header("Location: main.php#playlist"); exit;
}

// Προσθήκη βίντεο σε playlist
if (isset($_POST['add_item'])) {
    $pid = (int)$_POST['playlist_id'];
    $vid = $conn->real_escape_string($_POST['youtube_id']);
    $ttl = $conn->real_escape_string($_POST['title']);
    $st = $conn->prepare("INSERT INTO playlist_items (playlist_id,youtube_id,title) VALUES (?,?,?)");
    $st->bind_param("iss", $pid, $vid, $ttl);
    $st->execute(); $st->close();
    header("Location: main.php?show=playlist&q=".urlencode($_GET['q'] ?? '')."#playlist"); exit;
}

// Φόρτωση λιστών και items
$lists = [];
$res = $conn->query("SELECT id,name FROM playlists WHERE user_email='".$conn->real_escape_string($user)."'");
while ($r = $res->fetch_assoc()) {
    $lists[] = $r;
}

// Search YouTube
$search_results = [];
$q = $_GET['q'] ?? '';
if ($q !== '') {
    $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=5&q=".urlencode($q)."&key={$API_KEY}";
    $data = json_decode(file_get_contents($url), true);
    $search_results = $data['items'] ?? [];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <title>Main - StreamPlay</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{margin:0;padding:20px;font-family:sans-serif;background:#eee}
        .nav{display:flex;justify-content:flex-end;gap:10px}
        .nav button{padding:8px 16px;cursor:pointer}
        .section{background:black;padding:15px;margin-bottom:20px;border-radius:8px;color:white;background-image:url(images/img4.jpg);}
        .hide{display:none}
        .video{display:flex;align-items:flex-start;margin-bottom:15px}
        .video iframe{width:200px;height:113px;margin-right:10px}
        .video-info{flex:1}
        .video-info form{margin-top:10px}
        select,input[type=text]{padding:5px;font-size:14px}
        button{padding:6px 12px;font-size:14px;cursor:pointer}
        .logout{
            color:white;
            background-color:black;
            border-radius: 10px;
            background-image:url(images/img4.jpg);

        }
        .profile{
            color:white;
            background-color:black;
            border-radius: 10px;
            background-image:url(images/img4.jpg);

        }
        .myplaylists{
            color:white;
            background-color:black;
            border-radius: 10px;
            background-image:url(images/img4.jpg);


        }
        .search{
            color:white;
            background-color:black;
            border-radius: 10px;
            background-image:url(images/img4.jpg);
        }
        .search-form{
            color:black;
            background-color:white;
            border-radius: 10px;
            width: 1000px;
            height:auto;
            padding: 5px;
            margin-left: 200px;
            margin-right:auto;

        }
        .searh2{
            color:white;
            background-color:white;
            border-radius: 10px;
            background-image:url(images/img4.jpg);

        }


    </style>
</head>
<body style="background-image:url('images/img1.jpg');">
<div class="nav">
    <button onclick="showSection('search')" class="search">Search</button>
    <button onclick="showSection('playlist')"class="myplaylists">My Playlists</button>
    <button onclick="location.href='profile.php'"class="profile">Profile</button>
    <button onclick="location.href='logout.php'"class="logout">Logout</button>
</div>

<!-- Search Section -->
<div  id="search" class="section"  <?=($_GET['show'] ?? 'search')!=='search'?'class="section hide"':'class="section"'?>>
    <h2>Search YouTube</h2>
    <form method="GET" >
        <input type="text" name="q" placeholder="Search..." class="search-form"value="<?=htmlspecialchars($q)?>">
        <button type="submit"class="searh2">Search</button>
        <input type="hidden" name="show" value="search">
    </form>
    <?php if($search_results): ?>
        <?php foreach($search_results as $v):
            $id=$v['id']['videoId']; $t=$v['snippet']['title'];
            ?>
            <div class="video">
                <iframe src="https://www.youtube.com/embed/<?=$id?>" frameborder="0" allowfullscreen></iframe>
                <div class="video-info">
                    <strong><?=htmlspecialchars($t)?></strong>
                    <form method="POST">
                        <input type="hidden" name="youtube_id" value="<?=$id?>">
                        <input type="hidden" name="title" value="<?=htmlspecialchars($t)?>">
                        <select name="playlist_id">
                            <?php foreach($lists as $l): ?>
                                <option value="<?=$l['id']?>"><?=$l['name']?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="add_item">Add to Playlist</button>
                    </form>
                </div>
            </div>
        <?php endforeach; endif; ?>
</div>

<!-- Playlist Section -->
<div id="playlist" class="section <?=($_GET['show'] ?? 'search')!=='playlist'?'hide':''?>">
    <h2>My Playlists</h2>
    <div>
        <h3>Create New</h3>
        <form method="POST">
            <input type="text" name="playlist_name" placeholder="Playlist name" required>
            <label><input type="checkbox" name="is_public"> Public</label>
            <button type="submit" name="create_playlist">Create</button>
        </form>
    </div>
    <div>
        <h3>Existing</h3>
        <?php if($lists): ?>
            <ul>
                <?php foreach($lists as $l): ?>
                    <li><?=htmlspecialchars($l['name'])?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No playlists yet.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    function showSection(sec){
        document.getElementById('search').classList.add('hide');
        document.getElementById('playlist').classList.add('hide');
        document.getElementById(sec).classList.remove('hide');
    }
    // Default to show '<?=($_GET['show']??'search')?>'
    showSection('<?=($_GET['show']??'search')?>');
</script>
</body>
</html>
