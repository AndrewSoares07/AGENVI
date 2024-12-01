<?php


include('includes/logar-sistema.php');
include('protect_cliente.php');

// $id = (int) $_GET['id'];
$idempresa = $_GET['idempresa'];


$idcliente = $_SESSION['idcliente'];

$idavaliacao = $_GET['idavaliacao'];

$sql = "DELETE FROM avaliacao
    WHERE idavaliacao=$idavaliacao";
if ($mysqli->query($sql) === TRUE) {
header("Location: agendamento.php?id=" . $idempresa);

} else {
echo "<script>alert('Erro ao salvar comentário: " . $conn->error . "');</script>";
}
