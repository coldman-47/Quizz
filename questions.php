<?php
$questionnaires=json_decode(file_get_contents('questions.json'),true);
$questions = $questionnaires['question']; $type = $questionnaires['type']; $reponse = $questionnaires['reponses']; $nombre = $questionnaires['nombre']; $vraies = $questionnaires['vrai'];
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
foreach($_POST as $key => $val){
    if(preg_match('/^(del)/', $key)){
        $id = explode('-', $key)[1];
        $deleted = [];
        foreach($questionnaires as $cle => $content){
            if($cle=='nombre'){
            break;
            }
            $deleted[] = $content[$id];
            unset($questionnaires[$cle][$id]);
        }
        file_put_contents('questions.json', json_encode($questionnaires,JSON_PRETTY_PRINT));
        header('location:index.php');
    break;
    }
break;
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
    .set{
        padding: 0;
        width: 25%;
        margin: 0;
        background: none
    }
    .set input{
        background: none;
        box-shadow: none;
        padding:0;
        margin: 0;
        border:none;
        cursor:pointer;
        color:gray
    }
    .set input:hover{
        color:deepskyblue
    }
    h3{
        font-size:1.25em;
        text-align: justify;
        display: flex;
        flex-flow: row nowrap
    }
    h3:hover{
        background-color:wheat;
    }
    h3 div{
        width: 75%
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
        <h3><div><?= ($cpt+1).". ".$questions[$cpt] ?></div><form method="post" class="set"><input onclick="window.open('setQuestion.php?num=<?= $cpt ?>', '_blank');" type="submit" value="✎"><input class="del" name="del-<?= $cpt ?>" type="submit" value="🗑"></form></h3>
        <?php
        if(in_array($type[$cpt],['qcm','radio'])){
            if($type[$cpt]=='qcm'){
                $ul = "url('Imgs/Icônes/square.png')";
                $li = 'style="list-style-image:url'."('Imgs/Icônes/square-checked.png')".'"';
            }else{
                $ul = "url('Imgs/Icônes/circle.png')";
                $li = 'style="list-style-image:url'."('Imgs/Icônes/circle-checked.png')".'"';
            }
            echo '<ul style="list-style-image:'.$ul.'">';
            for($ct = 0; isset($reponse[$cpt][$ct]);$ct++){
                if(in_array($reponse[$cpt][$ct], $vraies[$cpt])){
                ?>
                <li <?= $li ?>><?= $reponse[$cpt][$ct] ?></li>
                <?php
                }else{
                    ?>
                    <li><?= $reponse[$cpt][$ct] ?></li>
                    <?php
                    }
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
<script>
    let del = document.getElementsByClassName('del');
</script>
</html>