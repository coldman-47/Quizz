<?php
session_start();
if(isset($_SESSION['joueur'])){
    require_once('jeux.php');
}elseif(isset($_SESSION['admin'])){
    require_once('admin.php');
}else{
    require_once('connexion.php');
}
?>