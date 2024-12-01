<?php

include('includes/logar-sistema.php');
include('protect_cliente.php');
include("includes/informacao_cliente.php");

$idcliente = $_SESSION['idcliente'];

$nome = $_POST['nome'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$email = $_POST['email'] ?? '';
$data_nasc = $_POST['data_nasc'] ?? '';

if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $arquivo = $_FILES['foto_perfil'];
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);

    $pasta_destino = 'arquivos/';
    $novo_nome_perfil = "perfil-$idcliente-" . time() . ".$extensao"; 
    $caminho_destino_perfil = $pasta_destino . $novo_nome_perfil;

    if (move_uploaded_file($arquivo['tmp_name'], $caminho_destino_perfil)) {
        $sql_update_perfil = "UPDATE cliente SET foto_perfil = ? WHERE idcliente = ?";
        $stmt_update_perfil = mysqli_prepare($mysqli, $sql_update_perfil);
        mysqli_stmt_bind_param($stmt_update_perfil, "si", $novo_nome_perfil, $idcliente);
        mysqli_stmt_execute($stmt_update_perfil);
    } else {
        echo "Erro ao fazer upload da imagem de perfil.";
    }
}

if (isset($_FILES['foto_back']) && $_FILES['foto_back']['error'] === UPLOAD_ERR_OK) {
    $arquivo = $_FILES['foto_back'];
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);

    $pasta_destino = 'arquivos/';
    $novo_nome_fundo = "fundo-$idcliente-" . time() . ".$extensao";
    $caminho_destino_fundo = $pasta_destino . $novo_nome_fundo;

    if (move_uploaded_file($arquivo['tmp_name'], $caminho_destino_fundo)) {
        $sql_update_fundo = "UPDATE cliente SET foto_back = ? WHERE idcliente = ?";
        $stmt_update_fundo = mysqli_prepare($mysqli, $sql_update_fundo);
        mysqli_stmt_bind_param($stmt_update_fundo, "si", $novo_nome_fundo, $idcliente);
        mysqli_stmt_execute($stmt_update_fundo);
    } else {
        echo "Erro ao fazer upload da imagem de fundo.";
    }
}

$sql = "UPDATE cliente SET nome_cliente = ?, data_nasc = ?, telefone = ?, email = ? WHERE idcliente = ?";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "ssssi", $nome, $data_nasc, $telefone, $email, $idcliente);

if (mysqli_stmt_execute($stmt)) {
    header('Location: perfil-cliente.php');
} else {
    echo "Erro ao atualizar o perfil. Tente novamente.";
}
?>
