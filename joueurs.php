<?php
$_SESSION['id'] = [];
const N = 15;
$joueurs = json_decode(file_get_contents('users.json'),true)['joueurs'];
$prev = ''; $next='';
if(!isset($_GET['page'])){
    $page = 1;
}else{
    $page = $_GET['page'];
}
arsort($joueurs['score']);
$score = $joueurs['score'];
foreach($score as $key => $val){
    $_SESSION['id'][] = $key;
}
$npage = ceil(sizeof($_SESSION['id'])/N);
$min = ($page-1)*N; $max = $min + N;
if($page <= 1){
    $page = 1;
    $prev = 'none';
}elseif($page>$npage){
    $page = $npage;
}
if($page == $npage){
    $max = sizeof($_SESSION['id']);
    $next = 'none';
}
?>
<!DOCTYPE html>
<html lang="fr">
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
    table{
        border: solid 2px deepskyblue;
        border-radius: 1em;
        width: 100%;
        font-size: 1.5em
    }
    th{
        font-style: italic;
    }
    th,td{
        color: grey;
        padding: .25em 1em;
        text-align: left
    }
    .prev{
        display: <?= $prev ?>;
    }
    .next{
        display: <?= $next ?>;
        margin-left:auto
    }
</style>
<body>
        <h2 style="color: grey">Liste des joueurs par score</h2>
        <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Score</th>
            </tr>
            <?php
            for($cpt = $min; $cpt<$max; $cpt++){
            ?>
            <tr>
                <td style="text-transform:uppercase"><?= $joueurs['nom'][$_SESSION['id'][$cpt]] ?></td>
                <td><?= $joueurs['pnom'][$_SESSION['id'][$cpt]] ?></td>
                <td><?= $score[$_SESSION['id'][$cpt]] ?> pts</td>
            </tr>
            <?php } ?>
        </table>
        <div style="display: flex; width:100%;">
            <a class="prev" href="index.php?page=<?= $page-1 ?>"><button>Précédent</button> </a><a class="next" href="index.php?page=<?= $page+=1?>"><button>Suivant</button></a>
        </div>
</body>
</html>