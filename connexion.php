<?php
$error = '';$classError = '';
$users = json_decode(file_get_contents('users.json'),true);
$admin = $users['admin']; $joueurs = $users['joueurs'];
$usernames = array_merge($admin['login'],$joueurs['login']);
if(isset($_SESSION['admin'])){
    header('location:index.php');
}
function user($role,$login,$password){
    if(in_array($login,$role['login'])){
        $_SESSION['id'] = array_search($login,$role['login']);
        return($password == $role['password'][$_SESSION['id']]);
    }
}
if(isset($_POST['con'])){
    $log = $_POST['log'];
    $pwd = $_POST['pwd'];
    if(!empty($log && $pwd)){
        if(in_array($log,$usernames)){
            if(user($admin,$log,$pwd) || user($joueurs,$log,$pwd)){
                if(user($admin,$log,$pwd)){
                    $_SESSION['admin'] = ["pnom" => $admin['pnom'][$_SESSION['id']], "nom" => $admin['nom'][$_SESSION['id']], "login" => $admin['login'][$_SESSION['id']], "password" => $admin['password'][$_SESSION['id']], "avatar" => $admin['avatar'][$_SESSION['id']]];
                    header('location:index.php');
                }else{
                    $_SESSION['joueur'] = ["pnom" => $joueurs['pnom'][$_SESSION['id']], "nom" => $joueurs['nom'][$_SESSION['id']], "login" => $joueurs['login'][$_SESSION['id']], "password" => $joueurs['password'][$_SESSION['id']], "avatar" => $joueurs['avatar'][$_SESSION['id']], "score" =>$joueurs['score'][$_SESSION['id']], "trouve" =>$joueurs['trouve'][$_SESSION['id']]];
                    $questionnaires=json_decode(file_get_contents('questions.json'),true);
                    $nombre = $questionnaires['nombre']; $questions = $questionnaires['question'];
                    $idqst = []; $connect = true;
                    $unplayed = 0;
                    foreach($questions as $key => $val){
                        if(!in_array($key, $_SESSION['joueur']['trouve'])){
                            $unplayed++;
                        }
                    }
                    if($unplayed >= $nombre){
                        for($i=0;$i<$nombre;$i++){
                            $cpt = 0; $rmax = sizeof($questions)-1;
                            $random = rand(0,$rmax);
                            while((in_array($random,$idqst) || in_array($random,$_SESSION['joueur']['trouve']))){
                                $random = rand(0,$rmax);
                            }
                            $idqst[] = $random;
                        }
                        $_SESSION['idqst'] = $idqst;
                        $_SESSION['repondu'] = $_SESSION['point'] = $_SESSION['trouve'] = [];
                        header('location:index.php');
                    }
                    else{
                        unset($_SESSION['joueur']);
                        $error = 'Connexion momentannément indisponible!'; $classError = 'error';
                    }
                }
            }else{
                $error = 'Mot de passe incorrect'; $classError = 'error';
            }
        }else{
            $error = 'Utilisateur non éxistant'; $classError = 'error';
        }
    }else{
        $error = 'Aucun champs ne doit être laissé vide!'; $classError = 'error';
    }
}
if(isset($_POST['signup'])){
    $_SESSION['signup'] = true;
    header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion</title>
</head>
<link rel="stylesheet" href="connexion.css">
<style>
    html,body{
        height: 100%
    }
    input[type="submit"]{
        cursor: pointer;
    }
</style>
<body>
<div id="doc" style="height:100%">
    <?php
    require('header.php')
    ?>
    <div class="conteneur">
        <div style="width: 50%">
            <div style="width:100%; margin:0 auto; padding:1rem; background-color:deepskyblue; color:white">
                <h3>Login Form</h3>
            </div>
            <form action="index.php" method="post">
                <input name="log" placeholder="Login" style="background-image:url('imgs/Icônes/ic-login.png')">
                <input name="pwd" placeholder="Password" type="password"  style="background-image:url('imgs/Icônes/ic-password.png')">
                <div>
                    <input name="con" class="btn" type="submit" value="Connexion">
                    <input name="signup" style="background:none !important; border:none; color:gray; width:auto !important" class="btn" type="submit" value="S'inscrire pour jouer?">
                </div>
                <p class="<?= $classError ?>"><?= $error ?></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>