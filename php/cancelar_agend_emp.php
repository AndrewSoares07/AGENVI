<?php
include ("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");

$idagendamento = $_GET['id'];


$edti = "UPDATE `agendamento` SET `status`= 'cancelado' WHERE idagendamento = $idagendamento";
$result = mysqli_query($mysqli, $edti);
if($result){
    header('location:agendamento_emp.php');
}
else{
    echo"erro ao mudar o status";
}


?>