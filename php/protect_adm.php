<?php

if(!isset($_SESSION)){
    session_start();
}
if(!isset($_SESSION['id_adm'])){
    die("Você não pode acessar essa página pois não esta logado <p><a href=\"login-empresa.php\">entrar</a></p> ");
}
?>