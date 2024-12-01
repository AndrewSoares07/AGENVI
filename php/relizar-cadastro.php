<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tela de Carregamento</title>
  <link rel="stylesheet" href="teste.css">
  <style>
    /* Estilos para centralizar a tela de carregamento */
    body, html {
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-color: #E2E2E2;
    }
    video{
    width: 100%;
    margin: auto;

}

  </style>
</head>
<body>
 
<video src="../img/videodecarregamento.mp4" autoplay loop muted></video>
</body>
</html>



<?php
session_start();
include_once('includes/conecta.php');

$mensagem_erro = ''; // Inicialize a variável

$imagem_empresa = $_SESSION['imagem_empresa'] ?? null; 
$imagePaths = $_SESSION['imagePaths'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $nome = $_POST["nome"] ?? "";
    $nomeE = $_POST["nomeE"] ?? "";
    $tel = $_POST["tel"] ?? "";
    $email = $_POST['email'] ?? null;
    $cnpj = $_POST["cnpj"] ?? "";
    $cep = $_POST["cep"] ?? "";
    $senha = $_POST['senha'] ?? null;
    $tipo = $_POST["tipo"] ?? "";
    $Vsenha = $_POST["Vsenha"] ?? null;
    $plano = $_POST["plano"] ?? "";
    $num = $_POST["num"] ?? "";
    $complemento = $_POST["comple"] ?? "";
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['uf'];
    $logradouro = $_POST['rua'];
    $data_atual = date('Y-m-d');


 
    $sql = "SELECT * FROM localidade WHERE cep = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $cep);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {

      

        $sql_insert = "INSERT INTO `localidade` (`cep`, `bairro`, `cidade`, `uf`, `logradouro`) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $con->prepare($sql_insert);
        $stmt_insert->bind_param("sssss", $cep, $bairro, $cidade, $estado, $logradouro);
        $stmt_insert->execute();
    }

    if (empty($email) || empty($senha)) {
        die("Email e senha são obrigatórios.");
    }
    
    $sqluser = $con->prepare("SELECT `email` FROM `empresa` WHERE `email` = ?");
    $sqluser->bind_param("s", $email);
    $sqluser->execute();
    $result = $sqluser->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('O email já existe.');</script>";
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `empresa`(`nome`, `nome_fantasia`, `telefone`, `email`, `CNPJ_CPF`, `cep`, `senha`, `tipo`, `numero`, `complemento`, `data_inicio`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssssssss", $nome, $nomeE, $tel, $email, $cnpj, $cep, $hash, $tipo, $num, $complemento, $data_atual);
        $stmt->execute();

        $codigo = $stmt->insert_id;

        $dias_semana = ['seg', 'ter', 'qua', 'qui', 'sex'];

        foreach ($dias_semana as $dia) {
            $horarios_sql = "INSERT INTO `horarios_empresa`(`idempresa`, `horario_ini`, `dias_semana`, `horario_fim`) 
                             VALUES ('$codigo', '07:00', '$dia', '18:00')";
            
            if ($con->query($horarios_sql) !== TRUE) {
                echo "Erro ao inserir horário para $dia: " . $con->error . "<br>";
            }
        }

        // Verificar se há imagem de empresa
        if (!empty($imagem_empresa)) {
            $ext = pathinfo($imagem_empresa, PATHINFO_EXTENSION);
            $nome_arquivo_novo = "empresa-" . $codigo . "." . $ext;
            $destino = "arquivos/" . $nome_arquivo_novo;  // Caminho completo para copiar o arquivo
            $caminho_origem = "../img/arquivo/" . $imagem_empresa;
            
            // Verifica se o arquivo de origem realmente existe
            if (file_exists($caminho_origem)) {
                if (copy($caminho_origem, $destino)) {
                    $sql_update = "UPDATE empresa SET foto_empresa = ? WHERE idempresa = ?";
                    $stmt_update = $con->prepare($sql_update);
                    $stmt_update->bind_param("si", $nome_arquivo_novo, $codigo);
                    $stmt_update->execute();
                } else {
                    echo "Erro ao copiar o arquivo.";
                }
            } else {
                echo "Arquivo de origem não encontrado.";
            }
        } else {
            // Caso não tenha enviado imagem, usa a imagem padrão
            $nome_arquivo_novo = "empresa-" . $codigo . ".png"; // Nome da imagem padrão
            $destino = "arquivos/" . $nome_arquivo_novo;  // Caminho para a imagem padrão
            $caminho_origem = "../img/arquivo/semimagem.png"; // Caminho da imagem padrão
            
            // Copia a imagem padrão para a nova pasta
            if (copy($caminho_origem, $destino)) {
                $sql_update = "UPDATE empresa SET foto_empresa = ? WHERE idempresa = ?";
                $stmt_update = $con->prepare($sql_update);
                $stmt_update->bind_param("si", $nome_arquivo_novo, $codigo);
                $stmt_update->execute();
            } else {
                echo "Erro ao copiar a imagem padrão.";
            }
        } 
        if($stmt_update){
            $imagem_defphalt = "back.png";

            $insert_port = "INSERT INTO `portifolio_empresa`(`idempresa`, `port_img1`, `port_img2`, `port_img3`, `port_img4`, `port_img5`, `port_img6`, `port_img7`, `port_img8`, `port_img9`, `port_img10`, `port_img11`, `port_img12`) VALUES ('$codigo','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt','$imagem_defphalt')";

            $execute_port = $con->prepare($insert_port);
            $execute_port->execute();
        }




        // Obter o total de imagens a serem processadas
        $totalImagens = 6; // Número total de imagens
        $imageFolder = "../img/arquivo/";

        for ($index = 0; $index < $totalImagens; $index++) {
            $pattern = $imageFolder . "foto_amb" . ($index + 1) . "_*.*";
            $imageFiles = glob($pattern);

            $fileExtension = "png"; // Extensão da imagem
            $newFileName = "foto_amb" . ($index + 1) . "-" . $codigo . '.' . $fileExtension; // Nome do arquivo específico
            $newFilePath = "arquivos/" . $newFileName; // Caminho completo para o arquivo copiado

            if (!empty($imageFiles)) {
                foreach ($imageFiles as $imagePath) {
                    $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $newFileName = "foto_amb" . ($index + 1) . "-" . $codigo . '.' . $fileExtension;
                    $newFilePath = "arquivos/" . $newFileName;

                    if (copy($imagePath, $newFilePath)) {
                        $columnName = "foto_amb" . ($index + 1);
                        $sql_update = "UPDATE empresa SET $columnName = ? WHERE idempresa = ?";
                        $stmt_update = $con->prepare($sql_update);
                        $stmt_update->bind_param("si", $newFileName, $codigo);
                        $stmt_update->execute();
                    } else {
                        $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Erro ao copiar o arquivo $imagePath para $newFilePath</p>";
                    }
                }
            } else {
                // Se não houver imagem, usar a imagem padrão (default)
                $defaultImagePath = "../img/arquivo/back.png"; // Caminho da imagem padrão

                if (copy($defaultImagePath, $newFilePath)) {
                    $columnName = "foto_amb" . ($index + 1);
                    $sql_update = "UPDATE empresa SET $columnName = ? WHERE idempresa = ?";
                    $stmt_update = $con->prepare($sql_update);
                    $stmt_update->bind_param("si", $newFileName, $codigo);
                    $stmt_update->execute();
                } else {
                    $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Erro ao copiar a imagem padrão para $newFilePath</p>";
                }
            }
        }

        $mensagem_erro = "<p class='foi'><i class='bx bx-error'></i>Cadastro concluído com sucesso!.</p>";
       
    }
}
include("includes/logar-sistema.php");

                    $mensagem_erro = ''; // Inicialize a variável

                    if (isset($_POST['email']) && isset($_POST['senha'])) {

                        if (empty($_POST['email'])) {
                            $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Preencha seu e-mail</p>";
                        } else if (empty($_POST['senha'])) {
                            $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Preencha sua senha</p>";
                        } else {
                            $email = $mysqli->real_escape_string($_POST['email']);
                            $senha = $_POST['senha'];
                    
                            $sql_code = "SELECT * FROM empresa WHERE email = '$email'";
                            $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
                    
                            if ($sql_query->num_rows == 1) {
                                $empresa = $sql_query->fetch_assoc();
                    
                                if (password_verify($senha, $empresa['senha'])) {
                                    // Start loading screen before redirection
                                    echo "<script>showLoadingScreen();</script>";
                                    
                                    // Store session data and redirect with a delay
                                    $_SESSION['idempresa'] = $empresa['idempresa'];
                                    $_SESSION['nome'] = $empresa['nome'];
                                    
                                    echo "<script>
                                            setTimeout(function() {
                                                window.location.href = 'principal-empresa.php';
                                            }, 9000); // Redirect after 2 seconds
                                          </script>";
                                    exit;
                                } else {
                                    $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Senha incorreta.</p>";
                                }
                            } else {
                                $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>E-mail não encontrado.</p>";
                            }
                        }
                    }
                    ?>
                    


