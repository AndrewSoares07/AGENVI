<?php
include("protect.php");

include("includes/logar-sistema.php");

include("includes/informaçoes_empresa.php");
$idempresa = $_SESSION['idempresa'];





$sql = "SELECT a.dt_agendamento, COUNT(a.idagendamento) as total_agendamentos, a.status, e.idempresa
        FROM agendamento a 
        inner join empresa e on e.idempresa = a.idempresa
        WHERE e.idempresa = $idempresa and a.status = 'em andamento'
        GROUP BY a.dt_agendamento";

$result = mysqli_query($mysqli, $sql);

$eventos = [];



while ($row = mysqli_fetch_assoc($result)) {
 

    $eventos[] = [
        'date' => $row['dt_agendamento'],
        'title' => $row['total_agendamentos'],
        'classNames' => ['event-andamento']
    ];
}

date_default_timezone_set('America/Sao_Paulo');

$data_atual = date('d/m/y'); 
?>



<!DOCTYPE html>
<html lang="pt-br" dir="ltr">

<head>
    <meta charset="UTF-8">
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="../css/style.css">

    <link rel="stylesheet" href="../css/calendario.css">
    <link rel="stylesheet" href="../css/agendamento_emp.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <script src='https://unpkg.com/@fullcalendar/daygrid@5.11.3/main.min.js'></script>
    <script src='https://unpkg.com/@fullcalendar/pt-br@5.11.3/locale/pt-br.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<style>

    .chart-container {
        width: 250px;
        margin-left: 20px;
       
    }
    .bar-container {
        width: 100%;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
       
    }
    .bar-label {
        font-size: 14px;
       
        color: black;
        width: 80px; /* largura para a coluna do nome */
     
    }
    .bar-background {
        width: 80%;
        height: 20px;
        background-color: #D3D3D3; /* Cor de fundo cinza */
        border-radius: 15px;
        position: relative;
    }
    .bar {
        height: 20px;
        font-size: 12px;
        font-weight: bold;
        padding: 2px;
        padding-left: 5px;
        background-color: #7C5AFF; /* Cor da barra roxa */
        border-radius: 15px;
        color: #D3D3D3;
        position: absolute;
        top: 0;
        left: 0;
        transition: width 0.3s ease;
    }

</style>

<body>



    <?php include("includes/navbar.php"); ?>



    <section class="home-section">

    <div class="home-content">
            <div>
                <i class='bx bx-menu'></i>
                <span class="text">Agendamentos</span>
            </div>
            <a class="bt1" href="../php/relatorio/relatorio/gerar_relatorio.php">Relatorio de clientes</a>
        </div>
        <hr>



       
        <div class="papito1">
        <div class="agend_t">
            <div class="text_p">
            <div class="sep"></div>
    <p id="dataHoje" class="datahoje"><?php echo "Hoje - $data_atual"; ?></p>
    <div class="sep"></div>
