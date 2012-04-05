<?php

require_once 'inc/config.php';
require_once 'inc/Bdd.php';

global $config;
$bdd = new PDO($config['bdd driver'] . ':host=' . $config['bdd host'], $config['bdd user'], $config['bdd pass']);
$bdd->query('drop database if exists ' . $config['bdd dbname']);
$bdd->query('create database ' . $config['bdd dbname']);
$bdd = null;

$bdd = Bdd::get();

$requetes = "";

$sql = file($config['bdd sqlfile']); // on charge le fichier SQL
foreach ($sql as $l) { // on le lit
    if (substr(trim($l), 0, 2) != "--") { // suppression des commentaires
        $requetes .= $l;
    }
}

$reqs = explode(';', $requetes); // on sépare les requêtes
foreach ($reqs as $req) { // et on les éxécute
    if (trim($req) != '') {
        $q = $bdd->query($req);
        if ($q === false) {
            var_dump($req);
            var_dump($bdd->errorCode());
            var_dump($bdd->errorInfo());
        }
    }
}
?>
<a href=".">back</a>