<?php
include('includes/logar-sistema.php');

include('protect_cliente.php');

$idcliente = $_SESSION['idcliente'];

if (!$mysqli) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

function roundToHalf($num)
{
    return round($num * 2) / 2;
}

$sql_cliente = "SELECT l.uf 
                FROM cliente c 
                INNER JOIN localidade l ON c.cep = l.cep 
                WHERE c.idcliente = ?";
$stmt_cliente = $mysqli->prepare($sql_cliente);
$stmt_cliente->bind_param('i', $idcliente);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();
$cliente_data = $result_cliente->fetch_assoc();

$cliente_estado = $cliente_data['uf'];
$stmt_cliente->close();


$title = "As empresas mais bem avaliadas do seu estado";


if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $buscar = $mysqli->real_escape_string($_GET['buscar']);


    $sql = "SELECT e.idempresa, e.nome, e.foto_empresa, e.foto_amb1, l.cidade, e.nome_fantasia,
            COALESCE(AVG(a.estrelas), 0) AS media_estrelas, 
            COUNT(a.idavaliacao) AS total_avaliacoes,
            CASE WHEN l.uf = ? THEN 1 ELSE 0 END AS prioridade,
            (SELECT COUNT(*) FROM favoritos f WHERE f.idcliente = ? AND f.idempresa = e.idempresa) AS is_favorited
            FROM empresa e 
            INNER JOIN localidade l ON e.cep = l.cep
            LEFT JOIN avaliacao a ON e.idempresa = a.idempresa
            WHERE e.nome LIKE '%$buscar%' OR e.nome_fantasia LIKE '%$buscar%'
            GROUP BY e.idempresa, e.nome, e.foto_empresa, e.foto_amb1, l.cidade, l.uf
            ORDER BY prioridade DESC, total_avaliacoes DESC, media_estrelas DESC";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('si', $cliente_estado, $idcliente);
    $stmt->execute();
    $result = $stmt->get_result();


    $title = "Resultados da busca por '$buscar'";
} else {

    $sql = "SELECT e.idempresa, e.nome, e.foto_empresa, e.foto_amb1, l.cidade,  e.nome_fantasia ,
            COALESCE(AVG(a.estrelas), 0) AS media_estrelas, 
            COUNT(a.idavaliacao) AS total_avaliacoes,
            (SELECT COUNT(*) FROM favoritos f WHERE f.idcliente = ? AND f.idempresa = e.idempresa) AS is_favorited
            FROM empresa e 
            INNER JOIN localidade l ON e.cep = l.cep
            LEFT JOIN avaliacao a ON e.idempresa = a.idempresa
            WHERE l.uf = ?
            GROUP BY e.idempresa, e.nome, e.foto_empresa, e.foto_amb1, l.cidade
            ORDER BY total_avaliacoes DESC, media_estrelas DESC
            LIMIT 5";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('is', $idcliente, $cliente_estado);
    $stmt->execute();
    $result = $stmt->get_result();
}


$showCarousel = !(empty($_GET['buscar']) && empty($_GET['buscar'])) && !$stmt->num_rows();

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title> Agenvi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/teste.css">
</head>

<body>



