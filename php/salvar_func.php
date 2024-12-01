<?php
include("protect.php");
include("includes/logar-sistema.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $nome = $_POST['nome_func'];
    $email = $_POST['email'];
    $cel = $_POST['cell'];
    $cpf = $_POST['cpf'];
    $dt_nasc = $_POST['dt_nasc'];
    $servicos = isset($_POST['servicos']) ? $_POST['servicos'] : [];

   
    $verificar_email = "SELECT * FROM funcionario WHERE email = ?";
    $stmt = $mysqli->prepare($verificar_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado_ver = $stmt->get_result();

    if ($resultado_ver->num_rows > 0) {
        echo "Já existe um funcionário cadastrado com esse e-mail";
    } else {
       
        $sql = "INSERT INTO funcionario (nome_func, email, dt_nasc, cel, cpf, foto_func) VALUES (?, ?, ?, ?, ?, 'semimagem.png' )";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssss", $nome, $email, $dt_nasc, $cel, $cpf);
        $result = $stmt->execute();

        if ($result) {
            $id_funcionario = $mysqli->insert_id;
            $id_empresa = $_SESSION['idempresa'];

            
            $sql_lista = "INSERT INTO lista_funcionario_empresa (idempresa, idfuncionario) VALUES (?, ?)";
            $stmt = $mysqli->prepare($sql_lista);
            $stmt->bind_param("ii", $id_empresa, $id_funcionario);
            $result_lista = $stmt->execute();

            if ($result_lista) {
                echo "Funcionário cadastrado com sucesso.";

                
                if (isset($_FILES['foto_func']) && $_FILES['foto_func']['error'] === UPLOAD_ERR_OK) {
                    $imagem = $_FILES['foto_func'];
                    var_dump($imagem);

                    if ($imagem['error'] === UPLOAD_ERR_OK) {
                        $extensao = pathinfo($imagem['name'], PATHINFO_EXTENSION);
                        $nome_arquivo_novo = 'foto-func-' . $id_funcionario .'-'. $id_empresa . '.' . $extensao;
                        $destino = 'arquivos/' . $nome_arquivo_novo;

                        if (move_uploaded_file($imagem['tmp_name'], $destino)) {
                            
                            $sql = "UPDATE funcionario SET foto_func = ? WHERE idfuncionario = ?";
                            $stmt = $mysqli->prepare($sql);
                            $stmt->bind_param("si", $nome_arquivo_novo, $id_funcionario);

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
                    echo"oi";
                   
                }

                
                foreach ($servicos as $servico_id) {
                    $sql_servicos_funcionario = "INSERT INTO servicos_funcionario (idservico, idfuncionario) VALUES (?, ?)";
                    $stmt = $mysqli->prepare($sql_servicos_funcionario);
                    $stmt->bind_param("ii", $servico_id, $id_funcionario);
                    $result_servicos_funcionario = $stmt->execute();

                    if (!$result_servicos_funcionario) {
                        echo "Erro ao cadastrar o relacionamento de serviço: " . $stmt->error;
                    }
                }
                
                $days_of_week = isset($_POST['dia_semana']) ? $_POST['dia_semana'] : [];
                $start_times = isset($_POST['horario_ini']) ? $_POST['horario_ini'] : [];
                $end_times = isset($_POST['horario_fim']) ? $_POST['horario_fim'] : [];
                
                $success = true;
                
                foreach ($days_of_week as $day) {
                    $start_time = isset($start_times[$day]) ? $start_times[$day] : null;
                    $end_time = isset($end_times[$day]) ? $end_times[$day] : null;
                
                
                    $check_sql = "SELECT * FROM horario_func WHERE idfuncionario = '$id_funcionario' AND dia_semana = '$day'";
                    $check_result = $mysqli->query($check_sql);
                
                    if ($check_result->num_rows > 0) {
                
                    
                        continue;
                    }
                
                
                    if (!empty($start_time) && !empty($end_time)) {
                        $sql = "INSERT INTO `horario_func`(`idfuncionario`, `dia_semana`, `horario_ini`, `horario_fim`) VALUES ('$id_funcionario', '$day', '$start_time', '$end_time')";
                        if ($mysqli->query($sql) !== TRUE) {
                            $success = false;
                    
                        } else {
                           
                        }
                    }
                }
                
                if ($success) {
                    header("Location: lista_func.php");
                } else {
                   
                }


            } else {
            }
        } else {
            echo "Erro ao cadastrar o funcionário: " . $stmt->error;
        }
    }









    $stmt->close();
    $mysqli->close();
} else {
    echo "Método de requisição inválido.";
}
?>
