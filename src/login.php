<?php
session_start();
session_destroy();
session_start();

require_once 'inc/Bdd.php';
require_once 'inc/User.php';

if (isset($_POST['login'])) {
    $login = mysql_real_escape_string($_POST['login']);
    $password = md5($_POST['login'].$config['salt'].$_POST['password']);
    $bdd = Bdd::get();
    $statement = $bdd->prepare("select `id`, `login`, `password`, `date inscription` from `user` where `login` = ? limit 1");
    if ($statement->execute(array($login)) === false) {
        header('Location: login.php');
        die();
    }
    $row = $statement->fetch();
    if ($password == $row['password']) {
        $_SESSION['user'] = serialize(new User($row['id'], $row['login'], $row['date inscription']));
        header('Location: .');
        die();
    }
}

include 'inc/head.php';
?>
<link rel="stylesheet" href="css/login.css">
<?php
include 'inc/header.php';
?>
<div id="container" class="center">
    <form method="post">
        Nom d'utilisateur : <input name="login" placeholder="pseudo"><br>
        Mot de passe : <input name="password" type="password" placeholder="password"><br>
        <input type="submit">
    </form>
</div>
<?php
include 'inc/footer.php';