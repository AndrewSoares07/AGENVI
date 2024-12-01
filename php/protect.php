<?php

if(!isset($_SESSION)){
    session_start();
}
if(!isset($_SESSION['idempresa'])){
    die("Você não pode acessar essa página pois não esta logado <p><a href=\"conecte-se.php\">entraar</a></p> ");
}
?>