</div>

        
        <div id="tudo"></div>
        </div>  


            <div class="colunainfo">
                
            <div id="calendar"></div>
            <br>
                <div class="infoagen">
                <?php
                $agendamentos_td = "SELECT COUNT(a.idagendamento) as agendamentos_td, a.status
            FROM agendamento a
            WHERE a.idempresa = $idempresa";
                $sql_td = mysqli_query($mysqli, $agendamentos_td);
                if ($sql_td) {
                    $std = mysqli_fetch_assoc($sql_td);
                    $todos_ag = $std['agendamentos_td']; // todos os agendamentos;
                }

                $agendamentos_fn = "SELECT COUNT(a.idagendamento) as agendamentos_fn, a.status
            FROM agendamento a
            WHERE a.idempresa = $idempresa and a.status = 'finalizado'";
                $sql_tf = mysqli_query($mysqli, $agendamentos_fn);
                if ($sql_tf) {
                    $stf = mysqli_fetch_assoc($sql_tf);
                    $todos_af = $stf['agendamentos_fn']; // todos os agendamentos como finalizado;
                }

                $agendamentos_c = "SELECT COUNT(a.idagendamento) as agendamentos_c, a.status
            FROM agendamento a
            WHERE a.idempresa = $idempresa and a.status = 'cancelado'";
                $sql_c = mysqli_query($mysqli, $agendamentos_c);
                if ($sql_c) {
                    $stc = mysqli_fetch_assoc($sql_c);
                    $todos_ac = $stc['agendamentos_c']; // todos os agendamentos como cancelado;
                }





                ?>
                <div class="ageninfo">
                    <p>
                         Agendamentos <br><strong><?php echo $todos_ag ?></strong>
                    </p>
                    <p>
                         Finalizados <br><strong><?php echo $todos_af ?></strong>/<?php echo $todos_ag ?></strong>
                    <p>
                        Cancelados <br><strong><?php echo $todos_ac ?></strong>/<?php echo $todos_ag ?>
                    </p>
                </div>
        </div>
  <br>


        <div class="funcs">
            <p>Funcionários mais escolhidos</p>

                <div class="funcs2">
                    <?php
                    // Conexão e consulta ao banco de dados
                    $func = "
                        SELECT f.nome_func, f.foto_func, COUNT(a.idagendamento) AS num_agendamentos
                        FROM agendamento a
                        INNER JOIN funcionario f ON f.idfuncionario = a.idfuncionario
                        WHERE a.idempresa = $idempresa
                        GROUP BY f.nome_func
                        ORDER BY num_agendamentos DESC
                        LIMIT 3;
                    ";
                    $sql_func = mysqli_query($mysqli, $func);
                    // Array para armazenar os dados do gráfico
                    $chartData = [];
                    $maxAgendamentos = 0;
                    while ($row = mysqli_fetch_assoc($sql_func)) {
                        $num_agendamentos = (int) $row['num_agendamentos'];
                        $chartData[] = [
                            'nome' => $row['nome_func'],
                            'num_agendamentos' => $num_agendamentos
                        ];
                    
                        // Atualiza o valor máximo de agendamentos
                        if ($num_agendamentos > $maxAgendamentos) {
                            $maxAgendamentos = $num_agendamentos;
                        }
                        // Verifica se o funcionário tem uma foto; caso contrário, usa a imagem padrão
                        $foto = $row['foto_func'] ? $row['foto_func'] : '../img/default.jpg';
                        // Exibe a foto do funcionário na div `ft_funcs` com tooltip de informações
                        echo "<div class='foto-funcionario' style='display: inline-block;'>
                            <img src='arquivos/$foto' alt='{$row['nome_func']}' title='{$row['nome_func']} - {$num_agendamentos} agendamentos' style='width: 50px; height: 50px; border-radius: 50%; margin-left:-15px; border:3px solid white;object-fit:cover; margin-top:15px;'>
                        </div>";
                    }
                    ?>
                    <div class="ft_funcs" style="display: flex;"></div>
                    <div class="chart-container">
                        <?php foreach ($chartData as $data): ?>
                            <?php
                                // Calcula a largura proporcional da barra em relação ao maior número de agendamentos
                                $widthPercent = ($data['num_agendamentos'] / $maxAgendamentos) * 100;
                            ?>
                            <div class="bar-container">
                                <div class="bar-label"><?php echo $data['nome']; ?></div>
                                <div class="bar-background">
                                    <div class="bar" style="width: <?php echo $widthPercent; ?>%;"><?php echo $data['num_agendamentos']; ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

        </div>
        <br>

                
            </div>

        </div>



    </section>





    <script>
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
      arrow[i].addEventListener("click", (e) => {
        let arrowParent = e.target.parentElement.parentElement; // Selecting main parent of arrow
        arrowParent.classList.toggle("showMenu");
      });
    }

    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    sidebarBtn.addEventListener("click", () => {
      sidebar.classList.toggle("close");
    });
        
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var empresaId = button.data('id'); 
        
      
        var modal = $(this);
        modal.find('#confirmDeleteBtn').attr('href', 'excluir_empresa.php?id=' + empresaId);
    });
  </script>


  <script>

    



    
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var divTudo = document.getElementById('tudo'); // Div onde os agendamentos serão exibidos

        // Cria um array de eventos a partir dos dados PHP
        var eventos = <?php echo json_encode($eventos); ?>;

        // Inicializa o calendário
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // Visão inicial: mês
            locale: 'pt-br', // Idioma português
            editable: false, // Não permite arrastar eventos
            selectable: false, // Não permite selecionar intervalos
            events: eventos, // Adiciona eventos ao calendário
            dateClick: async function (info) {
    try {
        const dataSelecionada = info.dateStr; // Data selecionada no formato YYYY-MM-DD

        // Formata a data no formato d/m/Y
        const [ano, mes, dia] = dataSelecionada.split('-');
        const dataFormatada = `${dia}/${mes}/${ano}`;

        // Atualiza o parágrafo com a data clicada
        const dataHoje = document.getElementById('dataHoje');
        if (dataHoje) {
            dataHoje.textContent = `Data selecionada - ${dataFormatada}`; // Define o texto como a data formatada
        }

        const url = `agendamentos_por_dia.php?data=${dataSelecionada}`;
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        });

        const result = await response.text();

        // Atualiza a div com os agendamentos
        divTudo.innerHTML = '';
        divTudo.innerHTML = result;

    } catch (error) {
        console.error('Erro ao obter agendamentos:', error);
    }
},


            eventClick: function (info) {
                alert('Evento: ' + info.event.title);
            },
            eventContent: function (info) {
                var customHtml = document.createElement('div');
                customHtml.className = 'event-box'; // Classe para estilização
                customHtml.innerHTML = info.event.title; // Título do evento
                return { domNodes: [customHtml] };
            },
            datesSet: async function (info) {
    var mesAtual = info.view.currentStart.getMonth() + 1; // Mês atual (1 a 12)
    var anoAtual = info.view.currentStart.getFullYear(); // Ano atual
    
    // Obtém a data atual
    const dataAtual = new Date();
    const diaAtual = String(dataAtual.getDate()).padStart(2, '0'); // Adiciona zero à esquerda se necessário
    const mesAtualFormatado = String(dataAtual.getMonth() + 1).padStart(2, '0'); // Mês com 2 dígitos
    const anoAtualFormatado = dataAtual.getFullYear();
    
    const dataFormatada = `${diaAtual}/${mesAtualFormatado}/${anoAtualFormatado}`;

    // Atualiza a exibição da data selecionada para a data atual
    const dataHoje = document.getElementById('dataHoje');
    if (dataHoje) {
        dataHoje.textContent = `Hoje - ${dataFormatada}`; // Define o texto como a data atual formatada
    }

    try {
        const url = `dados_agendamentos.php?mes=${mesAtual}&ano=${anoAtual}`;
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        });

        const result = await response.text();

        divTudo.innerHTML = '';
        divTudo.innerHTML = result;

    } catch (error) {
        console.error('Erro ao carregar dados do calendário:', error);
    }
},

        });

        calendar.render();
    });
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>