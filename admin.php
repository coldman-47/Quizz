<?php
if(!isset($_SESSION['admin'])){
    header('location:index.php');
}
if(isset($_POST['decon'])){
    session_destroy();
    header('location:index.php');
}
function active($name){
    if(isset($_POST[$name])){
        return '-active';
    }else{
        return '';
    }
}
if(isset($_POST['lj'])){
    $_SESSION['include'] = 'joueurs';
}elseif(isset($_POST['ca'])){
    $_SESSION['include'] = 'inscriptions';
}elseif(isset($_POST['lq'])){
    $_SESSION['include'] = 'questions';
}elseif(!isset($_SESSION['include'])){
    $_POST['lq'] = '-active';
    $_SESSION['include'] = 'questions';
}

if(!isset($_SESSION['lq'],$_SESSION['ca'],$_SESSION['lj'],$_SESSION['cq']) || !empty($_POST['lq']) || !empty($_POST['ca']) || !empty($_POST['lj']) || !empty($_POST['cq']) ){
    $_SESSION['lq'] = active('lq'); $_SESSION['ca'] = active('ca');
    $_SESSION['lj'] = active('lj'); $_SESSION['cq'] = active('cq');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizz</title>
    <link rel="stylesheet" href="index.css">
</head>
<style>
    #menu{
        width: 35%;
        height: 100%;
        padding: 1rem;
    }
    #page{
        width: 65%;
        height: 100%;
        padding: 1rem;
        background-color: white;
        box-shadow: -2px -2px 10px 1px rgba(128, 128, 128, 0.5);
        border-radius: .5rem
    }
    #menu form{
        box-shadow: -1px 10px 10px 1px rgba(128, 128, 128, 0.5);
        border-radius: 0 !important;
        padding: 0;
        z-index: 0;
    }
    .menu-ctrl{
        width: 100%;
        font-size: 1.25em;
        color:gray;
        font-weight: lighter;
        padding: 1rem;
        border:none;
        border-left: solid .25em white;
        background-color: white;
        text-align: left;
        background-repeat: no-repeat;
        background-position: 95%;
        cursor:pointer
    }
    .-active{
        border-color: green;
        background-color: beige
    }
    iframe{
        width: 100%;
        overflow: hidden;
    }
    #signin{
        padding:0;
    }
    #signin input{
        font-size: 1vw;
    }
    .cote2{
        padding:0
    }
</style>
<body>
<div id="doc" style="height:100%">
<?php
    require('header.php')
?>
    <div class="conteneur" style="padding: .5rem 1.5em">
    <div style="width:100%; padding:0 1rem; background-color:deepskyblue; color:white; display:flex; text-align:center">
        <div style="width: 15%;">
        </div>
        <div style="width: 70%; text-transform: uppercase; display:flex; align-items:center; justify-content:center">
        <h2>Créez et paramètrez vos quizz</h2>
        </div>
        <div style="width: 15%">
            <form  id="decon" method="post"  style="background-color: transparent !important; padding:1.5rem"><button name="decon">Déconnexion</button></form>
        </div>
    </div>
    <div id="body">
        <div id="menu">
            <div id="info">
                <img src="<?= $_SESSION['admin']['avatar'] ?>" alt="">
                <h2><?= $_SESSION['admin']['pnom'].'<br>'.$_SESSION['admin']['nom'] ?></h2>
            </div>
            <form method="post">
                <input name="lq" class="menu-ctrl lq <?= $_SESSION['lq'] ?>" type="submit" value="Liste  Questions" style="background-image: url('imgs/Icônes/ic-liste<?=$_SESSION['lq'] ?>.png')">
                <input name="ca" class="menu-ctrl ca <?= $_SESSION['ca'] ?>" type="submit" value="Créer Admin" style="background-image: url('imgs/Icônes/ic-ajout<?= $_SESSION['ca'] ?>.png')">
                <input name="lj" class="menu-ctrl lj <?= $_SESSION['lj'] ?>" type="submit" value="Liste  Joueurs" style="background-image: url('imgs/Icônes/ic-liste<?= $_SESSION['lj'] ?>.png')">
                <input name="cq" class="menu-ctrl cq <?= $_SESSION['cq'] ?>" type="submit" value="Créer Questions" style="background-image: url('imgs/Icônes/ic-ajout<?= $_SESSION['cq'] ?>.png')">
            </form>
        </div>
        <div id="page">
            <?php
            require_once($_SESSION['include'].".php");
            ?>
        </div>
    </div>
    </div>
</div>
</body>
</html>