<style>
    
    .perfiloffcanv{
  margin: 10px;
  width: 55px;

  height: 55px;
  border-radius: 100%;
}

    
    
        :root{
        --c01:#fff;
        --c02:#E9E3FF; 
        --c03:#999999;
        --c04:#161616;
        --c05:#000;
        --c06:#B583F5;
        --c07:#7C5AFF;
        --c08:#6023B0;
        --c09:#471687;
    }
    
    .img{
     
      width:60px;
      
      border-radius:100%;
    }
    
     
    .offcanvas-end{
      height: 330px;
      border-radius: 10px;
      width: 30%;
    
     
    }
    
    .offcanvas-body{
    margin-left: 10px;
     width: 100%;
    }
    
    .exit{
      
       text-decoration: none;
       color: #161616;
       font-size: 17px;
    }
    .exit:hover{
      color: var(--c08);
     font-weight: 600;
    }
    
    
    .sair{
        width: 80px;
        text-align: center;
    }
    li{
        width: 150px;
        color: var(--c01) !important;
        text-align: center;
    }
   
    .bt1{
        width: 40px;
        
        border: none;
      background-color: var(--c08);
      color: var(--c01);
      border-radius: 0px 10px 10px 0px;
    }
    
    
    .bg-body-tertiary{
      height: 80px;
      z-index: 3;
     
      background-color: rgba(0, 0, 0, 0.426) !important;
      box-shadow: 17px 20px 8px rgba(0, 0, 0, 0.426)  !important;
    
    
    
    }
    .nome{
      font-size: 20px;
    }
    .offcanvas-body>div{
      margin-top: -10;
      margin-bottom: 10px;
    }
    i{
      margin: 5px;
    }
    
    .navbar-brand{
      margin-left:25px;
    }

    .navbar-nav .nav-link.active, .navbar-nav .nav-link.show{
        color: var(--c01);
    }
    .meusagendamentos{
        padding-left: 25px;
        display: flex;
        width: 100%;
    }
    .justify-content-start{
        padding-left: 25px;
    }
   
