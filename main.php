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
$conn = new mysqli("mysql", "user", "userpass", "my_database");
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

$user = $_SESSION['user_email'];

// Δημιουργία νέας λίστας
if (isset($_POST['create_playlist'])) {
    $plist_name = $conn->real_escape_string($_POST['playlist_name']);
    $is_public  = isset($_POST['is_public']) ? 1 : 0;
    $stmt = $conn->prepare("INSERT INTO playlists (user_email,name,is_public) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $user, $plist_name, $is_public);
    $stmt->execute();
    $stmt->close();
}

// Προσθήκη βίντεο σε λίστα
if (isset($_POST['add_item'])) {
    $playlist_id = (int)$_POST['playlist_id'];
    $youtube_id  = $conn->real_escape_string($_POST['youtube_id']);
    $title       = $conn->real_escape_string($_POST['title']);
    $stmt = $conn->prepare("INSERT INTO playlist_items (playlist_id,youtube_id,title) VALUES (?,?,?)");
    $stmt->bind_param("iss", $playlist_id, $youtube_id, $title);
    $stmt->execute();
    $stmt->close();
}

// Φόρτωση λιστών χρήστη
$lists = [];
$res = $conn->query("SELECT id,name FROM playlists WHERE user_email='".$conn->real_escape_string($user)."'");
while($row = $res->fetch_assoc()) $lists[] = $row;

// Αναζήτηση YouTube
$search_results = [];
if (!empty($_GET['q'])) {
    $q = urlencode($_GET['q']);
    $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=5&q={$q}&key={$API_KEY}";
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    $search_results = $data['items'] ?? [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <title>Main Page</title>
    <style>
        body { font-family: sans-serif; background:#f0f0f0; margin:0; padding:20px; }
        .container { max-width:800px; margin:auto; }
        .section { background:#fff; padding:15px; margin-bottom:20px; border-radius:8px; }
        .section h2 { margin-top:0; }
        .video { display:flex; align-items:flex-start; margin-bottom:15px; }
        .video img, .video iframe { width:160px; height:90px; margin-right:10px; }
        .video-info { flex:1; }
        .video-info form { margin-top:10px; }
        select, input[type="text"] { padding:5px; font-size:14px; }
        button { padding:6px 12px; font-size:14px; cursor:pointer; }
        nav { text-align:right; margin-bottom:20px; }
        nav button { padding:6px 12px; }
    </style>
</head>
<body>
<div class="container">
    <!-- Navigation -->
    <nav>
        <button onclick="location.href='profile.php'">My Profile</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </nav>

    <!-- Δημιουργία Playlist -->
    <div class="section">
        <h2>Δημιουργία Playlist</h2>
        <form method="POST">
            <input type="text" name="playlist_name" placeholder="Όνομα Λίστας" required>
            <label><input type="checkbox" name="is_public"> Public</label>
            <button type="submit" name="create_playlist">Create</button>
        </form>
        <div>
            <h3>Οι λίστες σου:</h3>
            <ul>
                <?php foreach($lists as $l): ?>
                    <li><?= htmlspecialchars($l['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Αναζήτηση YouTube -->
    <div class="section">
        <h2>Αναζήτηση YouTube</h2>
        <form method="GET">
            <input type="text" name="q" placeholder="Πχ. Drift Cars" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>
            <button type="submit">Search</button>
        </form>

        <?php if($search_results): ?>
            <?php foreach($search_results as $vid):
                $vidId = $vid['id']['videoId'];
                $title = $vid['snippet']['title'];
                $thumb = $vid['snippet']['thumbnails']['default']['url'];
                ?>
                <div class="video">
                    <!-- Embed player -->
                    <iframe src="https://www.youtube.com/embed/<?= $vidId ?>" frameborder="0" allowfullscreen></iframe>
                    <div class="video-info">
                        <strong><?= htmlspecialchars($title) ?></strong>
                        <form method="POST">
                            <input type="hidden" name="youtube_id" value="<?= $vidId ?>">
                            <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">
                            <select name="playlist_id">
                                <?php foreach($lists as $l): ?>
                                    <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="add_item">Add to Playlist</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
