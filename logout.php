<?php
session_start();
session_destroy();
header("Location: streamplay.php");
exit;

