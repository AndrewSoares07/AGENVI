<?php
//$idempresa = $_GET['id'];

include('includes/logar-sistema.php');
include('protect_cliente.php');

$idempresa = $_POST['empresaid'];


$idcliente = $_SESSION['idcliente'];



$rating = $_POST['estrela'] ?? "";
$comentario = $_POST['comentario'] ?? "";
$data_atual = date('Y-m-d');


              if ($_SERVER["REQUEST_METHOD"] == "POST") {

                 // Receber os dados
                 $comentario = $mysqli->real_escape_string($_POST['comentario']);
                 $rating = (int)$_POST['rating'];

                 // Inserir no banco de dados
                 $sql = "INSERT INTO avaliacao VALUES (DEFAULT, $idcliente, $idempresa, $rating,'$comentario', '$data_atual')";
                 echo $sql;
                 if ($mysqli->query($sql) === TRUE) {
                     echo "<script>alert('Comentário salvo com sucesso!');</script>";
                     header("Location: agendamento.php?id=" . $idempresa);

                 } else {
                     echo "<script>alert('Erro ao salvar comentário: " . $conn->error . "');</script>";
                 }
             }
 ?>