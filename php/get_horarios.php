<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');

$idcliente = $_SESSION['idcliente'];

$mapaDias = [
    'Seg' => 1,
    'Ter' => 2,
    'Qua' => 3,
    'Qui' => 4,
    'Sex' => 5,
    'Sab' => 6,
    'Dom' => 7
];

// Recebe os parâmetros via POST
$data = $_POST['data'] ?? '';
$diaDaSemana = $_POST['diaDaSemana'] ?? '';
$idempresa = $_POST['idempresa'] ?? '';
$idservico = $_POST['idservico'] ?? '';
$periodo = $_POST['periodo'] ?? '';

if (empty($data) || empty($diaDaSemana) || empty($idempresa) || empty($idservico) || empty($periodo)) {
    exit;
}

try {
    $dataDia = new DateTime($data);
    $diaSemanaNum = $dataDia->format('N'); // Dia da semana: 1 (Segunda) a 7 (Domingo)

    // Mapeia o número para o nome textual
    $diaSemanaTexto = array_search($diaSemanaNum, $mapaDias);

    // Verifica se $diaSemanaTexto foi corretamente definido
    if ($diaSemanaTexto === false) {
        echo "Dia da semana não reconhecido.";
        exit;
    }

    // Consulta para obter os agendamentos já existentes no dia
    $select_agend = "SELECT a.horario_ini, a.horario_fim
                     FROM agendamento a
                     WHERE a.dt_agendamento = ? AND a.idcliente = ?";
    
    $stmt_agend = $mysqli->prepare($select_agend);
    if (!$stmt_agend) {
        throw new Exception("Erro na preparação da consulta: " . $mysqli->error);
    }
    $stmt_agend->bind_param("si", $data, $idcliente);

    if (!$stmt_agend->execute()) {
        throw new Exception("Erro na execução da consulta: " . $stmt_agend->error);
    }

    $result_agend = $stmt_agend->get_result();
    $agendamentosExistentes = [];

    while ($row = $result_agend->fetch_assoc()) {
        $agendamentosExistentes[] = [
            'horario_ini' => new DateTime($row['horario_ini']),
            'horario_fim' => new DateTime($row['horario_fim'])
        ];
    }

    $horarios_list = "SELECT
                        e.nome,
                        he.dias_semana,
                        he.horario_ini,
                        he.horario_fim,
                        s.duracao_serv,
                        s.idservico,
                        s.nome_serv
                    FROM empresa e
                    INNER JOIN horarios_empresa he ON he.idempresa = e.idempresa
                    INNER JOIN lista_servicos_empresa ls ON e.idempresa = ls.idempresa
                    INNER JOIN servicos s ON s.idservico = ls.idservico
                    WHERE e.idempresa = ? AND he.dias_semana = ? AND s.idservico = ?
                    ORDER BY he.horario_ini";

    $hhh = $mysqli->prepare($horarios_list);
    if (!$hhh) {
        throw new Exception("Erro na preparação da consulta: " . $mysqli->error);
    }
    $hhh->bind_param("iss", $idempresa, $diaSemanaTexto, $idservico);

    if (!$hhh->execute()) {
        throw new Exception("Erro na execução da consulta: " . $hhh->error);
    }

    $resulte = $hhh->get_result();
    $horariosPorServico = [];

    // Define os períodos
    $periodos = [
        'manha' => ['inicio' => '00:00', 'fim' => '12:00'],
        'tarde' => ['inicio' => '13:00', 'fim' => '18:00'],
        'noite' => ['inicio' => '18:00', 'fim' => '23:00']
    ];

    // Filtra horários com base no período selecionado
    if (!array_key_exists($periodo, $periodos)) {
        echo "Período não reconhecido.";
        exit;
    }
    $periodoInicio = new DateTime($periodos[$periodo]['inicio']);
    $periodoFim = new DateTime($periodos[$periodo]['fim']);

    if ($resulte->num_rows > 0) {
        while ($horarios = $resulte->fetch_assoc()) {
            $horarioIni = new DateTime($horarios['horario_ini']);
            $horarioFim = new DateTime($horarios['horario_fim']);
            $duracaoServ = $horarios['duracao_serv']; // Formato TIME

            // Converte a duração do formato TIME para minutos
            $duracaoMinutos = intval(substr($duracaoServ, 0, 2)) * 60 + intval(substr($duracaoServ, 3, 2));
            if ($duracaoMinutos <= 0) {
                continue; // Ignora se a duração for zero ou negativa
            }

            // Verifica se os horários estão dentro do período selecionado
            if ($horarioIni > $periodoFim || $horarioFim < $periodoInicio) {
                continue; // Pula para a próxima iteração se os horários não coincidirem com o período selecionado
            }

            // Ajusta o início e fim do horário de acordo com o período selecionado
            $horarioIni = max($horarioIni, $periodoInicio);
            $horarioFim = min($horarioFim, $periodoFim);

            // Inicializa o array de horários para o serviço, se necessário
            if (!isset($horariosPorServico[$horarios['nome_serv']])) {
                $horariosPorServico[$horarios['nome_serv']] = [];
            }

            // Gera os intervalos de acordo com a duração
            $intervalos = [];
            while ($horarioIni < $horarioFim) {
                $intervaloInicio = $horarioIni->format('H:i');

                // Calcula o próximo intervalo
                $proximo = clone $horarioIni;
                $proximo->add(new DateInterval('PT' . $duracaoMinutos . 'M'));

                // Verifica se o próximo intervalo ultrapassa o horário de fim
                if ($proximo > $horarioFim) {
                    $proximo = $horarioFim;
                }

                $intervaloFim = $proximo->format('H:i');

                // Verifica se o intervalo já foi agendado
                $conflito = false;
                foreach ($agendamentosExistentes as $agendamento) {
                    if (($horarioIni >= $agendamento['horario_ini'] && $horarioIni < $agendamento['horario_fim']) || 
                        ($proximo > $agendamento['horario_ini'] && $proximo <= $agendamento['horario_fim'])) {
                        $conflito = true;
                        break;
                    }
                }

                if (!$conflito) {
                    $intervalos[] = ['inicio' => $intervaloInicio, 'fim' => $intervaloFim];
                }

                // Atualiza o início para o próximo intervalo
                $horarioIni = $proximo;
            }

            // Adiciona os intervalos ao array de horários por serviço
            if (!empty($intervalos)) {
                $horariosPorServico[$horarios['nome_serv']] = $intervalos;
            }
        }

        // Exibe os horários se existirem
        if (!empty($horariosPorServico)) {
            $exibiuHorarios = false;
            foreach ($horariosPorServico as $nomeServico => $intervalos) {
                echo "<div class='sep2'></div>";
                echo "<div class='horario-pai'>";
        
                // Verifica se existem intervalos para exibir
                if (!empty($intervalos)) {
                    $exibiuHorarios = true; // Marca que há horários para exibir
                    foreach ($intervalos as $intervalo) {
                        echo "<button class='horario-item' data-inicio='" . htmlspecialchars($intervalo['inicio']) . "' data-fim='" . htmlspecialchars($intervalo['fim']) . "'>";
                        echo "<div class='horario-inicio'>" . htmlspecialchars($intervalo['inicio']) . "</div> - ";
                        echo "<div class='horario-fim'>" . htmlspecialchars($intervalo['fim']) . "</div>";
                        echo "</button><br>";
                    }
                }
                echo "</div>";
            }
        
            // Se não houver horários exibidos, mostra a imagem
            if (!$exibiuHorarios) {
                echo "<div class='div_img_hr'><img src='../img/senhora.png' alt='Não há horários disponíveis para este período'  class='img_hr'>
                <h5>Horários indisponíveis nesse dia</h5>
                </div>";
            }
        
        } else {
            // Exibe a imagem caso não haja horários por serviço ou a consulta não retornar resultados
            echo "<div class='div_img_hr'><img src='../img/senhora.png' alt='Não há horários disponíveis para este período'  class='img_hr'>
            <h5>Horários indisponíveis nesse dia</h5>
            </div>";
        }
    }        
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>