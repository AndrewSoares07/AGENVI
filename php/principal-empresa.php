<?php
include("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");
?>

<!DOCTYPE html>
<html lang="pt-br" dir="ltr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/faturamentos.css">

  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
  <title>Agenvi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <?php
  include("includes/navbar.php");
  ?>

  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu'></i>
      <span class="text">Faturamentos</span>
    </div>


    <div class="sep_div">
      <div class="ladoa">
        <div class="div1">
          <p>Dias da semana mais agendados</p>
          <?php
          $agenda = "SELECT dtsemana, COUNT(*) as count
                     FROM agendamento
                     WHERE idempresa = $idempresa
                     GROUP BY dtsemana
                     ORDER BY FIELD(dtsemana, 'seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom')";
          $query = mysqli_query($mysqli, $agenda);
          $dados = [
            'seg' => 0,
            'ter' => 0,
            'qua' => 0,
            'qui' => 0,
            'sex' => 0,
            'sab' => 0,
            'dom' => 0
          ];
          while ($row = mysqli_fetch_assoc($query)) {
            if (isset($dados[$row['dtsemana']])) {
              $dados[$row['dtsemana']] = (int) $row['count'];
            }
          }
          ?>
          <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
          <script type="text/javascript">
            google.charts.load('current', { 'packages': ['corechart'] });
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
              var data = google.visualization.arrayToDataTable([
                ['Dias da Semana', 'Agendamentos'],
                ['Seg', <?php echo $dados['seg']; ?>],
                ['Ter', <?php echo $dados['ter']; ?>],
                ['Qua', <?php echo $dados['qua']; ?>],
                ['Qui', <?php echo $dados['qui']; ?>],
                ['Sex', <?php echo $dados['sex']; ?>],
                ['Sab', <?php echo $dados['sab']; ?>],
                ['Dom', <?php echo $dados['dom']; ?>]
              ]);
              var options = {
              
                lineWidth: 3,
                series: { 0: { color: '#7C5AFF' } },
                legend: { position: 'bottom' }
              };
              var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
              chart.draw(data, options);
            }
          </script>
          <div id="curve_chart"></div>
        </div>
        <div class="lucro">
          <div>
            <h5>Lucro Total</h5>
            <?php
            $lucro = "SELECT SUM(a.preco_ad) as total_lucro, e.data_inicio
                      FROM agendamento a
                      INNER JOIN empresa e ON e.idempresa = a.idempresa
                      WHERE a.idempresa = $idempresa AND a.status = 'finalizado'";
            $sql_query = mysqli_query($mysqli, $lucro);
            if ($sql_query) {
              $row = mysqli_fetch_assoc($sql_query);
              $total_lucro = $row['total_lucro'];
              $data_inicio = $row['data_inicio'];
              // Calcular a diferença de tempo desde a data de início
              $date_inicio = new DateTime($data_inicio);
              $date_hoje = new DateTime();
              $diferenca = $date_inicio->diff($date_hoje);
              // Montar a mensagem com o tempo desde o início
              $anos = $diferenca->y;
              $meses = $diferenca->m;
              $dias = $diferenca->d;
              if ($anos > 0) {
                $mensagem = "$anos anos e $meses meses na nossa plataforma";
              } elseif ($meses > 0) {
                $mensagem = "$meses meses na nossa plataforma";
              } else {
                $mensagem = "$dias dias na nossa plataforma";
              }
              if ($total_lucro === NULL) {
                $total_lucro = 0;
              }
              ?>
              <div class="valor"> R$
                <?php echo number_format($total_lucro, 2, ',', '.'); ?>
              </div>
              <p>
                <?php echo $mensagem; ?>
              </p>
            <?php } ?>
          </div>
          <hr>
          <!-- Lucro do Último Trimestre -->
          <div>
            <h5>Lucro Trimestral</h5>
            <?php
            // Ajustando a consulta para o lucro do último trimestre
            $lucrotri = "SELECT SUM(a.preco_ad) as total_tri
                         FROM agendamento a
                         WHERE a.idempresa = $idempresa
                         AND a.status = 'finalizado'
                         AND a.dt_agendamento >= CURDATE() - INTERVAL 3 MONTH";
            $sql_query_triii = mysqli_query($mysqli, $lucrotri);
            if ($sql_query_triii) {
              $roww = mysqli_fetch_assoc($sql_query_triii);
              $total_tri = $roww['total_tri'];
              if ($total_tri === NULL) {
                $total_tri = 0;
              }
              $total_tri_hj = number_format($total_tri, 2, ',', '.');
              ?>
              <div class="valor"> R$
                <?php echo $total_tri_hj; ?>
              </div>
            <?php } ?>
            <?php
            $cliente_com_mais_agendamentos = "
            SELECT c.nome_cliente, a.status
            FROM agendamento a
            INNER JOIN cliente c ON c.idcliente = a.idcliente
            WHERE a.idempresa = $idempresa and a.status = 'finalizado'
            GROUP BY c.nome_cliente
            ORDER BY COUNT(a.idagendamento) DESC
            LIMIT 1";
            $cliente_query = mysqli_query($mysqli, $cliente_com_mais_agendamentos);
            if ($cliente_query) {
              $cliente_row = mysqli_fetch_assoc($cliente_query);
              if (empty($cliente_row['nome_cliente'])) {
                $nome_cli = "Infelizmente você ainda não tem clientes";
              } else {
                $nome_cli = "Cliente mais frequente: <strong>" . $cliente_row['nome_cliente'] . "</strong>";
              }
            }
            ?>
            <p>
              <?php echo $nome_cli; ?>
            </p>
          </div>
        
        </div>
        
      </div>
