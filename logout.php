<?php
    session_start();
    session_destroy();
    unset($_SESSION['username']);
    header("Location: home.php");
    exit();
?>