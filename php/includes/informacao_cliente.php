<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');
$idcliente = $_SESSION['idcliente'];

$sql_code = "SELECT * FROM cliente WHERE idcliente = $idcliente";
$sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

if ($sql_query->num_rows == 1) {
   
    $cliente = $sql_query->fetch_assoc();
    $nome_cliente = $cliente["nome_cliente"];
    $datan = $cliente["data_nasc"];
    $telefone = $cliente["telefone"];
    $email = $cliente['email'];
    $cep = $cliente["cep"];
    $genero = $cliente["genero"];
    $img_perfil = $cliente["foto_perfil"];
    $back = $cliente["foto_back"];
    

} 
  $data = $cliente["data_nasc"];
  $partesData = explode("-", $data);
  $dataInvertida = "{$partesData[2]}/{$partesData[1]}/{$partesData[0]}";
?>