</style>

   
    <?php include("includes/header-nav.php"); ?>

    <?php
    if (!$showCarousel) {



        ?>
        <video autoplay muted loop>
            <source class="video" src="../img/video-agenvi2 (1100 x 410 px).mp4" type="video/mp4">
        </video>
        <?php
    }
    ?>



   
          <?php
              if (!$showCarousel) { ?>
          
           <?php
          $agendamento_sql = "SELECT 
              e.idempresa,
              e.foto_empresa,
              e.nome,
              e.nome_fantasia,
              s.nome_serv,
              a.preco_ad,
              a.horario_ini,
              a.horario_fim,
              a.dt_agendamento,
              f.nome_func,
              e.numero,
              l.cep,
              l.cidade,
              l.logradouro,
              l.bairro,
              l.UF
          FROM agendamento a
          INNER JOIN empresa e ON e.idempresa = a.idempresa
          INNER JOIN servicos s ON s.idservico = a.idservico
          INNER JOIN funcionario f ON f.idfuncionario = a.idfuncionario
          INNER JOIN localidade l ON l.cep = e.cep
          WHERE 
              a.idcliente = ? and a.status = 'em andamento'
          ORDER BY a.dt_agendamento ASC LIMIT 3;";
          $agend_sql = $mysqli->prepare($agendamento_sql);
          $agend_sql->bind_param('i', $idcliente);
          $agend_sql->execute();
          $result_agen = $agend_sql->get_result();
          
          // Verificar se há resultados
          if ($result_agen->num_rows > 0) {
              // Exibir o título apenas se houver agendamentos
              echo '<h3>seus <strong>agendamentos</strong></h3>   <br><br><div class="meusagendamentos">';
          
              // Loop para exibir os agendamentos
              while ($roww = $result_agen->fetch_assoc()) {
            $data = htmlspecialchars($roww['dt_agendamento']);
            $partesData = explode("-", $data);
            $dataInvertida = "{$partesData[2]}/{$partesData[1]}/{$partesData[0]}";
            $horarioInicio = htmlspecialchars($roww['horario_ini']);
            $horarioFim = htmlspecialchars($roww['horario_fim']);
            $horarioFormatadoInicio = substr($horarioInicio, 0, 5);
            $horarioFormatadoFim = substr($horarioFim, 0, 5);
            $precoServ = str_replace('.', ',', htmlspecialchars($roww['preco_ad']));
          ?>
          
          
          <div class="papito">
              <div class="vamola">
            <a href="agendamento.php?id=<?= htmlspecialchars($roww['idempresa']); ?>">

                <div class="pai2">
                    <div class="dif">
                        <?php
                            $foto_amb2_path = 'arquivos/' . htmlspecialchars($roww['foto_empresa']);
                            echo "<div class='fotinha' style='background-image: url($foto_amb2_path); background-size: cover;'></div>";
                        ?>
                        <div class="infoemp">
                            <p class="pzin"> <?= htmlspecialchars($roww['nome_fantasia']) ?></p>
                            <p class="local"><?= htmlspecialchars($roww['logradouro']) ?>, <?= htmlspecialchars($roww['numero']) ?> - <?= htmlspecialchars($roww['bairro']) ?> </p>
                        </div>
                    </div>
                    <div class="bodyinfo">
                        <p> <strong class="color"><?= htmlspecialchars($roww['nome_serv']) ?></strong> <strong class="din">R$<?= $precoServ ?></strong></p>
                        <p id="data" class="pdata"> <i class='bx bxs-calendar 'style='color:#6023B0'></i> <?= $dataInvertida ?>  às <?= $horarioFormatadoInicio ?></p>
                    </div>
                    
                </div>
            </a>
              </div>
          </div>
          
          
          
          <?php 
              }
          }
          ?>
          
          <?php }?>
      </div>

    
    
    <h3>Destaques</h3>
    <h4>
        <?= htmlspecialchars($title) ?>
    </h4>
      <br><br>
    <div class="d-flex  justify-content-start flex-wrap" >
        <?php while ($row = $result->fetch_assoc()) { ?>


            <a href="agendamento.php?id=<?= htmlspecialchars($row['idempresa']); ?>">

                <div class="pai">

                <button class="favorite-button" data-idempresa="<?= $row['idempresa'] ?>">
                    <img src="../img/<?= $row['is_favorited'] ? 'heath.png' : 'coracao.png' ?>"class="heart-icon" />
                </button>
                

                    <?php
                      
                        $foto_amb1_path = 'arquivos/' . htmlspecialchars($row['foto_amb1']);
                        echo "<div class='filho' style='background-image: url($foto_amb1_path); background-size: cover;'></div>";
                    ?>
                    
                    <?php
                      $foto_amb2_path = 'arquivos/' . htmlspecialchars($row['foto_empresa']);
                      echo "<div class='filho2' style='background-image: url($foto_amb2_path); background-size: cover;'></div>";
                    
                    ?>
                <div class="footercard">
    
                             <div class="txt1">
                                <h5 style="color: #7C5AFF;   font-weight: bold;">
                                    <?= htmlspecialchars($row['nome_fantasia']) ?>
                                </h5>
                            </div>
    
    
                        <div class="star-rating">
                                    <?php
                                    $fullStars = floor($row['media_estrelas']);
                                    $halfStar = ($row['media_estrelas'] - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    for ($i = 0; $i < $fullStars; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    if ($halfStar) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    for ($i = 0; $i < $emptyStars; $i++) {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                                
                            <div class="txt2">
    
                                <p class="end"> <?= htmlspecialchars($row['cidade']) ?> </p>
                            </div>
                    </div>
                </div>
            </a>

        <?php } ?>
    </div>
   
      <br><br><br>
    <?php if (!$showCarousel) { ?>
    <h3>Encontre as melhores empresas aqui na <strong>agenvi</strong></h3>
    <h4>Veja as variadas opções de empresas a baixo</h4>
     <br><br>
    <div class="d-flex  justify-content-start flex-wrap" >

            <?php
            $aleemp = " SELECT 
        e.idempresa,
        e.nome, 
        e.foto_empresa,
         e.foto_amb1,
        l.cidade, 
        e.nome_fantasia,
        COALESCE(AVG(a.estrelas), 0) AS media_estrelas, 
        COUNT(a.idavaliacao) AS total_avaliacoes,
        (SELECT COUNT(*) FROM favoritos f WHERE f.idcliente = $idcliente AND f.idempresa = e.idempresa) AS is_favorited
         FROM empresa e
         INNER JOIN localidade l ON l.cep = e.cep
         LEFT JOIN avaliacao a ON e.idempresa = a.idempresa 
         GROUP BY e.idempresa, e.nome, e.foto_empresa, l.cidade
         ORDER BY RAND();
        ";
            $sql_aleemp = mysqli_query($mysqli, query: $aleemp);
            while ($alet = $sql_aleemp->fetch_assoc()) {
                ?>


        <a href="agendamento.php?id=<?= htmlspecialchars($alet['idempresa']); ?>">
            <div class="pai">

                    <button class="favorite-button" data-idempresa="<?= $alet['idempresa'] ?>">
                        <img src="../img/<?= $alet['is_favorited'] ? 'heath.png' : 'coracao.png' ?>"class="heart-icon" />
                    </button>


                    <?php
                    $foto_amb1_path = 'arquivos/' . htmlspecialchars($alet['foto_amb1']);
                    echo "<div class='filho' style='background-image: url($foto_amb1_path); background-size: cover;'></div>";
                    ?>
                        <?php
                    $foto_amb2_path = 'arquivos/' . htmlspecialchars($alet['foto_empresa']);
                    echo "<div class='filho2' style='background-image: url($foto_amb2_path); background-size: cover;'></div>";
                    ?>
                       <div class="footercard">
                           
                           <div class="txt1">
                              <h5 style="color: #7C5AFF;   font-weight: bold;">
                                  <?= htmlspecialchars($alet['nome_fantasia']) ?>
                              </h5>
                                                   </div>
                           
                                                <div class="star-rating">
                                                   <?php
                              $fullStars = floor($alet['media_estrelas']);
                              $halfStar = ($alet['media_estrelas'] - $fullStars) >= 0.5;
                              $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                              for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="fas fa-star"></i>';
                              }
                              if ($halfStar) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                              }
                              for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<i class="far fa-star"></i>';
                              }
                                                   ?>
                                               </div>
                           
                                           
                                                  
                                                   
                                                   <div class="txt2">
                              <p class="end"> <?= htmlspecialchars($alet['cidade']) ?></p>
                                                   </div>
                       </div>
                </div>
            </a>


            <?php } ?>
        <?php } ?>
    </div>
    <br>
    <br>
    <br>
    <?php include("includes/footer.php"); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
   $(document).ready(function () {
    $('.favorite-button').on('click', function (e) {
        e.preventDefault();
        var button = $(this);
        var idempresa = button.data('idempresa');
        var icon = button.find('.heart-icon');
        var currentIconSrc = icon.attr('src');
        var action;

        // Verifique o ícone atual para determinar a ação
        if (currentIconSrc === '../img/heath.png') {
            action = 'unfavorite';
            icon.attr('src', '../img/coracao.png'); // Troque para o ícone "favorito" imediatamente
        } else {
            action = 'favorite';
            icon.attr('src', '../img/heath.png'); // Troque para o ícone "não favorito" imediatamente
        }

        $.ajax({
            url: 'favoritar.php',
            method: 'POST',
            data: { idempresa: idempresa, action: action },
            success: function (response) {
                // Aqui você pode tratar a resposta se necessário
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Erro ao favoritar:', textStatus, errorThrown);
                // Opcionalmente, reverta a troca de ícone se a requisição falhar
                if (action === 'favorite') {
                    icon.attr('src', '../img/coracao.png'); // Reverte se falhar
                } else {
                    icon.attr('src', '../img/heath.png'); // Reverte se falhar
                }
            }
        });
    });
});



     

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>

<?php

// 1. Obtém a data e hora atual
$data_atual = date('Y-m-d H:i:s'); // Formato: YYYY-MM-DD HH:MM:SS

// 2. Atualiza os agendamentos que já passaram da data/hora atual
$sql_atualiza = "UPDATE agendamento
                 SET status = 'finalizado'
                 WHERE idcliente = ? 
                 AND status = 'em andamento'
                 AND dt_agendamento < ? 
                 AND CONCAT(dt_agendamento, ' ', horario_fim) < ?"; // Compara data e horário de término

$stmt_atualiza = $mysqli->prepare($sql_atualiza);
$stmt_atualiza->bind_param("iss", $idcliente, $data_atual, $data_atual);
$stmt_atualiza->execute();
$stmt_atualiza->close();
?>





<?php
$stmt->close();
$mysqli->close();
?>

