<?php
include("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agenvi";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$idempresa = $_SESSION['idempresa'];

// Buscar os dias e horários que a empresa trabalha
$sql_horarios_empresa = "SELECT dias_semana, horario_ini, horario_fim FROM horarios_empresa WHERE idempresa = $idempresa";
$result_horarios = $conn->query($sql_horarios_empresa);

$empresa_horarios = [];
if ($result_horarios->num_rows > 0) {
    while ($row = $result_horarios->fetch_assoc()) {
        $empresa_horarios[$row['dias_semana']] = [
            'horario_ini' => $row['horario_ini'],
            'horario_fim' => $row['horario_fim']
        ];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/style.css">
    <title>Agenvi</title>
</head>
<body>
<?php include("includes/navbar.php"); ?>

<section class="home-section">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Cadastro de funcionário - Horários</span>
    </div>
    <div class="horas">
        <form action="salvar_horario.php" method="POST">
            <h4>Funcionário:</h4>
            <select name="idfuncionario" required>
                <?php
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }
                $sql = "SELECT f.idFuncionario, f.nome_func FROM lista_funcionario_empresa lfe 
                        INNER JOIN funcionario f ON lfe.idFuncionario = f.idFuncionario 
                        WHERE lfe.idEmpresa = $idempresa 
                        ORDER BY f.idFuncionario DESC";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["idFuncionario"] . "'>" . $row["nome_func"] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum funcionário cadastrado</option>";
                }
                $conn->close();
                ?>
            </select><br><br>

            <?php
            $days_of_week = ["seg", "ter", "qua", "qui", "sex", "sab", "dom"];
            foreach ($days_of_week as $day) {
                $disabled = !array_key_exists($day, $empresa_horarios) ? "disabled" : "";
                $horario_ini = $disabled ? "" : $empresa_horarios[$day]['horario_ini'];
                $horario_fim = $disabled ? "" : $empresa_horarios[$day]['horario_fim'];
                
                echo "<div class='day-container'>";
                echo "<input type=\"checkbox\" name=\"dia_semana[]\" value=\"$day\" $disabled> $day";
                echo "<div class='hora'><div class='pt1'>
                      <label>Hora de Início:</label>
                      <input class='inp4' type=\"time\" name=\"horario_ini[$day]\" value=\"$horario_ini\" min=\"$horario_ini\" max=\"$horario_fim\" $disabled>
                      </div>";
                echo "<div class='pt1'>
                      <label>Hora de Término:</label>
                      <input class='inp4' type=\"time\" name=\"horario_fim[$day]\" value=\"$horario_fim\" min=\"$horario_ini\" max=\"$horario_fim\" $disabled>
                      </div></div>";
                echo "</div>";
            }
            ?>
            <input type="submit" value="Adicionar Horários" class="salvarf">
        </form>
    </div>

    <style>
        .inp4 {
            border: 1px solid var(--c08);
            width: 100px;
            height: 30px;
            padding: 5px;
            border-radius: 5px;
        }
        h4 {
            display: inline-block;
        }
        .hora {
            width: 560px;
            display: flex;
        }
        form {
            width: 500px;
        }
        .pt1 {
            margin: 0px 0px 40px 20px;
        }
        .salvarf {
            border: none;
            border-radius: 5px;
            background-color: #6023B0;
            color: white;
            width: 200px;
            height: 50px;
            display: block;
            margin: auto;
            margin-top: 30px;
        }
        .horas {
            display: inline-block;
        }
        select {
            width: 200px;
            border-radius: 2px;
            height: 40px;
            border: 1px solid #6023B0;
        }
        form {
            margin-left: 600px;
            display: block;
            width: 600px;
        }
        .day-container {
            margin-bottom: 20px;
        }
    </style>
</section>

<script>
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e) => {
            let arrowParent = e.target.parentElement.parentElement;
            arrowParent.classList.toggle("showMenu");
        });
    }
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
