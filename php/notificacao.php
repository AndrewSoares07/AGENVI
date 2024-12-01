<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');

$idcliente = $_SESSION['idcliente'];

// Configurações de paginação
$itens_por_pagina = 4;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_atual - 1) * $itens_por_pagina;

// Total de agendamentos em andamento
$sql_total = "SELECT COUNT(*) AS total FROM agendamento WHERE idcliente = ? AND status = 'em andamento'";
$stmt_total = $mysqli->prepare($sql_total);
$stmt_total->bind_param("i", $idcliente);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $itens_por_pagina);

// Consulta principal com limite de itens e offset
$agendamento_sql = "SELECT a.idagendamento, a.status, e.idempresa, e.nome_fantasia, s.nome_serv, 
                    a.preco_ad, a.horario_ini, a.horario_fim, a.dt_agendamento, f.nome_func, e.numero, 
                    l.cep, l.cidade, l.logradouro, l.bairro, l.UF
                    FROM agendamento a
                    INNER JOIN empresa e ON e.idempresa = a.idempresa
                    INNER JOIN servicos s ON s.idservico = a.idservico
                    INNER JOIN funcionario f ON f.idfuncionario = a.idfuncionario
                    INNER JOIN localidade l ON l.cep = e.cep
                    WHERE a.idcliente = ? AND a.status = 'em andamento'
                    ORDER BY a.dt_agendamento ASC
                    LIMIT ? OFFSET ?";
$stmt = $mysqli->prepare($agendamento_sql);
$stmt->bind_param("iii", $idcliente, $itens_por_pagina, $inicio);
$stmt->execute();
$result_agen = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenvi</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/notifica.css">
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            padding: 1em 0;
            list-style-type: none;
        }
        .pagination li {
            margin: 0 8px;
        }
        .pagination li a {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #ccc;
            transition: background-color 0.3s;
        }
        .pagination li a:hover {
            background-color: #b2b2b2;
        }
        .pagination li.active a {
            background-color: #6023B0;
        }
    </style>
</head>
<body>
<header>
        <a href="../php/principal-cliente.php"><img class="seta" src="../img/seta-esquerda.png" alt=""><img class="logo" src="../img/logo-completona.png" alt=""></a> 
        <a class="sair" href="logout.php"><i class='bx bx-exit' style='color:#ffffff'></i> Sair</a>
    </header>

    <div class="papai">
        <?php include("includes/infos-perfil-cli.php"); ?>
        <div class="caixa-central">
            <h3>Agendamentos 
                <?php
                $sml = "SELECT COUNT(*) AS agendamento FROM agendamento WHERE idcliente = ? AND status = 'em andamento'";
                $stmt_fav = $mysqli->prepare($sml);
                $stmt_fav->bind_param("i", $idcliente);
                $stmt_fav->execute();
                $stmt_fav->bind_result($total_favoritas);
                $stmt_fav->fetch();
                $stmt_fav->close();
                echo "<strong class='fav'>({$total_favoritas})</strong>";
                ?>
            </h3>
            <?php while ($roww = $result_agen->fetch_assoc()) : ?>
                <?php
                    $data = htmlspecialchars($roww['dt_agendamento']);
                    $dataInvertida = date("d/m/Y", strtotime($data));
                    $horarioInicio = htmlspecialchars($roww['horario_ini']);
                    $horarioFim = htmlspecialchars($roww['horario_fim']);
                    $horarioFormatadoInicio = substr($horarioInicio, 0, 5);
                    $horarioFormatadoFim = substr($horarioFim, 0, 5);
                    $precoServ = str_replace('.', ',', htmlspecialchars($roww['preco_ad']));
                ?>  
                <div class="outra">
                    <div class="pai3">
                        <div class="color">.</div>
                        <div>
                            <a href="agendamento.php?id=<?= htmlspecialchars($roww['idempresa']); ?>" class="nom"><?= htmlspecialchars($roww['nome_fantasia'])?></a>
                            <p class="nom2"><?= htmlspecialchars($roww['nome_serv'])?></p>
                        </div>
                        
                        <div>
                        <br>
                            <div class="hor">
                                <p id="data"><?= $dataInvertida ?> - <?= $horarioFormatadoInicio ?> às <?= $horarioFormatadoFim ?></p>
                            </div>
                            <p class="prer">R$ <?= $precoServ ?></p>
                        </div>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#confirmModal">Cancelar</button>
                    </div>
                    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmModalLabel">Confirmação</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Quer mesmo cancelar esse agendamento?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                    <button><a href="cancelaragend.php?id=<?= htmlspecialchars($roww['idagendamento']); ?>" class="sim">Sim</a></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- Paginação -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="<?= ($i == $pagina_atual) ? 'active' : '' ?>">
                            <a href="?pagina=<?= $i ?>"></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
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





