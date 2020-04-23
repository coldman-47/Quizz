<?php
$questionnaires=json_decode(file_get_contents('questions.json'),true);
$questions = $questionnaires['question']; $type = $questionnaires['type']; $reponse = $questionnaires['reponses']; $nombre = $questionnaires['nombre'];
$npage = ceil(sizeof($questions)/$nombre);
if(!isset($_GET['page'])){
    $page = 1;
}else{
    $page = $_GET['page'];
}
$min = ($page-1)*$nombre; $max = $min + $nombre;
if($page <= 1){
    $page = 1;
    $prev = 'none';
}elseif($page>$npage){
    $page = $npage;
}
if($page == $npage){
    $max = sizeof($questions);
    $next = 'none';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joueurs</title>
</head>
<link rel="stylesheet" href="index.css">
<style>
    a{
        text-decoration: none;
    }
    .prev{
        display: <?= $prev ?>;
        align-self: flex-start;
    }
    .next{
        display: <?= $next ?>;
        margin-left:auto
    }
    #qst{
        border: solid 2px wheat;
        border-radius: 1em;
        width: 100%;
        font-weight: bold;
        padding: 1rem
    }
    #qst h3{
        color:grey;
        margin: 0
    }
    ul{
        margin: 0
    }
    #nq{
        padding: 0;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        font-size: 1.5em;
    }
    #nq input{
        padding: 0;
        height: 2.5rem;
        margin-left: 1rem;
        width: 2.5em;
        font-size: 1em;
        border-radius: 0
    }
</style>
<body>
    <form method="post" id="nq">
        <label for="">Nbre de question/Jeu</label>
        <input name="nq" value="<?= $nombre ?>">
        <input name="valid" type="submit" value="Ok" style="color: white; background-color:royalblue">
    </form>
    <div id="qst">
        <?php
        for($cpt=$min; $cpt<$max; $cpt++){
        ?>
        <h3><?= ($cpt+1).". ".$questions[$cpt] ?></h3>
        <?php
        if(in_array($type[$cpt],['qcm','radio'])){
            if($type[$cpt]=='qcm'){
                $ul = "url('Imgs/Icônes/square.png')";
            }else{
                $ul = "url('Imgs/Icônes/circle.png')";
            }
            echo '<ul style="list-style-image:'.$ul.'">';
            for($ct = 0; isset($reponse[$cpt][$ct]);$ct++){
                ?>
                <li><?= $reponse[$cpt][$ct] ?></li>
                <?php
            }
            echo '</ul>';
        }
        else{
            ?>
                <input disabled style="padding:.25rem; box-shadow:none">
            <?php
        }
        }
        ?>
        <div style="display: flex; width:100%;">
            <a class="prev" href="index.php?page=<?= $page-1 ?>"><button>Précédent</button> </a><a class="next" href="index.php?page=<?= $page+=1?>"><button>Suivant</button></a>
        </div>
    </div>
</body>
</html>