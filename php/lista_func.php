<?php
include("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");
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
      <span class="text">Funcionários</span>
    </div>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a class='novofunc' href='cadastrar_func.php'>Novo funcionário <img class='mais' src='../img/mais.png' alt=''></a>
                <a class='button' href='../php/relatorio/relatorio/gerar_relatorio.php?tipo=func'>Gerar Relatório</a>
            </div>

            <form method="GET" action="" class="d-flex ms-3">
                <input type="text" name="pesquisa" class="barrapes" placeholder="Pesquisar Funcionários" value="<?php echo isset($_GET['pesquisa']) ? htmlspecialchars($_GET['pesquisa']) : ''; ?>">
                <button type="submit" class="butpes"><i class='bx bx-search' style='color:#ffffff'></i></button>
            </form>
        </div>

        <?php
        // Pagination variables
        $limit = 5; // Number of entries to show per page
        $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Current page
        $offset = ($pagina_atual - 1) * $limit; // Calculate offset

        // Search functionality
        $pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

        $id_empresa = $_SESSION['idempresa'];

        // Count total records for pagination
        $count_sql = "SELECT COUNT(*) as total FROM funcionario f
                      INNER JOIN lista_funcionario_empresa lfe ON f.idfuncionario = lfe.idfuncionario
                      WHERE lfe.idempresa = ? AND (f.nome_func LIKE ? OR f.email LIKE ? OR f.cel LIKE ? OR f.cpf LIKE ?)";
        $count_stmt = $mysqli->prepare($count_sql);
        $like_search = '%' . $pesquisa . '%';
        $count_stmt->bind_param("sssss", $id_empresa, $like_search, $like_search, $like_search, $like_search);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total_records = $count_result->fetch_assoc()['total'];
        $total_paginas = ceil($total_records / $limit);

        // Main query for fetching employees
        $sql = "SELECT
                    f.idfuncionario,
                    f.nome_func,
                    f.email,
                    f.cel,
                    f.cpf,
                    GROUP_CONCAT(DISTINCT h.dia_semana ORDER BY FIELD(h.dia_semana, 'Seg', 'Ter', 'Qua', 'Qui', 'Sex') SEPARATOR ', ') AS dias_trabalho,
                    GROUP_CONCAT(DISTINCT s.nome_serv ORDER BY s.nome_serv SEPARATOR ', ') AS servicos
                FROM
                    funcionario f
                LEFT JOIN
                    horario_func h ON f.idfuncionario = h.idfuncionario
                INNER JOIN
                    lista_funcionario_empresa lfe ON f.idfuncionario = lfe.idfuncionario
                LEFT JOIN
                    servicos_funcionario sf ON f.idfuncionario = sf.idfuncionario
                LEFT JOIN
                    servicos s ON sf.idservico = s.idservico
                WHERE
                    lfe.idempresa = ? AND (f.nome_func LIKE ? OR f.email LIKE ? OR f.cel LIKE ? OR f.cpf LIKE ?)
                GROUP BY
                    f.idfuncionario
                LIMIT ? OFFSET ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssii", $id_empresa, $like_search, $like_search, $like_search, $like_search, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

    echo "<table class='table table-striped'>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Nome</th>";
echo "<th class='tdt'>Dias de Trabalho</th>";
echo "<th>Serviços</th>";
echo "<th>Email</th>";
echo "<th>Celular</th>";
echo "<th>CPF</th>";
echo "<th>Funções</th>";
echo "</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['idfuncionario'] . "</td>";
        echo "<td>" . $row['nome_func'] . "</td>";
        echo "<td>" . $row['dias_trabalho'] . "</td>";
    
        echo "<td class='servicos-cell' title='" . htmlspecialchars($row['servicos']) . "'>" . htmlspecialchars($row['servicos']) . "</td>";
        
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['cel'] . "</td>";
        echo "<td>" . $row['cpf'] . "</td>";
        echo "<td>";
        echo "<a class='butfunc' href='excluir_func.php?id=" . $row['idfuncionario'] . "'><button type='button' class='btn btn-danger'>Excluir</button></a>";
        echo "<a class='butfunc' href='edit_func.php?id=" . $row['idfuncionario'] . "'><button type='button' class='btn btn-success'>Editar</button></a>";
       
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>Nenhum funcionário encontrado para a pesquisa: <strong>" . htmlspecialchars($pesquisa) . "</strong>.</td></tr>";
}
echo "</table>";


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

        $stmt->close();
        $mysqli->close();
        ?>
    </div>
</section>

<script>
  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e) => {
      let arrowParent = e.target.parentElement.parentElement; // selecting main parent of arrow
      arrowParent.classList.toggle("showMenu");
    });
  }
  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
  sidebarBtn.addEventListener("click", () => {
    sidebar.classList.toggle("close");
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJo" crossorigin="anonymous"></script>
</body>
</html>
