<?php
$users = json_decode(file_get_contents('users.json'),true);
$joueurs = $users['joueurs'];
arsort($joueurs['score']);
$suivant = 'Suivant'; $over = 0;
if(isset($_SESSION['idqst'])){
    $idqst = $_SESSION['idqst'];
}
$questionnaires=json_decode(file_get_contents('questions.json'),true);
$questions = $questionnaires['question']; $type = $questionnaires['type']; $reponse = $questionnaires['reponses'];
$nombre = $questionnaires['nombre']; $point = $questionnaires['score']; $vraies = $questionnaires['vrai'];
if(!isset($_SESSION['joueur'])){
    header('location:connexion.php');
}
$joueur = $_SESSION['joueur'];
if(isset($_POST['decon'])){
    session_destroy();
    header('location:connexion.php');
}
if(isset($_POST['next']) || isset($_POST['prev'])){
    $id = $_SESSION['num'];
    if(isset($_POST['next'])){
        if($_POST['next'] == 0){
            if($id <= $nombre-1){
                if(in_array($type[$idqst[$id]],['text','radio'])){
                    var_dump($type[$idqst[$id]]);
                    if(!empty($_POST['rep'])){
                        $repondu = $_POST['rep'];
                        if($repondu==$vraies[$idqst[$id]][0]){
                            $_SESSION['repondu'][] = $idqst[$id]; $_SESSION['point'][] = $point[$idqst[$id]];
                        }else{
                            $_SESSION['repondu'][] = $idqst[$id]; $_SESSION['point'][] = 0;
                        }
                        $_SESSION['num']+=1;
                        $id = $_SESSION['num'];
                    }
                }else{
                    $repondu = [];
                    foreach($_POST as $key => $val){
                        if(preg_match('/^(rep)/', $key)){
                            $repondu[] = $val;
                        }
                    }
                    if(!empty($repondu)){
                        var_dump($repondu);
                        $_SESSION['num']+=1;
                        foreach($repondu as $key => $val){
                            if(!in_array($val,$vraies[$idqst[$id]])){
                                $_SESSION['repondu'][] = $idqst[$id]; $_SESSION['point'][] = 0;
                            }else{
                                $_SESSION['repondu'][] = $idqst[$id]; $_SESSION['point'][] = $point[$idqst[$id]];
                            }
                        }
                    }
                }
            }
            if($id == $nombre-1){
                $suivant = 'Terminer';
            }
        }else{
            $over = 1;
            $new = array_sum($_SESSION['point']);
            unset($_POST,$_SESSION['point'],$_SESSION['idqst']);
            $joueur['score'] += $new;
            $users['joueurs']['score'][$_SESSION['id']] += $new;
            file_put_contents('users.json',json_encode($users,JSON_PRETTY_PRINT));
        }
    }else{
        if($id>=1){
            unset($_SESSION['repondu'][array_key_last($_SESSION['repondu'])]);
            unset($_SESSION['point'][array_key_last($_SESSION['point'])]);
            $_SESSION['num']--;
        }
    }
}
elseif(!isset($_SESSION['num'])){
    $_SESSION['num'] = 0;
    $id = $_SESSION['num'];
}
else{
    $id = $_SESSION['num'];
}

    if($id == 0){
        $prev = 'none';
    }
    if(isset($_GET['top'])){
        $top = 'active';
        $myscore = '';
    }else{
        $myscore = 'active';
        $top = '';
    }

if(isset($_GET['replay'])){
    $idqst = [];
    for($i=0;$i<$nombre;$i++){
        $random = rand(0,sizeof($questions)-1);
        while(in_array($random,$idqst)){
            $random = rand(0,sizeof($questions)-1);
        }
        $idqst[] = $random;
    }
    $_SESSION['idqst'] = $idqst;
    $_SESSION['repondu'] = $_SESSION['point'] = [];
    $_SESSION['num'] = 0;
    $id = $_SESSION['num'];
    header('location:index.php');
}
?>
<!DOCTYPE html$page>
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
    background-color: grey;
    background-image: none;
    margin-right: auto
}
button a{
    text-decoration:none;
    color:white
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
            <img src="<?= $joueur['avatar'] ?>" alt="" style="height: 5rem; width:5rem; border-radius:100%; border: solid 4px white; margin:auto">
            <h3><?= $joueur['pnom'].' '.$joueur['nom'] ?></h3>
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
        <?php
        if(!$over){
            if($suivant=='Terminer'){
                $over = 1;
            }
        ?>
            <div id="title">
                <h1>Question <?= ($id+1)." / ".$nombre ?>:</h1>
                <h2 style="font-weight: lighter"><?= $questions[$idqst[$id]] ?></h2>
            </div>
            <div id="questions">
                <section><?= $point[$idqst[$id]] ?> pts</section>
                <div>
                <form method="POST" style="display: flex; flex-direction:column; color:black">
                <?php
                if(in_array($type[$idqst[$id]],['qcm','radio'])){
                    if($type[$idqst[$id]] == 'qcm'){
                        $iptype='checkbox';
                        for($i = 0; $i<sizeof($reponse[$idqst[$id]]); $i++){
                            ?>
                            <label for="rep<?= $i ?>">
                                <input value="<?= $reponse[$idqst[$id]][$i] ?>" type="<?= $iptype ?>" name="rep<?= $i ?>" id="rep<?= $i ?>">
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
                                <input value="<?= $reponse[$idqst[$id]][$i] ?>" type="<?= $iptype ?>" name="rep" id="rep<?= $i ?>">
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
                <div style="display: flex; width:100%;">
                    <button class="prev" name="prev">Précédent</button>
                    <button class="next" value="<?= $over ?>" name="next"><?= $suivant ?></button>
                </div>
                </form>
                </div>
            </div>
        <?php }
        else{
        ?>
        <div id="over">
            <h1>GAME OVER!</h1>
            <h2>Nouveau Score : +<?= $new ?> pts</h2>
            <div><button style="background-color:grey; background-image:none"><a href="index.php?replay">Rejouer ?</a></button></div>
        </div>
        <?php } ?>
        </div>
        <div style="width: 40%; display:flex;align-items:center; padding: 1.5rem; flex-direction:column">
            <div id="rubrique">
                <a href="index.php?top" class="<?= $top ?>">Top Scores</a>
                <a href="index.php?mysc" class="<?= $myscore ?>">Mon meilleur score</a>
            </div>
            <?php
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
                    <h2 style="color:deepskyblue; text-align:center"><?= $joueur['score'] ?> points</h2>
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