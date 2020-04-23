<?php
session_start();
$idqst = $_SESSION['idqst'];
$questionnaires=json_decode(file_get_contents('questions.json'),true);
$questions = $questionnaires['question']; $type = $questionnaires['type']; $reponse = $questionnaires['reponses'];
$nombre = $questionnaires['nombre']; $point = $questionnaires['score']; $nr = $questionnaires['nr'];

if(!isset($_SESSION['joueur'])){
    header('location:connexion.php');
}
if(isset($_POST['decon'])){
    session_destroy();
    header('location:connexion.php');
}

if(!isset($_GET['page'])){
    $page = 1;
}else{
    $page = $_GET['page'];
}

if($page <= 1){
    $page = 1;
    $prev = 'none';
}elseif($page>$nombre){
    $page = $nombre;
}
if($page == $nombre){
    $max = $nombre;
    $next = 'none';
}
$id = $page-1;
if(isset($_GET['top'])){
    $top = 'active';
    $myscore = '';
}elseif(isset($_GET['mysc'])){
    $myscore = 'active';
    $top = '';
}
else{
    $top = 'active';
    $myscore = '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizz</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="jeux.css">
</head>
<style>
.prev {
    display: <?=$prev ?>;
}

.prev button {
    background-color: grey;
    background-image: none
}

.next {
    display: <?=$next ?>;
    margin-left: auto
}
</style>
<body>
<div id="doc" style="height:100%">
<?php
    require('header.php')
?>
    <div class="conteneur" style="padding: 1.5rem">
    <div style="width:100%; padding:0 1rem; background-color:deepskyblue; color:white; display:flex; text-align:center">
        <div style="width: 15%;">
            <img src="<?= $_SESSION['joueur']['avatar'] ?>" alt="" style="height: 5rem; width:5rem; border-radius:100%; border: solid 4px white; margin:auto">
            <h3><?= $_SESSION['joueur']['pnom'].' '.$_SESSION['joueur']['nom'] ?></h3>
        </div>
        <div style="width: 70%;line-height:2.5rem; padding:0 1rem">
        <h2>BIENVENUE SUR LA PLATEFORME DE JEU DE QUIZZ <br> JOUER ET TESTER VOTRE NIVEAU DE CULTURE GÉNÉRALE</h2>
        </div>
        <div style="width: 15%">
            <form method="post" style="background-color: transparent !important"><button name="decon">Déconnexion</button></form>
        </div>
    </div>
    <div id="body">
    <div>
        <div id="jeux">
            <div id="title">
                <h1>Question <?= ($id+1)." / ".$nombre ?>:</h1>
                <h2 style="font-weight: lighter"><?= $questions[$idqst[$id]] ?></h2>
            </div>
            <div id="questions">
                <section><?= $point[$idqst[$id]] ?> pts</section>
                <div>
                <form method="post" style="display: flex; flex-direction:column; color:black">
                <?php
                if(in_array($type[$idqst[$id]],['qcm','radio'])){
                    if($type[$idqst[$id]] == 'qcm'){
                        $iptype='checkbox';
                        for($i = 0; $i<sizeof($reponse[$idqst[$id]]); $i++){
                            ?>
                            <label for="rep<?= $i ?>">
                                <input type="<?= $iptype ?>" name="rep<?= $i ?>" id="rep<?= $i ?>">
                                <div class="<?= $iptype ?>">&#x2713; </div>
                                <?= $reponse[$idqst[$id]][$i] ?>
                            </label>
                            <?php
                        }
                    }else{
                        $iptype = 'radio';
                        for($i = 0; $i<sizeof($reponse[$idqst[$id]]); $i++){
                            ?>
                            <label for="rep<?= $i ?>">
                                <input type="<?= $iptype ?>" name="rep" id="rep<?= $i ?>">
                                <div class="<?= $iptype ?>"> &#8226; </div>
                                <?= $reponse[$idqst[$id]][$i] ?>
                            </label>
                            <?php
                        }
                    }
                }else{
                    echo '<input name="rep">';
                }
                ?>
                </form>
                </div>
                <div style="display: flex; width:100%;">
                    <a class="prev" href="jeux.php?page=<?= $page-1 ?>"><button>Précédent</button> </a>
                    <a class="next" href="jeux.php?page=<?= $page+=1?>"><button>Suivant</button></a>
                </div>
            </div>
        </div>
        <div style="width: 40%; display:flex;align-items:center; padding: 1.5rem; flex-direction:column">
            <div id="rubrique">
                <a href="jeux.php?top" class="<?= $top ?>">Top Scores</a>
                <a href="jeux.php?mysc" class="<?= $myscore ?>">Mon meilleur score</a>
            </div>
            <?php
            $joueurs = json_decode(file_get_contents('users.json'),true)['joueurs'];
            arsort($joueurs['score']);
            $score = $joueurs['score'];
            foreach($score as $key => $val){
                $playaz[] = $key;
            }
            ?>
            <div id="score">
                <?php
                if($top == 'active'){
                for($i=0;$i<5;$i++){
                    ?>
                    <div class="row">
                        <div class="side"><?= $joueurs['pnom'][$playaz[$i]].' '.$joueurs['nom'][$playaz[$i]] ?></div>
                        <div class="side"><?= $joueurs['score'][$playaz[$i]] ?> pts</div>
                    </div>
                <?php }
                }
                else{
                    ?>
                    <h2 style="color:deepskyblue; text-align:center"><?= $_SESSION['joueur']['score'] ?> points</h2>
                <?php }
                ?>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>
</body>
</html>