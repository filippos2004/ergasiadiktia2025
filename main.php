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
    $st = $conn->prepare("INSERT INTO playlists (user_email,name,is_public,created_at) VALUES (?,?,?,NOW())");
    $st->bind_param("ssi", $user, $pname, $pub);
    $st->execute(); $st->close();
    header("Location: main.php?show=playlists"); exit;
}
//Διαγραφη playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_playlist'], $_POST['playlist_id_delete'])) {
    $pid = (int)$_POST['playlist_id_delete'];
    if ($pid > 0) {
        // Διαγραφή items
        $st1 = $conn->prepare("DELETE FROM playlist_items WHERE playlist_id = ?");
        $st1->bind_param("i", $pid);
        $st1->execute(); $st1->close();
        // Διαγραφή playlist
        $st2 = $conn->prepare("DELETE FROM playlists WHERE id = ? AND user_email = ?");
        $st2->bind_param("is", $pid, $user);
        $st2->execute(); $st2->close();
    }
    header("Location: main.php?show=playlists");
    exit;

}
// Προσθήκη βίντεο σε playlist
if (isset($_POST['add_item'])) {
    $pid = (int)$_POST['playlist_id'];
    $vid = $conn->real_escape_string($_POST['youtube_id']);
    $ttl = $conn->real_escape_string($_POST['title']);
    $st = $conn->prepare("INSERT INTO playlist_items (playlist_id,youtube_id,title,added_at) VALUES (?,?,?,NOW())");
    $st->bind_param("iss", $pid, $vid, $ttl);
    $st->execute(); $st->close();
    header("Location: main.php?show=view&pid={$pid}"); exit;
}

// Search YouTube
$search_results = [];
$q = $_GET['q'] ?? '';
if ($q !== '') {
    $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=5&q=".urlencode($q)."&key={$API_KEY}";
    $data = json_decode(file_get_contents($url), true);
    $search_results = $data['items'] ?? [];
}

// Φόρτωση λιστών
$lists = [];
$res = $conn->query("SELECT id,name,created_at FROM playlists WHERE user_email='".$conn->real_escape_string($user)."' ORDER BY created_at DESC");
while ($r = $res->fetch_assoc()) {
    $lists[] = $r;
}

