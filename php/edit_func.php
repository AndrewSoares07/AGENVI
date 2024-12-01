<?php
// Incluir proteção e conexão com o banco de dados
include("protect.php");
include("includes/logar-sistema.php");

// Conexão com o banco de dados (substitua com suas credenciais)
$con = mysqli_connect('localhost', 'root', '', 'agenvi');

// Captura o ID do funcionário da URL
$idfuncionario = $_GET['id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter dados do formulário
    $idfuncionario = $_POST['idfuncionario'];
    $nome = $_POST['nome_func'];
    $email = $_POST['email'];
    $cel = $_POST['cel'];
    $cpf = $_POST['cpf'];
    $dt_nasc = $_POST['dt_nasc'];
    $idservico = isset($_POST['idservico']) ? $_POST['idservico'] : null;

    // Atualizar dados do funcionário na tabela 'funcionario'
    $sql_update_funcionario = "UPDATE funcionario SET nome_func = '$nome', email = '$email', cel = '$cel', cpf = '$cpf', dt_nasc = '$dt_nasc' WHERE idfuncionario = $idfuncionario";
    $con->query($sql_update_funcionario);

    // Deletar horários existentes do funcionário na tabela 'horario_func' e inserir os novos
    $sql_delete_horarios = "DELETE FROM horario_func WHERE idfuncionario = $idfuncionario";
    $con->query($sql_delete_horarios);

    // Obter dias de semana e horários do formulário
    $dias_semana = isset($_POST['dia_semana']) ? $_POST['dia_semana'] : [];
    $horario_ini = isset($_POST['horario_ini']) ? $_POST['horario_ini'] : [];
    $horario_fim = isset($_POST['horario_fim']) ? $_POST['horario_fim'] : [];

    // Inserir novos horários na tabela 'horario_func'
    foreach ($dias_semana as $dia) {
        if (in_array($dia, $dias_funcionamento_empresa)) { // Verificar se o dia está nos dias de funcionamento da empresa
            $inicio = $horario_ini[$dia];
            $fim = $horario_fim[$dia];
            $sql_insert_horarios = "INSERT INTO horario_func (idfuncionario, dia_semana, horario_ini, horario_fim) VALUES ($idfuncionario, '$dia', '$inicio', '$fim')";
            $con->query($sql_insert_horarios);
        }
    }

    // Deletar serviços anteriores do funcionário na tabela 'servicos_funcionario'
    $sql_delete_servicos = "DELETE FROM servicos_funcionario WHERE idfuncionario = $idfuncionario";
    $con->query($sql_delete_servicos);

    // Verificar se há serviços selecionados para inserir na tabela 'servicos_funcionario'
    if (!empty($idservico)) {
        if (!is_array($idservico)) {
            $idservico = [$idservico];
        }
        // Inserir novos serviços selecionados
        foreach ($idservico as $servico_id) {
            $sql_insert_servico_funcionario = "INSERT INTO servicos_funcionario (idfuncionario, idservico) VALUES ($idfuncionario, $servico_id)";
            $con->query($sql_insert_servico_funcionario);
        }
    }

    // Redirecionar para a página de lista de funcionários após a atualização
    header('Location: lista_func.php');
    exit();
} else {
    // Consulta para obter dados do funcionário
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


    $servicos_selecionados = [];
    if ($result_servicos_funcionario->num_rows > 0) {
        while ($row = $result_servicos_funcionario->fetch_assoc()) {
            $servicos_selecionados[] = $row['idservico'];
        }
    }

  
    $query_servicos = "SELECT s.idservico, s.nome_serv 
                       FROM servicos s
                       INNER JOIN lista_servicos_empresa lse ON s.idservico = lse.idservico
                       WHERE lse.idempresa = {$_SESSION['idempresa']}";
    $result_servicos = $con->query($query_servicos);

    $servicos_disponiveis = [];
    if ($result_servicos->num_rows > 0) {
        while ($row = $result_servicos->fetch_assoc()) {
            $servicos_disponiveis[$row['idservico']] = $row['nome_serv'];
        }
    }

    $sql_horarios_empresa = "SELECT dias_semana, horario_ini, horario_fim FROM horarios_empresa WHERE idempresa = {$_SESSION['idempresa']}";
    $result_horarios_empresa = $con->query($sql_horarios_empresa);


    $dias_funcionamento_empresa = [];
    $horarios_empresa = [];
    if ($result_horarios_empresa->num_rows > 0) {
        while ($row = $result_horarios_empresa->fetch_assoc()) {
            $dias_funcionamento_empresa[] = $row['dias_semana'];
            $horarios_empresa[$row['dias_semana']] = [
                'horario_ini' => $row['horario_ini'],
                'horario_fim' => $row['horario_fim']
            ];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/edit-func.css">
    <title>Agenvi</title>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }
        body{
            background-color: #E4E9F7;
        }
        .img{
            width: 180px;
            height: 180px;
            margin: auto;
            border-radius: 100px;
            background-image:url('arquivos/<?php echo htmlspecialchars($row_funcionario['foto_func']); ?>');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body>

<?php include("includes/navbar.php"); ?>

<section class="home-section">

    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Editar Funcionários</span>
    </div><br>

    <div class="editfunc">
       
        <form action="atualizar_funcionario.php" method="POST" enctype="multipart/form-data">
            <div class="pai">
                <div class="div1">
                    <h4>Dados</h4>
                    <hr>
                    <p style="color: gray; margin-left:20px;">clique na foto abaixo para altera-la</p>
                    <input type="hidden" name="idfuncionario" value="<?php echo $idfuncionario; ?>">
                    <figure class="img" id="figure-img">
                     <input type="file" id="foto_func" name="foto_func">
                     </figure>
        
                        <div>
                            <label>Nome:</label>
                            <input type="text" name="nome_func" class="inp2" value="<?php echo htmlspecialchars($row_funcionario['nome_func']); ?>" required><br>
                        </div>
                        <div>
                            <label>E-mail:</label>
                            <input type="email" name="email" class="inp2" value="<?php echo htmlspecialchars($row_funcionario['email']); ?>"><br>
                        </div>
                  
                  
                        <div class="lado">
                            <div>
                                <label>Celular:</label>
                                <input type="text" maxlength="11" name="cel" class="inp3" value="<?php echo htmlspecialchars($row_funcionario['cel']); ?>"><br>
                            </div>
                            <div>
                                <label>CPF:</label>
                                <input type="text" maxlength="11" name="cpf" class="inp3" value="<?php echo htmlspecialchars($row_funcionario['cpf']); ?>"><br>
                            </div>
                        </div>
                   
                        <div>
                            <label>Data de Nascimento:</label>
                            <input type="date" name="dt_nasc" class="inp2" value="<?php echo htmlspecialchars($row_funcionario['dt_nasc']); ?>"><br>
                        </div>
                        <div>
                            <label>Serviço:</label>
                            <?php foreach ($servicos_disponiveis as $idservico => $nome_servico) : ?>
                                <div>
                                    <input type="checkbox" name="idservico[]" value="<?php echo $idservico; ?>" <?php echo (in_array($idservico, $servicos_selecionados)) ? 'checked' : ''; ?>>
                                    <?php echo $nome_servico; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                   
                </div>
                <div class="div2">
                    <h4>Horários de Trabalho</h4>
                    <hr>
                    <?php
                                    // Parte do PHP que define os dias da semana e horários de funcionamento da empresa
                                    $dias_semana_nomes = [
                                        'domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'
                                    ];

                                    // Obtenha os horários de trabalho do funcionário do banco de dados
                                    $sql_horarios_funcionario = "SELECT dia_semana, horario_ini, horario_fim FROM horario_func WHERE idfuncionario = $idfuncionario";
                                    $result_horarios_funcionario = $con->query($sql_horarios_funcionario);

                                    $horarios_funcionario = [];
                                    if ($result_horarios_funcionario->num_rows > 0) {
                                        while ($row = $result_horarios_funcionario->fetch_assoc()) {
                                            $horarios_funcionario[$row['dia_semana']] = [
                                                'horario_ini' => $row['horario_ini'],
                                                'horario_fim' => $row['horario_fim']
                                            ];
                                        }
                                    }

                            // Parte do HTML do formulário para os horários
                            ?>
                            <div class="form-group">
                              
                                <?php foreach ($dias_funcionamento_empresa as $dia): ?>
                                    <?php
                                    $horario_ini_empresa = isset($horarios_empresa[$dia]['horario_ini']) ? $horarios_empresa[$dia]['horario_ini'] : '08:00';
                                    $horario_fim_empresa = isset($horarios_empresa[$dia]['horario_fim']) ? $horarios_empresa[$dia]['horario_fim'] : '18:00';
                                    $is_working = isset($horarios_funcionario[$dia]);
                                    $horario_ini_funcionario = $is_working ? $horarios_funcionario[$dia]['horario_ini'] : $horario_ini_empresa;
                                    $horario_fim_funcionario = $is_working ? $horarios_funcionario[$dia]['horario_fim'] : $horario_fim_empresa;
                                    ?>
                                    <div class="form-check form-check-inline">
                                        <div class="lad">
                                            <input type="checkbox" class="form-check-input" id="dia_semana_<?php echo $dia; ?>" name="dias_semana[]" value="<?php echo $dia; ?>" <?php echo $is_working ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="dia_semana_<?php echo $dia; ?>"><?php echo ucfirst($dia); ?>:</label>
                                        </div>
                                        <input type="time" class="form-control" id="horario_ini_<?php echo $dia; ?>" name="horario_ini[<?php echo $dia; ?>]" value="<?php echo $horario_ini_funcionario; ?>" min="<?php echo $horario_ini_empresa; ?>" max="<?php echo $horario_fim_empresa; ?>" required>
                                        <input type="time" class="form-control" id="horario_fim_<?php echo $dia; ?>" name="horario_fim[<?php echo $dia; ?>]" value="<?php echo $horario_fim_funcionario; ?>" min="<?php echo $horario_ini_empresa; ?>" max="<?php echo $horario_fim_empresa; ?>" required>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                           
             </div>

            </div>
            <input type="submit" value="Atualizar Funcionário" class="button">
        </form>
    </div>
    <br>
    
</section>

<script>
     // Seleciona o input de arquivo e a imagem
     document.getElementById('foto_func').addEventListener('change', function(event) {
    const file = event.target.files[0]; // Pega o primeiro arquivo selecionado
    const figure = document.getElementById('figure-img');
    
    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            // Define o fundo da <figure> com a imagem selecionada
            figure.style.backgroundImage = `url(${e.target.result})`;
            figure.style.backgroundSize = 'cover'; // Ajusta o tamanho da imagem de fundo
            figure.style.backgroundPosition = 'center'; // Centraliza a imagem
        };

        reader.readAsDataURL(file); // Lê o arquivo como uma URL de dados
    }
});


    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e)=>{
            let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
            arrowParent.classList.toggle("showMenu");
        });
    }
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", ()=>{
        sidebar.classList.toggle("close");
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>


