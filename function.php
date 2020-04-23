<?php
function connexion($role,$login,$password){
    if(in_array($login,$role['login'])){
        $id = array_search($login,$role['login']);
        return($password == $role['password'][$id]);
    }
}
?>