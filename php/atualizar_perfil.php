<?php
include("protect.php");
include("includes/logar-sistema.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("includes/informaçoes_empresa.php");

    $idempresa = $_SESSION['idempresa'];
    
    $nome = $_POST['nome'];
    $nome_fantasia = $_POST['nome_fantasia'];
    $telefone = $_POST['telefone'];
    $cnpj_cpf = $_POST['cnpj_cpf'];
    $comple = $_POST['complemento'];
    $bairro = $_POST['bairro'];
    $num = $_POST['numero'];
    $cidade = $_POST['cidade'];
    $logradouro = $_POST['rua'];
    $uf = $_POST['uf'];
    $cep = $_POST['cep'];
    $num = $_POST['numero'];


mysqli_begin_transaction($mysqli);

try {
    
    $sql_check_cep = "SELECT cep FROM localidade WHERE cep = '$cep'";
    $result_cep = mysqli_query($mysqli, $sql_check_cep);
    
   
    if (mysqli_num_rows($result_cep) == 0) {
        $sql_insert_localidade = "INSERT INTO localidade (cep, bairro, UF, cidade, logradouro) 
                                  VALUES ('$cep', '$bairro', '$uf', '$cidade', '$logradouro')";
        mysqli_query($mysqli, $sql_insert_localidade);
    } else {
     
        $sql_update_localidade = "UPDATE localidade SET bairro = '$bairro', UF = '$uf', cidade = '$cidade', logradouro = '$logradouro' 
                                  WHERE cep = '$cep'";
        mysqli_query($mysqli, $sql_update_localidade);
    }

   
    $sql_update_empresa = "UPDATE empresa SET nome = '$nome', nome_fantasia = '$nome_fantasia', telefone = '$telefone', CNPJ_CPF = '$cnpj_cpf', complemento = '$comple', numero = '$num', cep = '$cep' WHERE idempresa = $idempresa";
    mysqli_query($mysqli, $sql_update_empresa);

    
    mysqli_commit($mysqli);
} catch (Exception $e) {
    mysqli_rollback($mysqli);
    echo "Falha ao atualizar: " . $e->getMessage();
}



    function uploadImage($input_name, $file_field, $prefix) {
        global $upload_dir, $idempresa, $mysqli;

        if ($_FILES[$input_name]['error'] == UPLOAD_ERR_OK) {
            $arquivo = $_FILES[$input_name];
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            
            $novo_nome_imagem = "$prefix-$idempresa.$extensao";
            $caminho_destino_imagem = $upload_dir . $novo_nome_imagem;

            if (move_uploaded_file($arquivo['tmp_name'], $caminho_destino_imagem)) {
                $sql_update_imagem = "UPDATE empresa SET $file_field = ? WHERE idempresa = ?";
                $stmt_update_imagem = mysqli_prepare($mysqli, $sql_update_imagem);
                mysqli_stmt_bind_param($stmt_update_imagem, "si", $novo_nome_imagem, $idempresa);
                mysqli_stmt_execute($stmt_update_imagem);
            } else {
                echo "Erro ao fazer upload da imagem.";
            }
        }
    }

    $upload_dir = 'arquivos/';
    
    // Upload da foto principal
    uploadImage('foto_empresa', 'foto_empresa', "perfil_emp");

    // Upload das fotos ambiente
    for ($i = 1; $i <= 6; $i++) {
        $input_name = "foto_amb$i";
        $file_field = "foto_amb$i";
        uploadImage($input_name, $file_field, "foto_amb$i");
    }

    header("Location: perfil-empresa.php");
    exit();
}
?>
