<?php
$mysqli = new mysqli('localhost','root','','agenvi');
if($mysqli->error){
    die("falha ao conectar". $mysqli->error);
}



?>