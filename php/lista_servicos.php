<?php
include("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");

// Função para formatar a duração
function formatarDuracao($duracao) {
    // Assumindo que a duração está no formato "HH:MM"
    if (preg_match('/(\d+):(\d+)/', $duracao, $matches)) {
        $horas = $matches[1];
        $minutos = $matches[2];

        // Exibe a duração no formato "XhYY"
        return $horas . 'h' . str_pad($minutos, 2, '0', STR_PAD_LEFT);
    }
    return $duracao; // Retorna a duração original se não estiver no formato esperado
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/lista-func.css">
    <title>Agenvi</title>
</head>
<body>

<?php include("includes/navbar.php"); ?>

<section class="home-section">
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Serviços</span>
    </div>

    <div id="listaFunc"></div>

    <div class="container">
       
    <div class="container d-flex justify-content-between align-items-center mb-3">
<div>
    <a class='novofunc' href='cadastrar_servicos.php'>Novo serviço <img class='mais' src='../img/mais.png' alt=''></a>
    <a class='button' href='../php/relatorio/relatorio/gerar_relatorio.php?tipo=serv'>Gerar Relatório</a>
</div>

<form method="GET" action="" class="d-flex ms-3">
    <input type="text" name="pesquisa" class="barrapes" placeholder="Pesquisar Serviço" value="<?php echo isset($_GET['pesquisa']) ? htmlspecialchars($_GET['pesquisa']) : ''; ?>">
    <button type="submit" class="butpes"><i class='bx bx-search' style='color:#ffffff'  ></i></button>
</form>
</div>

        <table class="table table-striped">
            <?php
            $id_empresa = $_SESSION['idempresa'];

            // Definir o número de serviços por página
            $servicos_por_pagina = 5;

            // Capturar o número da página atual ou definir como 1 se não for fornecido
            $pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

            // Captura o termo de pesquisa
            $pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

            // Calcular o deslocamento (OFFSET) para a consulta
            $offset = ($pagina_atual - 1) * $servicos_por_pagina;

            // Consulta para contar o total de serviços com pesquisa
            $sql_total = "SELECT COUNT(*) AS total_servicos
                          FROM servicos s
                          INNER JOIN lista_servicos_empresa lse ON s.idservico = lse.idservico
                          WHERE lse.idempresa = ? AND (s.nome_serv LIKE ? OR s.descricao_serv LIKE ?)";
            $stmt_total = $mysqli->prepare($sql_total);
            $search_param = "%$pesquisa%"; // Adiciona wildcards para pesquisa
            $stmt_total->bind_param("iss", $id_empresa, $search_param, $search_param);
            $stmt_total->execute();
            $resultado_total = $stmt_total->get_result();
            $row_total = $resultado_total->fetch_assoc();
            $total_servicos = $row_total['total_servicos'];

            // Calcular o número total de páginas
            $total_paginas = ceil($total_servicos / $servicos_por_pagina);

            // Consulta para buscar os serviços com a pesquisa
            $sql = "SELECT
                        s.idservico,
                        s.nome_serv,
                        s.descricao_serv,
                        s.preco_serv,
                        s.duracao_serv
                    FROM
                        servicos s
                    INNER JOIN
                        lista_servicos_empresa lse ON s.idservico = lse.idservico
                    WHERE
                        lse.idempresa = ? AND (s.nome_serv LIKE ? OR s.descricao_serv LIKE ?)
                    LIMIT ? OFFSET ?";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("issii", $id_empresa, $search_param, $search_param, $servicos_por_pagina, $offset);
            $stmt->execute();
            $result = $stmt->get_result();

            // Tabela de serviços
            echo "<table border='1' class='table'>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nome do Serviço</th>";
            echo "<th>Descrição</th>";
            echo "<th>Preço</th>";
            echo "<th>Duração</th>";
            echo "<th>Funções</th>";
            echo "</tr>";

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['idservico'] . "</td>";
                    echo "<td>" . $row['nome_serv'] . "</td>";
                    echo "<td>" . $row['descricao_serv'] . "</td>";
                    echo "<td>" . $row['preco_serv'] . "</td>";
                    echo "<td>" . formatarDuracao($row['duracao_serv']) . "</td>"; // Formatar a Duração
                    echo "<td>";
                    echo "<a class='butfunc' href='excluir_servico.php?id=" . $row['idservico'] . "'><button type='button' class='btn btn-danger'>Excluir</button></a>";
                    echo "<a class='butfunc' href='edit_servico.php?id=" . $row['idservico'] . "'><button type='button' class='btn btn-success'>Editar</button></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                // Mensagem caso não encontre serviços
                if (!empty($pesquisa)) {
                    echo "<tr><td colspan='6'>Nenhum serviço encontrado para a pesquisa: <strong>" . htmlspecialchars($pesquisa) . "</strong>.</td></tr>";
                } else {
                    echo "<tr><td colspan='6'>Nenhum serviço cadastrado para esta empresa.</td></tr>";
                }
            }

            echo "</table>";

            // Fechar a consulta
            $stmt->close();

            // Paginação
            echo "<nav aria-label='Page navigation example'><ul class='pagination'>";

            // Botão anterior
            echo "<li class='page-item " . ($pagina_atual <= 1 ? 'disabled' : '') . "'>";
            echo "<a class='page-link' href='?pagina=" . ($pagina_atual - 1) . "&pesquisa=" . urlencode($pesquisa) . "' aria-label='Previous'>";
            echo "<span aria-hidden='true'>Anterior</span></a></li>";

            // Links para as páginas
            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<li class='page-item " . ($i == $pagina_atual ? 'active' : '') . "'>";
                echo "<a class='page-link' href='?pagina=$i&pesquisa=" . urlencode($pesquisa) . "'>$i</a></li>";
            }

            // Botão próxima
            echo "<li class='page-item " . ($pagina_atual >= $total_paginas ? 'disabled' : '') . "'>";
            echo "<a class='page-link' href='?pagina=" . ($pagina_atual + 1) . "&pesquisa=" . urlencode($pesquisa) . "' aria-label='Next'>";
            echo "<span aria-hidden='true'>Próximo</span></a></li>";

            echo "</ul></nav>";

            // Fechar a conexão
            $mysqli->close();
            ?>
        </table>
    </div>

    <script>
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement; // Selecionando o pai principal da seta
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
</body>
</html>
