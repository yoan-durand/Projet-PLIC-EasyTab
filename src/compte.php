<?php
require_once 'inc/init.php';
global $user;
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    if ($password == $confirmPassword) {
        $bdd = Bdd::get();
        $req = $bdd->prepare('update user set password = ? where id = ?');
        $req->execute(array(md5($user->pseudo.$config['salt'].$password), $user->id));
    }
}

$pseudo = $user->pseudo;
$dateInscription = date("d/m/Y", $user->dateInscription);

include 'inc/head.php';
?>
<link rel="stylesheet" href="css/compte.css">
<script src="js/jquery-1.7.1.js"></script>
<script type="text/javascript">
    $(function(){
        $('#infos .item').click(function(){
            $('input', this).focus();
        });
    });
</script>
<?php
include 'inc/header.php';
?>
<div id="infos" class="center">
    <form method="post">
        <div class="item">
            <div class="left">Nom d'utilisateur</div>
            <div class="right"><?php echo $pseudo; ?></div>
        </div>
        <div class="item">
            <div class="left">Date d'inscription</div>
            <div class="right"><?php echo $dateInscription; ?></div>
        </div>
        <div class="item">
            <div class="left">Nouveau mot de passe</div>
            <div class="right"><input name="password" placeholder="password" type="password" ></div>
        </div>
        <div class="item">
            <div class="left">Confirmation du mot de passe</div>
            <div class="right"><input name="confirmPassword" placeholder="password" type="password" ></div>
        </div>
        <div class="item">
            <input type="submit" class="right gros-bouton">
        </div>
    </form>
</div>
<?php
include 'inc/footer.php';