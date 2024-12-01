
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<?php
include ("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");
$idempresa = $_SESSION['idempresa'];

$idempresa = $_SESSION['idempresa'];
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('m');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');





$sql_agendamentos = "SELECT
    c.nome_cliente,
    c.foto_perfil,
    s.nome_serv,
    s.duracao_serv,
    a.preco_ad,
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
WHERE e.idempresa = ?
    AND MONTH(a.dt_agendamento) = ?
    AND YEAR(a.dt_agendamento) = ?
    and a.status = 'em andamento'
ORDER BY a.dt_agendamento ASC;";

$stmtt = $mysqli->prepare($sql_agendamentos);
$stmtt->bind_param('iii', $idempresa, $mes, $ano);
$stmtt->execute();
$resultt = $stmtt->get_result();



if ($resultt->num_rows > 0) {
    while ($roww = $resultt->fetch_assoc()) {
        $collapseId = "collapseExample" . $roww['idagendamento']; // ID único para cada colapso

        echo '<div class="teste">
                <div class="caixapainel">
                    <div class="painel">
                        <div class="painel2">
                            <div class="coluna">
                                <div class="infocli">
                                    <img class="ftcliente" src="arquivos/' . htmlspecialchars($roww['foto_perfil']) . '" alt="">
                                    <p class="nomecliente">' . htmlspecialchars($roww['nome_cliente']) . '</p>
                                </div>
                                <button class="deta" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">Ver detalhes</button>
                            </div>
                            <div class="servico">
                                <p>' . htmlspecialchars($roww['nome_serv']) . '</p>
                                <p class="valor">R$ ' . str_replace('.', ',', htmlspecialchars($roww['preco_ad'])) . '</p>
                            </div>
                            <div class="botoes">
                            <a href="cancelar_agend_emp.php?id='. htmlspecialchars($roww['idagendamento']) .'" class="but-cancelar"> Cancelar</a>
                            <a href="finalizar_agend_emp.php?id='. htmlspecialchars($roww['idagendamento']) .'" class="but-finalizar"> Finalizar</a>
                            </div>
                        </div>
                        <div class="collapse" id="' . $collapseId . '">
                            <div class="card card-body">
                                <div class="coluna">
                                    <p class="func"><strong>Funcionário(a):</strong> ' . htmlspecialchars($roww['nome_func']) . '</p>
                                    <div class="data">
                                        <p class="infosdt"><img class="icons" src="../img/calendario.png" alt=""> ' . date('d/m/Y', strtotime($roww['dt_agendamento'])) . '</p>
                                        <p class="infosdt"><img class="icons" src="../img/historia.png" alt=""> ' . substr($roww['horario_ini'], 0, 5) . ' às ' . substr($roww['horario_fim'], 0, 5) . '</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
} else {
    echo '<div class="div-papito"><br><p>Nenhum agendamento encontrado para essas datas</p><br>';
    include("includes/animacaosemagenda.php");
  echo '</div>';
}
?>


<?php
// Define o fuso horário para Brasília (UTC-3)
date_default_timezone_set('America/Sao_Paulo');

// 1. Obtém a data e hora atual no fuso horário de Brasília
$data_atual = date('Y-m-d H:i:s'); // Formato: YYYY-MM-DD HH:MM:SS

// 2. Atualiza os agendamentos que já passaram da data/hora atual
$sql_atualiza = "UPDATE agendamento
                 SET status = 'finalizado'
                 WHERE idempresa = ? 
                 AND status = 'em andamento'
                 AND dt_agendamento < ? 
                 AND CONCAT(dt_agendamento, ' ', horario_fim) < ?"; // Compara data e horário de término

$stmt_atualiza = $mysqli->prepare($sql_atualiza);
$stmt_atualiza->bind_param("iss", $idempresa, $data_atual, $data_atual);
$stmt_atualiza->execute();
$stmt_atualiza->close();
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">