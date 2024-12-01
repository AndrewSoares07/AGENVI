<?php
include('includes/logar-sistema.php');
$id_empresa = $_GET['id'];
$infor_emp = "SELECT e.nome,
e.nome_fantasia,
e.foto_empresa,
e.numero,
e.foto_amb1,
e.foto_amb2,
e.foto_amb3,
e.foto_amb4,
e.foto_amb5,
e.foto_amb6,
l.cep,
l.logradouro,
l.bairro,
l.UF,
l.cidade,
p.Nome,
p.nivel
FROM empresa e
INNER JOIN localidade l ON l.cep = e.cep
INNER JOIN planos p ON p.nivel = e.nivel
WHERE e.idempresa = $id_empresa;
";
$sql_result = $mysqli->query($infor_emp);
if ($sql_result->num_rows == 1) {
    $infoemp = $sql_result->fetch_assoc();

    $nome = $infoemp['nome'];
    $nome_fantasia = $infoemp['nome_fantasia'];
    $numero = $infoemp['numero'];
    $cep = $infoemp['cep'];
    $logradouro = $infoemp['logradouro'];
    $bairro = $infoemp['bairro'];
    $uf = $infoemp['UF'];
    $cidade = $infoemp['cidade'];
    $nivel = $infoemp['nivel'];
    $foto_pf = $infoemp['foto_empresa'];
    $foto_amb1 = $infoemp['foto_amb1'];
    $foto_amb2 = $infoemp['foto_amb2'];
    $foto_amb3 = $infoemp['foto_amb3'];
    $foto_amb4 = $infoemp['foto_amb4'];
    $foto_amb5 = $infoemp['foto_amb5'];
    $foto_amb6 = $infoemp['foto_amb6'];
    $nome_pl = $infoemp['Nome'];
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
</head>

<body>

    <div>
        <div>
            <img src="arquivos/'<?php echo  $foto_amb1 ?>'" alt="">
            <img src="arquivos/'<?php echo $foto_pf ?>'" alt="">
        </div>

        <div>
            <?php echo $nome ?>
            <?php echo "  $logradouro, $numero-$bairro, $uf <i class='bx bxs-map' style='color:#6023b0'  ></i>" ?>
        </div>
        <div>
            <h5>plano escolhido</h5>
            <?php echo $nome_pl ?>
            <hr>
        </div>
        <div>
            <?php
            $lista_serv = "SELECT s.nome_serv 
            from lista_servicos_empresa ls 
            inner join servicos s on s.idservico = ls.idservico
            where ls.idempresa = $id_empresa";
            $result_serv = mysqli_query($mysqli, $lista_serv);
            while ($servicos = $result_serv->fetch_assoc()) { ?>
                <div>
                    <li>
                        <?php echo $servicos['nome_serv'] ?>
                    </li>
                </div>

            <?php } ?>
        </div>




    </div>
    <div>
        <div>
            <h5>Membros da equipe</h5>
            <div class="func-carousel">
                <?php
                $sql_func = "SELECT f.nome_func, f.foto_func
                from lista_funcionario_empresa lf
                inner join funcionario f on f.idfuncionario = lf.idfuncionario
                where lf.idempresa = $id_empresa;";
                $lista_func = mysqli_query($mysqli, $sql_func);
                while ($resut = $lista_func->fetch_assoc()) {
                ?>
                    <div>
                        <img src="arquivos/<?php echo $resut['foto_func'] ?>" alt="">
                        <p><?php echo $resut['nome_func'] ?></p>

                    </div>
                <?php } ?>
            </div>

        </div>
        <hr>
        <div>
            <h5>Horário de Funcionamento </h5>
            <?php
            $sql_horario = "SELECT he.dias_semana, he.horario_ini, he.horario_fim from horarios_empresa he where idempresa = $id_empresa";
            $result_hr = mysqli_query($mysqli, $sql_horario);
            while ($horario = $result_hr->fetch_assoc()) {
            ?>
                <div>
                    <?php echo $horario['dias_semana'] ?>
                    <?php echo $horario['horario_ini'] ?>--<?php echo $horario['horario_fim'] ?>
                </div>
            <?php } ?>
        </div>
        <br>
        <div>
        <div class="lucro">
          <div>
            <h5>Lucro Total</h5>
            <?php
            $lucro = "SELECT SUM(a.preco_ad) as total_lucro, e.data_inicio
                      FROM agendamento a
                      INNER JOIN empresa e ON e.idempresa = a.idempresa
                      WHERE a.idempresa = $id_empresa AND a.status = 'finalizado'";
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
                         WHERE a.idempresa = $id_empresa
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
            WHERE a.idempresa = $id_empresa and a.status = 'finalizado'
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

        </div>
        







    </div>

</body>

</html>


<script type="text/javascript">
    $(document).ready(function() {
        $('.func-carousel').slick({
            slidesToShow: 4, // Exibe 3 funcionários por vez
            slidesToScroll: 3, // Move um funcionário por vez
            // infinite: true,      // Loop infinito
            dots: true, // Adiciona indicadores
            // arrows: true,        // Setas de navegação
            draggable: true, // Permite arrastar com o mouse
            responsive: [{
                    breakpoint: 768, // Para telas menores (tablets e celulares)
                    settings: {
                        slidesToShow: 1
                    }
                },
                {
                    breakpoint: 1024, // Para telas médias (tablets horizontais)
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        });
    });
</script>