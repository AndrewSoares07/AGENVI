
<?php
include("protect_adm.php");
include("includes/logar-sistema.php");
?>

<!DOCTYPE html>
<html lang="pt-br" dir="ltr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/faturamentos.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
  <title>Agenvi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <?php
  include("includes/navbar-adm.php");
  ?>
    <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu'></i>
      <span class="text">Empresas</span>
    </div>




    <div class="sep_div">
      <div class="ladoa">

        <div class="lucro">
          <div>
            <h5>Lucro Total</h5>
            <?php
            $lucro = "SELECT sum(p.preco) as total_lucro
            FROM empresa e
            INNER JOIN planos p ON p.nivel = e.nivel";
            $sql_query = mysqli_query($mysqli, $lucro);
            if ($sql_query) {
              $row = mysqli_fetch_assoc($sql_query);
              $total_lucro = $row['total_lucro'];
             
        
              ?>
              <div class="valor"> R$
                <?php echo number_format($total_lucro, 2, ',', '.'); ?>
              </div>
            <?php } ?>
          </div>
          <hr>
          <!-- Lucro do Último Trimestre -->
          <div>
            <h5>Lucro Trimestral</h5>
            <?php
            // Ajustando a consulta para o lucro do último trimestre
            $lucrotri = "SELECT sum(p.preco) as total_tri, e.data_inicio
                        FROM empresa e
                        INNER JOIN planos p ON p.nivel = e.nivel
                        AND data_inicio >= CURDATE() - INTERVAL 3 MONTH";
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
            $cliente_com_mais_agendamentos = "SELECT e.nome_fantasia as nome_cliente, e.data_inicio
            FROM empresa e
            ORDER BY e.data_inicio asc
            LIMIT 1";
            $cliente_query = mysqli_query($mysqli, $cliente_com_mais_agendamentos);
            if ($cliente_query) {
              $cliente_row = mysqli_fetch_assoc($cliente_query);
              if (empty($cliente_row['nome_cliente'])) {
                $nome_cli = "Infelizmente você ainda não tem clientes";
              } else {
                $nome_cli = "Cliente mais antigo: <strong>" . $cliente_row['nome_cliente'] . "</strong>";
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
          <p>Nichos mais procurados</p>
          <?php
// Consulta SQL para contar os agendamentos por tipo
$funcionarios = "SELECT tipo AS nome_serv, COUNT(tipo) AS agendamentos FROM empresa GROUP BY tipo";

$func_sql = mysqli_query($mysqli, $funcionarios);

// Prepara os dados para o gráfico
$dados = [];
while ($row = mysqli_fetch_assoc($func_sql)) {
    // Adiciona os dados ao array
    $dados[] = "['" . addslashes($row['nome_serv']) . "', " . (int)$row['agendamentos'] . "]";
}
// Converte o array PHP para o formato de JavaScript
$dados_json = implode(',', $dados);
?>

  
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Carrega a biblioteca Google Charts
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Dados enviados do PHP
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Agendamentos'], // Cabeçalhos do gráfico
            <?php echo $dados_json; ?> // Dados dinâmicos
        ]);

        var colors = {
            'advocacia': '#0000FF',   // Azul
            'barbearia': '#FF0000', // Vermelho
            'clinica': '#00FF00',   // Verde
            'salaobeleza': '#FFA500'    // Laranja
        };

        // Criação do objeto slices com base nos dados do gráfico
        var slices = {};
        for (var i = 0; i < data.getNumberOfRows(); i++) {
            var serviceName = data.getValue(i, 0); // Nome do serviço
            slices[i] = { color: colors[serviceName] || '#CCCCCC' }; // Cor personalizada ou padrão
        }

        // Configurações do gráfico
        var options = {
            is3D: false, // Gráfico 2D
            pieSliceText: 'percentage', // Exibe porcentagens nas fatias
            slices: slices // Aplica as cores personalizadas
        };

        // Cria e desenha o gráfico na div especificada
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
        
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var empresaId = button.data('id'); 
        
      
        var modal = $(this);
        modal.find('#confirmDeleteBtn').attr('href', 'excluir_empresa.php?id=' + empresaId);
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
</body>

</html>