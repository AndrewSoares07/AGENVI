<?php
include("protect.php");
include("includes/logar-sistema.php");

$con = mysqli_connect('localhost', 'root', '', 'agenvi');

$idfuncionario = $_GET['id']; // Captura o ID do funcionário da URL

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idfuncionario = $_POST['idfuncionario'];
    $nome = $_POST['nome_func'];
    $email = $_POST['email'];
    $cel = $_POST['cel'];
    $cpf = $_POST['cpf'];
    $dt_nasc = $_POST['dt_nasc'];
    $idservico = isset($_POST['idservico']) ? $_POST['idservico'] : null;

   
    $sql_update_funcionario = "UPDATE funcionario SET nome_func = '$nome', email = '$email', cel = '$cel', cpf = '$cpf', dt_nasc = '$dt_nasc' WHERE idfuncionario = $idfuncionario";
    if ($con->query($sql_update_funcionario) === TRUE) {
        echo "Dados do funcionário atualizados com sucesso.<br>";
    } else {
        echo "Erro ao atualizar os dados do funcionário: " . $con->error . "<br>";
    }


    $sql_delete_horarios = "DELETE FROM horario_func WHERE idfuncionario = $idfuncionario";
    if ($con->query($sql_delete_horarios) === TRUE) {
        echo "Horários antigos deletados com sucesso.<br>";
    } else {
        echo "Erro ao deletar os horários antigos: " . $con->error . "<br>";
    }

    $dias_semana = isset($_POST['dias_semana']) ? $_POST['dias_semana'] : [];
    $horario_ini = isset($_POST['horario_ini']) ? $_POST['horario_ini'] : [];
    $horario_fim = isset($_POST['horario_fim']) ? $_POST['horario_fim'] : [];

    foreach ($dias_semana as $dia) {
        $inicio = $horario_ini[$dia];
        $fim = $horario_fim[$dia];
        $sql_insert_horarios = "INSERT INTO horario_func (idfuncionario, dia_semana, horario_ini, horario_fim) VALUES ($idfuncionario, '$dia', '$inicio', '$fim')";
        if ($con->query($sql_insert_horarios) === TRUE) {
            echo "Horário para $dia inserido com sucesso.<br>";
        } else {
            echo "Erro ao inserir horário para $dia: " . $con->error . "<br>";
        }
    }

    // Deletar serviços anteriores do funcionário
    $sql_delete_servicos = "DELETE FROM servicos_funcionario WHERE idfuncionario = $idfuncionario";
    if ($con->query($sql_delete_servicos) === TRUE) {
        echo "Serviços antigos deletados com sucesso.<br>";
    } else {
        echo "Erro ao deletar os serviços antigos: " . $con->error . "<br>";
    }

    // Verificar se há serviços selecionados para inserir na tabela 'servicos_funcionario'
    if (!empty($idservico)) {
        if (!is_array($idservico)) {
            $idservico = [$idservico];
        }
        // Inserir os novos serviços selecionados
        foreach ($idservico as $servico_id) {
            $sql_insert_servico_funcionario = "INSERT INTO servicos_funcionario (idfuncionario, idservico) VALUES ($idfuncionario, $servico_id)";
            if ($con->query($sql_insert_servico_funcionario) === TRUE) {
                echo "Serviço $servico_id inserido com sucesso.<br>";
            } else {
                echo "Erro ao inserir serviço $servico_id: " . $con->error . "<br>";
            }
        }
    }


 if (isset($_FILES['foto_func']) && $_FILES['foto_func']['error'] === UPLOAD_ERR_OK) {
                    $imagem = $_FILES['foto_func'];
                    var_dump($imagem);

                    if ($imagem['error'] === UPLOAD_ERR_OK) {
                        $extensao = pathinfo($imagem['name'], PATHINFO_EXTENSION);
                        $nome_arquivo_novo = 'foto-func-' . $idfuncionario . '.' . $extensao;
                        $destino = 'arquivos/' . $nome_arquivo_novo;

                        if (move_uploaded_file($imagem['tmp_name'], $destino)) {
                            
                            $sql = "UPDATE funcionario SET foto_func = ? WHERE idfuncionario = ?";
                            $stmt = $mysqli->prepare($sql);
                            $stmt->bind_param("si", $nome_arquivo_novo, $idfuncionario);

                            if ($stmt->execute()) {
                                echo "Upload e registro no banco de dados concluídos com sucesso.";
                            } else {
                                echo "Erro ao salvar o nome da imagem no banco de dados: " . $stmt->error;
                            }
                        } else {
                            echo "Erro ao mover o arquivo para o destino.";
                        }
                    } else {
                        echo "Erro no upload do arquivo: " . $imagem['error'];
                    }
                } else {
                    echo "Arquivo não enviado ou ocorreu um erro no upload.";
                   
                }

    header('Location: lista_func.php');
    exit();












} else {
    // Consulta para obter os dados do funcionário
    $sql_funcionario = "SELECT * FROM funcionario WHERE idfuncionario = $idfuncionario";
    $result_funcionario = $con->query($sql_funcionario);
    if ($result_funcionario->num_rows > 0) {
        $row_funcionario = $result_funcionario->fetch_assoc();
    } else {
        echo "<p>Funcionário não encontrado.</p>";
        exit();
    }

    // Consulta para obter os serviços associados ao funcionário
    $sql_servicos_funcionario = "SELECT sf.idservico FROM servicos_funcionario sf WHERE sf.idfuncionario = $idfuncionario";
    $result_servicos_funcionario = $con->query($sql_servicos_funcionario);

    // Array para armazenar os IDs dos serviços associados
    $servicos_selecionados = [];
    if ($result_servicos_funcionario->num_rows > 0) {
        while ($row = $result_servicos_funcionario->fetch_assoc()) {
            $servicos_selecionados[] = $row['idservico'];
        }
    }

    // Consulta para obter os serviços disponíveis
    $query_servicos = "SELECT s.idservico, s.nome_serv 
                       FROM servicos s
                       INNER JOIN lista_servicos_empresa lse ON s.idservico = lse.idservico
                       WHERE lse.idempresa = {$_SESSION['idempresa']}";
    $result_servicos = $con->query($query_servicos);

    // Array para armazenar os serviços disponíveis
    $servicos_disponiveis = [];
    if ($result_servicos->num_rows > 0) {
        while ($row = $result_servicos->fetch_assoc()) {
            $servicos_disponiveis[$row['idservico']] = $row['nome_serv'];
        }
    }
}
?>