<div class="ladob">
  
        <div class="div2">
          <p>Serviços mais procurados</p>
    <?php
    // Sua consulta SQL agora conta o número de agendamentos para cada serviço
    $funcionarios = "SELECT s.nome_serv, COUNT(a.idagendamento) AS agendamentos
    FROM agendamento a
    INNER JOIN servicos s ON s.idservico = a.idservico
    INNER JOIN lista_servicos_empresa ls ON ls.idservico = s.idservico
    INNER JOIN empresa e ON e.idempresa = ls.idempresa
    WHERE e.idempresa = $idempresa
    GROUP BY s.idservico, s.nome_serv";
  
    $func_sql = mysqli_query($mysqli, $funcionarios);
  
    // Prepara os dados para o gráfico
    $dados = [];
    while ($row = mysqli_fetch_assoc($func_sql)) {
      // Aqui, estamos pegando o nome do serviço e o número de agendamentos
      $dados[] = "['" . $row['nome_serv'] . "', " . $row['agendamentos'] . "]";
    }
    // Converte o array para formato necessário para o JavaScript
    $dados_json = implode(',', $dados);
    ?>
  
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', { 'packages': ['corechart'] });
      google.charts.setOnLoadCallback(drawChart);
  
      function drawChart() {
        // Passando os dados do PHP para o JavaScript
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Agendamentos'], // Cabeçalhos
          <?php echo $dados_json; ?>  // Dados dinâmicos do PHP
        ]);
  
        var options = {
         
          is3D: false, // Garantindo que o gráfico não seja 3D
          colors: ['#7C5AFF', '#B583F5', '#7C5AFF', '#7231C5'], // Cores personalizadas
          pieSliceText: 'percentage', // Exibe a porcentagem nas fatias
          slices: {
            // Adiciona configurações específicas de fatias, como destaque, etc.
          },
        };
  
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>
  
    <div id="piechart"></div>
  </div>
  
     <div class="ilustracao">
  
      <?php
      include("includes/animacaofaturamento.php");
      ?>
  
     </div>
</div>

      
    </div><br><br>
    

  


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
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
</body>

</html>