<?php
// auth.php
session_start();

if (!isset($_SESSION['username']) || trim($_SESSION['username']) === '') {
    header("Location: ../login.php");
    exit();
}
