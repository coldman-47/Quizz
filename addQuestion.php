<?php
$questionnaires=json_decode(file_get_contents('questions.json'),true);

$error = '';$classError = '';
const ERROR = 'error';
function notNull($array){
    foreach($array as $key => $val){
        return !empty($val);
    }
}
if(isset($_POST['save'])){
    $enonce = $_POST['enonce'];
    $score = $_POST['points'];
    $type = $_POST['type'];
    $reponses = []; $vraies=[];
    foreach($_POST as $key => $val){
        if(preg_match('/^(rep)/', $key)){
            $reponses[] = $val;
            $id = explode('-',$key);
            if(isset($_POST["cb".$id[1]])){
                $vraies[] = $val;
            }
        }
    }
    if(!empty($enonce && $score && $type) && notNull($reponses)){
        if(in_array($type,['qcm','radio','text'])){
            if(sizeof($vraies) > 0){
                if($type == 'qcm' || (in_array($type,['radio','text']) && sizeof($vraies) == 1)){
                    $questionnaires['question'][] = $enonce;
                    $questionnaires['score'][] = (int)$score;
                    $questionnaires['type'][] = $type;
                    $questionnaires['reponses'][] = $reponses;
                    $questionnaires['vrai'][] = $vraies;
                    file_put_contents('questions.json', json_encode($questionnaires,JSON_PRETTY_PRINT));
                }else{
                    $error = 'La réponse doit être unique pour les questions de type radio ou texte'; $classError = ERROR;
                }
            }else{
                $error = 'Vous devez au moins marqué une réponse comme vraie'; $classError = ERROR;
            }
        }else{
            $error = 'Type de question invalide'; $classError = ERROR;
        }
    }else{
        $error = 'Aucun Champs ne doit être laissé vide'; $classError = ERROR;
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
</head>
<style>
    #questions{
        width: 100%;
        padding:2rem;
        background-color:white;
        font-size:1.5rem
    }
    #formQst{
        display: flex;
        flex-direction: column;
        border: solid 2px deepskyblue
    }
    #formQst div{
        display: flex;
        align-items: center;
        padding: .125em;
    }
    #formQst div input,
    #formQst div textarea,
    #formQst div select
    {
        box-shadow: none;
        padding: .5em;
        border-radius: 0;
        background-color:whitesmoke;
        border: solid 1px deepskyblue;
        border-width: 0 1px 1px 0;
        color:grey;
        font-weight: lighter;
        font-size: 1em;
        resize: none;
    }
    #reponses{
        flex-direction: column;
        align-items: start !important;
    }
    .del{
        background:none !important;
        border: none !important;
        font-weight: bolder  !important;
        font-size: 1.5rem  !important;
        color:deepskyblue  !important;
        cursor: pointer;
    }
    label{
        margin-right:.5rem
    }
    #pts{
        max-width: 5rem;
    }
    #plus{
        background-color: deepskyblue !important;
        color : white !important;
        font-weight: bolder !important;
        font-size: 1.5rem !important;
        cursor: pointer;
    }
</style>
<body>
<div id="questions">
    <h2 style="color:deepskyblue; margin-top:-0.5em">Paramétrez votre question</h2>
    <form id="formQst" method="post">
        <div>
            <label for="">Questions</label>
            <textarea name="enonce"></textarea>
        </div>
        <div>
            <label for="">Nombre de points</label>
            <input min="1" id="pts" name="points" type="number">
        </div>
        <div>
            <label for="">Type de question</label>
            <select id="type" name="type">
                <option selected disabled>Donnez le type de réponse</option>
                <option value="qcm">Choix multiple</option>
                <option value="radio">Radio bouton</option>
                <option value="text">Champ texte</option>
            </select>
            <input id="plus" type="button" value="+">
        </div>
        <div id="reponses">
        </div>
        <div><button style="margin-left:0" name="save">Enregister</button></div>
    </form>
</div>
</body>
        <p class="<?= $classError ?>"><?= $error ?></p>
<script>

function required(){
var required = document.getElementsByClassName('requis'), n = required.length;

}
function reponse(){
let id = []; id.push(1);
    let x = document.getElementsByClassName('rep').length;
    let type = document.getElementById('type').value, reponses = document.getElementById('reponses');
    if(type == 'qcm' || type == 'radio'){
        if(x<5){
            reponses.innerHTML += '<div class="rep" id="rep'+(x+1)+'"><label for="">Reponse '+(x+1)+'</label><input name="rep-'+(x+1)+'" type="text"><input type="checkbox" name="cb'+(x+1)+'" id=""><input onClick="trash('+(x+1)+')" type="button" class="del" id="del'+(x+1)+'" value="🗑"></div>';
        }
    }else if(type == 'text'){
        reponses.innerHTML = '<div><label for="">Reponse</label><input name="rep" placeholder="Saisir la bonne réponse"></div>';
    }
}
document.getElementById('plus').addEventListener("click",reponse);
function trash(cpt){
    document.getElementById('rep'+cpt).remove();
}
</script>
</html>