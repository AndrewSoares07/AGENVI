<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/style.css">
   
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .table-container {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        table {
            width: 100%;
            max-width: 1000px;
            border-collapse: collapse;
            border: 1px solid #ddd;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #7C5AFF;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }


    </style>
</head>
<body>
    <?php
        include("includes/logar-sistema.php");
        include ("protect.php");
        include ("includes/navbar.php");
    ?>

    <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Historico</span>
    </div>

        <div class="tudo">
            <div class="table-container">
                <?php
                // Verificar se a conexão com o banco de dados foi estabelecida
                if (!$mysqli) {
                    die("Falha na conexão: " . mysqli_connect_error());
                }

                // Verificar se a variável de sessão 'idempresa' está definida
                if (!isset($_SESSION['idempresa'])) {
                    die("ID da empresa não encontrado na sessão.");
                }

                $idempresa = $_SESSION['idempresa'];

                // Consulta SQL para unir as tabelas 'agendamento', 'cliente' e 'servico'
                $sql = "SELECT a.dt_agendamento, a.status, a.horario_ini, a.horario_fim, 
                           c.nome_cliente, s.nome_serv
                        FROM agendamento a
                        INNER JOIN empresa e ON a.idempresa = e.idempresa
                        INNER JOIN cliente c ON a.idcliente = c.idcliente
                        INNER JOIN servicos s ON a.idservico = s.idservico
                        WHERE a.idempresa = ?";

                // Preparar a consulta
                $stmt = $mysqli->prepare($sql);
                if (!$stmt) {
                    die("Erro na preparação da consulta: " . $mysqli->error);
                }

                // Vincular o parâmetro
                $stmt->bind_param("i", $idempresa);

                // Executar a consulta
                $stmt->execute();

                // Obter o resultado
                $result = $stmt->get_result();

                // Exibição dos dados em uma tabela HTML
                echo "<table>";
                echo "<thead>
                        <tr>
                        <th>Cliente</th>
                        <th>Serviço</th>
                            <th>Data do Agendamento</th>
                            <th>Hora de Início</th>
                            <th>Hora de Fim</th>
                            <th>Status</th>
                        </tr>
                      </thead>";
                echo "<tbody>";

                // Verificar se há resultados e exibir os dados
                if ($result->num_rows > 0) {
                    while ($dados = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($dados['nome_cliente']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['nome_serv']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['dt_agendamento']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['horario_ini']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['horario_fim']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['status']) . "</td>";
                      
                      
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum dado encontrado.</td></tr>";
                }
                echo "</tbody>";
                echo "</table>";

                // Fechar a declaração e a conexão com o banco de dados
                $stmt->close();
                $mysqli->close();
                ?>
            </div>
        </div>
    </section>
    <script>
        let arrow = document.querySelectorAll(".arrow");
        arrow.forEach(arrowElement => {
            arrowElement.addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement; // Selecionando o pai principal da seta
                arrowParent.classList.toggle("showMenu");
            });
        });

        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
