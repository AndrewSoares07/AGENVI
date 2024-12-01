<?php
$idempresa = $_GET['idempresa'];

include('includes/logar-sistema.php');
include('protect_cliente.php');



$idcliente = $_SESSION['idcliente'];


if (!$mysqli) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}



$sql = "SELECT e.nome,
               e.nome_fantasia,
               e.email,
               e.telefone, 
               l.bairro, 
               l.uf, 
               l.cidade,
               l.cep,
               l.logradouro, 
               e.numero, 
               e.foto_empresa,
               e.foto_amb1,
               e.foto_amb2,
               e.foto_amb3,
               e.foto_amb4,
               e.foto_amb5,
               e.foto_amb6,
               e.complemento
        FROM empresa e
        INNER JOIN localidade l ON e.cep = l.cep
        WHERE idempresa = $idempresa";

$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $empresa = $result->fetch_assoc();

    $bairro = $empresa['bairro'];
    $uf = $empresa['uf'];
    $cidade = $empresa['cidade'];
    $logradouro = $empresa['logradouro'];
    $numero = $empresa['numero'];
    $cep = $empresa['cep'];
    $nome = $empresa['nome'];
    $email = $empresa['email'];
    $nomef = $empresa['nome_fantasia'];
    $tel = $empresa['telefone'];
    $img1 = $empresa['foto_empresa'];
    $ft1 = $empresa['foto_amb1'];
    $ft2 = $empresa['foto_amb2'];
    $ft3 = $empresa['foto_amb3'];
    $ft4 = $empresa['foto_amb4'];
    $ft5 = $empresa['foto_amb5'];
    $ft6 = $empresa['foto_amb6'];
    $comp = $empresa['complemento'];


    $endereco_completo = "{$logradouro}, {$numero}, {$bairro}, {$cidade}, {$uf}";
    $endereco_url = urlencode($endereco_completo);


} else {
    echo "Nenhum resultado encontrado para o ID da empresa.";
}





?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../css/teste.css">
    <link rel="stylesheet" href="../css/agendamento.css">

</head>
<style>

</style>

<body>
    <?php include("includes/header-nav.php"); ?>


    <div class="div1">

        <?php include('includes/avaliacao.php'); ?>

            <div class="dadosemp">

            <div class="num_aval2">
                    <h3><?php echo number_format($media_estrelas, 1); ?></h3>
                    <p><?php echo $total_avaliacoes ?> avaliações</p>
                </div>

    
                <img class="baneremp" src="arquivos/<?php echo $ft1 ?>" alt="">
                <img class="logoemp" src="arquivos/<?php echo $img1 ?>" alt="">
                        
                <h4 class="nomep"><?php echo "$nomef"; ?></h4>
                <p class="com"> <?php echo "$comp" ?></p>

            
                
            
            </div>



            <div class="mapa">
                <?php echo '<iframe width="500" height="300"  frameborder="0" style="border:0"  src="https://maps.google.com/maps?q=' . $endereco_url . '&z=16&output=embed" allowfullscreen></iframe>'; ?>
                <br><br> <p><?php echo "<i class='bx bxs-map' style='color:#6023b0'  ></i>  $logradouro, $numero - $bairro, $cidade - $uf, $cep" ?></p> 
            </div>

    </div>



    <div class="div2">

    <div class="container">
    <h4 class="tit">Membros da equipe</h4>

    <div class="row">
        <div class="col-1 d-flex align-items-center justify-content-center">
            <!-- Botão de seta para a esquerda -->
            <a class="carousel-control-prev" href="#funcCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
        </div>

        <div class="col-10">
            <!-- Carrossel -->
            <div id="funcCarousel" class="carousel slide">
                <div class="carousel-inner">
                    <?php
                    $func_sql = " SELECT
                            f.nome_func,
                            f.foto_func
                            FROM lista_funcionario_empresa lf
                            inner JOIN funcionario f ON lf.idfuncionario = f.idfuncionario
                            INNER JOIN empresa e ON e.idempresa = lf.idempresa
                            WHERE e.idempresa = $idempresa; ";
                    $result_func = $mysqli->prepare($func_sql);
                    $result_func->execute();
                    $func_list = $result_func->get_result();
                    
                    $count = 0;
                    $slide_index = 0;
                    $num_items_per_slide = 3;
                    while ($func__list = $func_list->fetch_assoc()) {
                        // Inicia um novo item de slide a cada 3 funcionários
                        if ($count % $num_items_per_slide === 0) {
                            echo '<div class="carousel-item ' . ($slide_index === 0 ? 'active' : '') . '">';
                            echo '<div class="row">';
                            $slide_index++;
                        }
                        ?>
                        <div class="col-md-4">
                            <div class="funcionarios">
                                <img class="fotofunc img-fluid" src="arquivos/<?php echo $func__list['foto_func'] ?>" alt="">
                                <p><strong><?php echo $func__list['nome_func'] ?></strong></p>
                            </div>
                        </div>
                        <?php
                        $count++;
                        // Fecha o item de slide após 3 funcionários
                        if ($count % $num_items_per_slide === 0 || $count === $func_list->num_rows) {
                            echo '</div>'; // Fecha a row
                            echo '</div>'; // Fecha o item de slide
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-1 d-flex align-items-center justify-content-center">
            <!-- Botão de seta para a direita -->
            <a class="carousel-control-next" href="#funcCarousel" role="button" data-slide="next" style="color:black;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Próximo</span>
            </a>
        </div>
    </div>
</div>
  
</body>
</html>