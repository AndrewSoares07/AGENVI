<?php
// Incluir o arquivo de conexão e autenticação
include("includes/logar-sistema.php");
include("protect_cliente.php");

// Verificar se a conexão com o banco de dados foi estabelecida
if (!$mysqli) {
    die("Falha na conexão: " . mysqli_connect_error());
}

$idcliente = $_SESSION['idcliente'];

// Número de itens por página
$itens_por_pagina = 4;

// Página atual
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_atual - 1) * $itens_por_pagina;

// Consulta SQL para obter o total de registros
$sql_total = "SELECT COUNT(*) AS total FROM agendamento WHERE idcliente = ?";
$stmt_total = $mysqli->prepare($sql_total);
$stmt_total->bind_param("i", $idcliente);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_registros = $result_total->fetch_assoc()['total'];

// Calcula o total de páginas
$total_paginas = ceil($total_registros / $itens_por_pagina);

// Consulta SQL principal com limite e offset para paginação
$sql = "SELECT a.dt_agendamento, a.status, a.horario_ini, a.horario_fim, c.nome_cliente, s.nome_serv
        FROM agendamento a
        INNER JOIN cliente c ON a.idcliente = c.idcliente
        INNER JOIN servicos s ON a.idservico = s.idservico
        WHERE a.idcliente = ?
        LIMIT ? OFFSET ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iii", $idcliente, $itens_por_pagina, $inicio);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/historico.css">
    <style>
        /* Estilo para a paginação com estilo de "radio button" */
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
            background-color: #6023B0; /* Cor da página ativa */
        }
    </style>
</head>
<body>
    
    <header>
        <a href="../php/principal-cliente.php"><img class="seta" src="../img/seta-esquerda.png" alt=""><img class="logo" src="../img/logo-completona.png" alt=""></a> 
        <a class="sair" href="logout.php"><i class='bx bx-exit' style='color:#ffffff'></i> Sair</a>
    </header>

    <div class="papai">
        <?php include("includes/infos-perfil-cli.php") ?>

        <div class="central">
            <h3>Histórico</h3>

            <div class="centralff">
                <?php
                if ($result->num_rows > 0) {
                    while ($dados = $result->fetch_assoc()) {
                        echo "<table border='1' cellpadding='5' cellspacing='0' style='margin-bottom: 20px;'>";
                        echo "<tr>
                                <th>Dia</th>
                                <th>Status</th>
                                <th>Início</th>
                                <th>Término</th>
                                <th>Cliente</th>
                                <th>Serviço</th>
                              </tr>";
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($dados['dt_agendamento']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['status']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['horario_ini']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['horario_fim']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['nome_cliente']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['nome_serv']) . "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                } else {
                    echo "<p>Nenhum dado encontrado.</p>";
                }
                ?>
            </div>

            <!-- Navegação de Paginação -->
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>
