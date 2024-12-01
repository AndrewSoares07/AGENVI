<?php
include("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");

$idempresa = $_SESSION['idempresa'];
$data = $_GET['data'];

$sqLL = "SELECT
    c.nome_cliente,
    c.foto_perfil,
    s.nome_serv,
    s.duracao_serv,
    s.preco_serv,
    f.nome_func,
    a.horario_fim,
    a.horario_ini,
    a.idagendamento,
    a.dt_agendamento,
    a.status
FROM agendamento a
INNER JOIN cliente c ON c.idcliente = a.idcliente
INNER JOIN empresa e ON e.idempresa = a.idempresa
INNER JOIN servicos s ON s.idservico = a.idservico
INNER JOIN funcionario f ON f.idfuncionario = a.idfuncionario
WHERE e.idempresa = ? AND a.dt_agendamento = ? AND a.status = 'em andamento'";

$stmt = $mysqli->prepare($sqLL);

if ($stmt) {
    // Vincula os parâmetros à consulta
    $stmt->bind_param("is", $idempresa, $data);

    // Executa a consulta
    $stmt->execute();

    // Obtém o resultado
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Geração dinâmica do HTML para os resultados
            echo '<div class="teste">
                <div class="caixapainel">
                    <div class="painel">
                        <div class="painel2">
                            <div class="coluna">
                                <div class="infocli">
                                    <img class="ftcliente" src="arquivos/' . htmlspecialchars($row['foto_perfil']) . '" alt="">
                                    <p class="nomecliente">' . htmlspecialchars($row['nome_cliente']) . '</p>
                                </div>
                                <button class="deta" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $row['idagendamento'] . '" aria-expanded="false" aria-controls="collapse' . $row['idagendamento'] . '">Ver detalhes</button>
                            </div>
                            <div class="servico">
                                <p>' . htmlspecialchars($row['nome_serv']) . '</p>
                                <p class="valor">R$ ' . str_replace('.', ',', htmlspecialchars($row['preco_serv'])) . '</p>
                            </div>
                            <div class="botoes">
                                <a href="cancelar_agend_emp.php?id=' . htmlspecialchars($row['idagendamento']) . '" class="but-cancelar">Cancelar</a>
                                <a href="finalizar_agend_emp.php?id=' . htmlspecialchars($row['idagendamento']) . '" class="but-finalizar">Finalizar</a>
                            </div>
                        </div>
                        <div class="collapse" id="collapse' . $row['idagendamento'] . '">
                            <div class="card card-body">
                                <div class="coluna">
                                    <p class="func"><strong>Funcionário(a):</strong> ' . htmlspecialchars($row['nome_func']) . '</p>
                                    <div class="data">
                                        <p class="infosdt"><img class="icons" src="../img/calendario.png" alt=""> ' . date('d/m/Y', strtotime($row['dt_agendamento'])) . '</p>
                                        <p class="infosdt"><img class="icons" src="../img/historia.png" alt=""> ' . substr($row['horario_ini'], 0, 5) . ' às ' . substr($row['horario_fim'], 0, 5) . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
    } else {
        // Caso nenhum agendamento seja encontrado
        echo '<div class="div-papito"><br><p>Nenhum agendamento encontrado para essa data.</p><br>';
        include("includes/animacaosemagenda.php");
        echo '</div>';
    }

    // Fecha o statement
    $stmt->close();
} else {
    echo 'Erro na preparação da consulta: ' . $mysqli->error;
}

// Fecha a conexão com o banco
$mysqli->close();
?>
