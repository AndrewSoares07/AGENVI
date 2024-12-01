<?php
include("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");

$idempresa = $_SESSION['idempresa'];

// Buscar os dias e horários que a empresa trabalha
$sql_horarios_empresa = "SELECT * FROM horarios_empresa WHERE idempresa = $idempresa";
$result_horarios_empresa = $mysqli->query($sql_horarios_empresa);

// Array para armazenar os horários de funcionamento por dia da semana
$empresa_horarios = [];
if ($result_horarios_empresa->num_rows > 0) {
    while ($row = $result_horarios_empresa->fetch_assoc()) {
        $empresa_horarios[$row['dias_semana']] = [
            'horario_ini' => $row['horario_ini'],
            'horario_fim' => $row['horario_fim']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/horarios-emp.css">
    <title>Agenvi</title>
    <style>
        .disabled-day {
            background-color: #f0f0f0;
            color: #a0a0a0;
            cursor: not-allowed;
            pointer-events: none;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <?php include("includes/navbar.php"); ?>

    <section class="home-section">
        <div class="home-content">

            <i class='bx bx-menu'></i>
            <span class="text">Definir horários de funcionamento</span>
        </div><br><br>

        <div class="horas">
            <h3>Horários de Funcionamento</h3>
            <form action="editar_horariosemp.php" method="POST">

                <div class="horaimg">
                    <div>
                        <div class="horaimg">
                            <div>
                                <?php
                                $days_of_week = ["seg", "ter", "qua", "qui", "sex", "sab", "dom"];
                                foreach ($days_of_week as $day) {
                                    // Obtém os horários de início e fim para o dia atual
                                    $horario_ini = isset($empresa_horarios[$day]) ? $empresa_horarios[$day]['horario_ini'] : "";
                                    $horario_fim = isset($empresa_horarios[$day]) ? $empresa_horarios[$day]['horario_fim'] : "";

                                    // Verifica se o dia está habilitado
                                    $disabled_class = empty($horario_ini) ? 'disabled-day' : ''; 
                                    $icon_class = empty($horario_ini) ? 'bx-block' : 'bx-time'; 
                                    $icon_color = empty($horario_ini) ? '#a0a0a0' : 'purple'; 
                                    ?>
                                    <div class='day-container <?php echo $disabled_class; ?>'>
                                        <div class='divs'>
                                            <label>
                                                <?php echo ucfirst($day); ?>:
                                            </label>
                                        </div>
                                        <div class='hora'>
                                            <div class='pt1'>
                                                <div class="inp4">
                                                 
                                                    <?php echo !empty($horario_ini) ? date("H:i", strtotime($horario_ini)) : "00:00"; ?>
                                                  
                                                    <i class='bx <?php echo $icon_class; ?>'
                                                        style='color: <?php echo $icon_color; ?>; font-size: 20px;'></i>
                                                </div>
                                            </div>
                                            <p class="p">Às</p>
                                            <div class='pt1'>
                                                <div class="inp4">
                                                    
                                                    <?php echo !empty($horario_fim) ? date("H:i", strtotime($horario_fim)) : "00:00"; ?>
                                                    
                                                    <i class='bx <?php echo $icon_class; ?>'
                                                        style='color: <?php echo $icon_color; ?>; font-size: 20px;'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                            <div class="imghora">
                                <?php
                                include("includes/animacaohorario.php");
                                ?>
                            </div>
                        </div>




                    </div>
                </div>


                <input type="submit" value="Adicionar novo horário" class="button">
            </form>



        </div><br><br>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>