<?php 
include('includes/logar-sistema.php');
$idempresa = $_GET['id'];

$drop_empresa = "DELETE from empresa where idempresa = $idempresa";
$sql_query = mysqli_query($mysqli, $drop_empresa);
if($sql_query){
    header('location:adm_principal.php');
}

?>