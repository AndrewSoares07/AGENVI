<?php

if(!isset($_SESSION)){
    session_start();
}
if(!isset($_SESSION['idcliente'])){
    die("VOCE NÃO ESTA PODE ACESSAR ESSA PAGINA POIS VOCÊ NÃO ESTÁ LOGADO <p><a href=\"login-empresa.php\">entrar</a></p> ");
}
?>