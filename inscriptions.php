<?php
$error = '';$classError = '';
$pnom = '';$nom = '';$log = '';$pwd = '';$cpwd = '';$img = '';
$users = json_decode(file_get_contents('users.json'),true);
$admin = $users['admin'];
$joueurs = $users['joueurs'];

function inscrire($user,$role, $pnom, $nom,$log,$pwd,$avatar){
    $user[$role]['pnom'][] = $pnom;
    $user[$role]['nom'][] = $nom;
    $user[$role]['login'][] = $log;
    $user[$role]['password'][] = $pwd;
    $user[$role]['avatar'][] = $avatar;
    return $user;
}

if(isset($_POST['signup'])){
     $pnom = $_POST['pnom'];
     $nom = $_POST['nom'];
     $log = $_POST['log'];
     $pwd = $_POST['pwd'];
     $cpwd = $_POST['cpwd'];
    if(!empty($pnom && $nom && $log && $pwd && $cpwd && $_FILES['avatar']['name'])){
        if(!is_numeric($pnom) && !is_numeric($nom)){
            if(!in_array($log,array_merge($joueurs['login'],$admin['login']))){
                if($pwd == $cpwd){
                    $img= $_FILES['avatar'];
                    $rev = explode('.', strrev($img['name']));  $ftype = explode("/",$img['type']);

                    $extension = strrev($rev[0]);
                    if(in_array(strtolower($extension),['jpeg','jpg','png'])){
                        $type = $ftype[0];
                        $size = $img['size'];
                        $ferror = $img['error'];
                        if($ferror == 0){
                            if($type == 'image'){
                                if($size < 20000000){
                                    $avatar = 'avatar/'.$log.'.'.$extension;
                                    $upload = move_uploaded_file($img['tmp_name'],$avatar);
                                    if($upload)
                                    {
                                        if(isset($_SESSION['admin'])){
                                            $users = inscrire($users,'admin', $pnom, $nom,$log,$pwd,$avatar);
                                            file_put_contents('users.json',json_encode($users,JSON_PRETTY_PRINT));
                                        }else{
                                            $users = inscrire($users,'joueurs', $pnom, $nom,$log,$pwd,$avatar);
                                            $users['joueurs']['score'][] = 0;
                                            file_put_contents('users.json',json_encode($users,JSON_PRETTY_PRINT));
                                            unset($_SESSION['signup']);
                                        }
                                        header('location:index.php');
                                    }
                                }
                                else{
                                    $error='Fichier trop volumineux. Maximum 2Mo';  $classError = 'error';
                                }
                            }
                            else{
                                $error = 'Format incorrect';  $classError = 'error';
                            }

                        }
                        else{
                            $error = "Erreur lors de la soumission de l'image";  $classError = 'error';
                        }
                    }else{
                        $error = "Seules les extensions <b>JPEG, JPG</b> et <b>PNG</b> sont autorisées! ";  $classError = 'error';
                    }
                }else{
                    $error = 'Echec lors de la confirmation du mot de passe!'; $classError = 'error'; $pwd = ''; $cpwd = '';
                }
            }
            else{
                $error = 'Login déjà éxistant!'; $classError = 'error'; $log = '';
            }
        }else{
            $error = 'Le prénom et le nom ne doivent pas être numériques!'; $classError = 'error';
        }
    }else{
        $error = 'Aucun champs ne doit être laissé vide!'; $classError = 'error';
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
    <form id="signin" method="post" enctype="multipart/form-data">
        <div class="cote">
            <h1>S'INSCRIRE</h1>
            <p>Pour tester votre niveau de culture générale</p>
            <hr style="position:relative;">
            <div class="group">
                <label>Prénom</label>
                <input value="<?= $pnom ?>" placeholder="Aaaaa" name="pnom">
            </div>
            <div class="group">
                <label>Nom</label>
                <input value="<?= $nom ?>" placeholder="BBBB" name="nom">
            </div>
            <div class="group">
                <label>Login</label>
                <input value="<?= $log ?>" placeholder="aabaab" name="log">
            </div>
            <div class="group">
                <label>Password</label>
                <input value="<?= $pwd ?>" placeholder=".........." type="password" name="pwd">
            </div>
            <div class="group">
                <label>Confirmer Password</label>
                <input value="<?= $cpwd ?>" placeholder=".........." type="password" name="cpwd">
            </div>
            <div class="group">
                <input onchange="readURL(this)" id="file" type="file" name="avatar">
            </div>
            <div class="group">
                <input style="width: 12rem !important" class="btn" value="Créer compte" type="submit" name="signup">
            </div>
            <p class="<?= $classError ?>"><?= $error ?></p>
        </div>
        <div class="cote2">
            <img src="" alt="" id="avatar">
        </div>
    </form>
<script>
function readURL(input) {
  if (input.files && input.files[0]) {
    var imgUploaded = new FileReader();

    imgUploaded.onload = function(e) {
      document.getElementById('avatar').src = e.target.result;
    }

    imgUploaded.readAsDataURL(input.files[0]);
  }
}

</script>