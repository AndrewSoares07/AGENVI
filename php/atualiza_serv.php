<?php
include ("protect.php");
include("includes/logar-sistema.php");



$idservico = $_POST['idservico'];
$nome = $_POST['nome_serv'];
$preco = $_POST['preco_serv'];
$descricao = $_POST['descricao_serv'];
$duracao = $_POST['duracao_serv'];

$sql = "UPDATE servicos SET nome_serv ='$nome', preco_serv ='$preco',
descricao_serv ='$descricao', duracao_serv ='$duracao' WHERE `idservico` = $idservico";

$att = mysqli_query($mysqli, $sql);

if($att){
    header('location:lista_servicos.php');
}
else{
    echo"apaga e faz dnv";
}
?>