// Φόρτωση στοιχείων playlist για view section
$view_items = [];
if (isset($_GET['show']) && $_GET['show']==='view' && !empty($_GET['pid'])) {
    $pid = (int)$_GET['pid'];
    $stmt = $conn->prepare("SELECT youtube_id,title,added_at FROM playlist_items WHERE playlist_id=? ORDER BY added_at DESC");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $view_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
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
        .nav button{padding:8px 16px;cursor:pointer; background-image:url(images/img4.jpg);border-radius:20px;color:white}
        .section{background:black;padding:15px;margin-bottom:20px;border-radius:8px;color:white;background-image:url(images/img4.jpg);}
        .hide{display:none}
        .video{display:flex;align-items:flex-start;margin-bottom:15px}
        .video iframe{width:200px;height:113px;margin-right:10px}
        .video-info{flex:1}
        .video-info form{margin-top:10px}
        select,input[type=text]{padding:5px;font-size:14px}
        .search-form{width:60%; padding:8px; border-radius:20px;}
        button{padding:6px 12px;font-size:14px;cursor:pointer}
        .search2{padding:8px 16px;cursor:pointer; background-image:url(images/img4.jpg);border-radius:20px;color:white}
        .add{padding:8px 16px;cursor:pointer; background-image:url(images/img4.jpg);border-radius:20px;color:white}
        .playlistname{padding:8px 16px;cursor:pointer; background-image:url('images/img1.jpg');border-radius:20px;color:black;}
        .create{padding:8px 16px;cursor:pointer; background-image:url(images/img4.jpg);border-radius:20px;color:white}
        .playlistname1{width:20%; padding:8px; border-radius:20px;}
        .deleteplaylist{padding:8px 16px;cursor:pointer; background-image:url(images/img4.jpg);border-radius:20px;color:white}
    </style>
</head>
<body style="background-image:url('images/img1.jpg');">
<div class="nav">
    <button id="search1" class="search1" onclick="showSection('search')">Αναζητηση</button>
    <button id="playlist1"class="playlist" onclick="showSection('playlists')"> Playlists</button>
    <button id="profile1" class="profile" onclick="location.href='profile.php'">Profile</button>
    <button id="logout" class="logout" onclick="location.href='logout.php'" >Αποσυδεση</button>
</div>

<!-- Search Section -->
<div id="search" class="section hide">
    <h2>Search YouTube</h2>
    <form method="GET">
        <input type="text" name="q" placeholder="Search..." class="search-form" value="<?=htmlspecialchars($q)?>">
        <button class="search2" type="submit" onclick=playaudio() >Search</button>
        <input type="hidden" name="show" value="search">
    </form>
    <?php if($search_results): ?>
        <?php foreach($search_results as $v): $id=$v['id']['videoId']; $t=$v['snippet']['title']; ?>
            <div class="video">
                <iframe src="https://www.youtube.com/embed/<?=$id?>" frameborder="0" allowfullscreen></iframe>
                <div class="video-info">
                    <strong><?=htmlspecialchars($t)?></strong>
                    <form method="POST">
                        <input type="hidden" name="youtube_id" value="<?=$id?>">
                        <input type="hidden" name="title" value="<?=htmlspecialchars($t)?>">
                        <select class="playlistname" name="playlist_id">
                            <?php foreach($lists as $l): ?>
                                <option value="<?=$l['id']?>"><?=htmlspecialchars($l['name'])?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="add"type="submit" name="add_item" onclick=playaudio() >Προσθηκη</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Playlists Section -->
<div id="playlists" class="section hide">
    <h2>My Playlists</h2>
    <div class="section">
        <h3>Create New</h3>
        <form method="POST">
            <input class="playlistname1" type="text" name="playlist_name" placeholder="Playlist name" required>
            <label><input type="checkbox" name="is_public"> Public</label>
            <button class="create" type="submit" name="create_playlist" onclick=playaudio()>Δημιουργια Playlist</button>
        </form>
    </div>
    <div class="section">
        <h3>Existing Playlists</h3>
        <?php if($lists): ?>
            <ul>
                <?php foreach($lists as $l): ?>
                    <li><a href="#" onclick="showSection('view'); loadPlaylist(<?=$l['id']?>)"><?=htmlspecialchars($l['name'])?></a> (<?=htmlspecialchars($l['created_at'] ?? '')?>)
                        <!-- Form Διαγραφής -->
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete playlist;');">
                            <input type="hidden" name="playlist_id_delete" value="<?=$l['id']?>">
                            <button class="deleteplaylist" type="submit" name="delete_playlist" onclick=playaudio() style="margin-left:10px;">Διαγραφη playlist</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No playlists yet.</p>
        <?php endif; ?>
    </div>
</div>

<!-- View Playlist Items -->
<div id="view" class="section hide">
    <h2>Playlist Items</h2>
    <div id="items">
        <?php if(!empty($view_items)): ?>
            <?php foreach($view_items as $it): ?>
                <div class="video">
                    <iframe src="https://www.youtube.com/embed/<?=htmlspecialchars($it['youtube_id'])?>" frameborder="0" allowfullscreen></iframe>
                    <div class="video-info">
                        <strong><?=htmlspecialchars($it['title'])?></strong><br>
                        <small>Added at: <?=htmlspecialchars($it['added_at'])?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No videos in this playlist.</p>
        <?php endif; ?>
    </div>
</div>
<audio id="popsound" src="audio/pop.mp3" preload="auto"></audio>
<script>
    function playaudio(){
        document.getElementById('popsound').play();
    }
    function showSection(sec){
        ['search','playlists','view'].forEach(id=>{
            document.getElementById(id).classList.add('hide');
        });
        document.getElementById(sec).classList.remove('hide');
    }
    function loadPlaylist(pid){
        window.location.href = 'main.php?show=view&pid='+pid;
    }
    // Προεπιλογή based on GET
    showSection('<?=($_GET['show']??'search')?>');
</script>
</body>
</html>
