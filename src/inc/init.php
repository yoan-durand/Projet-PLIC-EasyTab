<?php

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

require_once 'User.php';
$user = unserialize($_SESSION['user']);

require_once 'Bdd.php';