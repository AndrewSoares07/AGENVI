<?php
// Iniciar a sessão
if (!isset($_SESSION)) {
    session_start();
}

// Verificar se a sessão está ativa
if (!isset($_SESSION['idempresa'])) {
    die("Você não pode acessar essa página pois não está logado <p><a href=\"login-empresa.php\">entrar</a></p>");
}

// Exibir erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conectar ao banco de dados (ajuste com suas credenciais)
$mysqli = new mysqli("localhost", "root", "", "agenvi");

$idempresa = $_SESSION['idempresa'];

// Verificar a conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

// Incluir a biblioteca mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Obter o parâmetro tipo e validar
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'cliente';
$tiposValidos = ['cliente', 'agen', 'emp', 'func', 'serv'];
if (!in_array($tipo, $tiposValidos)) {
    $tipo = 'cliente';
}

// Determinar a consulta SQL e o título com base no tipo
switch ($tipo) {
    case 'cliente':
        $sql = "SELECT * FROM agendamento a 
        INNER JOIN cliente c ON a.idcliente = c.idcliente 
        WHERE idempresa = '$idempresa' 
        ORDER BY 
            FIELD(a.status, 'em andamento', 'finalizado', 'cancelado')";
        $titulo = 'Clientes';
        break;
    case 'agen':
        $sql = "SELECT * FROM agendamento WHERE idempresa = '$idempresa'";
        $titulo = 'Agendamentos';
        break;
    case 'emp':
        $sql = "SELECT * FROM empresa";
        $titulo = 'Empresas';
        break;
        case 'serv':
        // Consulta para os serviços
        $sql = "
        SELECT 
            s.idservico,
            s.nome_serv,
            s.preco_serv,
            s.descricao_serv,
            s.duracao_serv,
            COUNT(a.idagendamento) AS total_agendamentos,
            SUM(CASE WHEN a.status = 'finalizado' THEN 1 ELSE 0 END) AS finalizados,
            SUM(CASE WHEN a.status = 'em andamento' THEN 1 ELSE 0 END) AS em_andamento,
            SUM(CASE WHEN a.status = 'cancelado' THEN 1 ELSE 0 END) AS cancelados
        FROM lista_servicos_empresa l
        INNER JOIN servicos s ON l.idservico = s.idservico
        LEFT JOIN agendamento a ON a.idservico = s.idservico AND a.idempresa = '$idempresa'
        WHERE l.idempresa = '$idempresa'
        GROUP BY s.idservico
        ORDER BY total_agendamentos DESC;
        ";
        $titulo = 'Serviços';
            break;
        
    case 'func':
        $sql = "SELECT
                f.idfuncionario,
                f.nome_func,
                f.email,
                f.cel,
                f.dt_nasc,
                f.cpf,
                GROUP_CONCAT(DISTINCT h.dia_semana ORDER BY FIELD(h.dia_semana, 'Seg', 'Ter', 'Qua', 'Qui', 'Sex') SEPARATOR ', ') AS dias_trabalho,
                GROUP_CONCAT(DISTINCT s.nome_serv ORDER BY s.nome_serv SEPARATOR ', ') AS servicos,
                (SELECT COUNT(*) FROM agendamento a WHERE a.idfuncionario = f.idfuncionario AND a.status = 'finalizado' AND a.idempresa = '$idempresa') AS finalizados,
                (SELECT COUNT(*) FROM agendamento a WHERE a.idfuncionario = f.idfuncionario AND a.status = 'em andamento' AND a.idempresa = '$idempresa') AS em_andamento,
                (SELECT COUNT(*) FROM agendamento a WHERE a.idfuncionario = f.idfuncionario AND a.status = 'cancelado' AND a.idempresa = '$idempresa') AS cancelados,
                (SELECT COUNT(*) FROM agendamento a WHERE a.idfuncionario = f.idfuncionario AND a.idempresa = '$idempresa') AS total_agendamentos
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
                lfe.idempresa = '$idempresa'
            GROUP BY
                f.idfuncionario";
        $titulo = 'Funcionários';
        break;
    default:
        $sql = "SELECT * FROM cliente";
        $titulo = 'Clientes';
        break;
}

// Executar a consulta SQL e tratar erros
$result = $mysqli->query($sql);
if (!$result) {
    die("Erro na consulta ao banco de dados: " . $mysqli->error);
}

// Consulta para contar os agendamentos por status (finalizado, em andamento, cancelado)
if (in_array($tipo, ['agen', 'cliente'])) {
    $countQuery = "SELECT status, COUNT(*) as total FROM agendamento WHERE idempresa = '$idempresa' GROUP BY status";
    $countResult = $mysqli->query($countQuery);
    $totais = [
        'finalizado' => 0,
        'em andamento' => 0,
        'cancelado' => 0
    ];

    if ($countResult) {
        while ($row = $countResult->fetch_assoc()) {
            if (isset($totais[$row['status']])) {
                $totais[$row['status']] = $row['total'];
            }
        }
    }
}

// Função para aplicar a máscara ao CPF
function formatarCPF($cpf) {
    if (empty($cpf)) return $cpf;
    // Remove todos os caracteres não numéricos
    $cpf = preg_replace('/\D/', '', $cpf);
    // Aplica a máscara
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}

// Função para aplicar a máscara ao telefone
function formatarTelefone($telefone) {
    if (empty($telefone)) return $telefone;
    // Remove todos os caracteres não numéricos
    $telefone = preg_replace('/\D/', '', $telefone);
    // Aplica a máscara
    return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
}

// Função para limitar o email a 5 caracteres
function truncarEmail($email) {
    if (empty($email)) return $email;
    return strlen($email) > 5 ? substr($email, 0, 5) . '...' : $email;
}

// Função para truncar texto com "..."
function truncarComPontos($texto, $limite = 5) {
    $texto = $texto ?? '';  // Garante que o texto não seja null
    return (strlen($texto) > $limite) ? substr($texto, 0, $limite) . "..." : $texto;
}

// Estilo CSS para a tabela
$css = '
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 20px;
        }
        .container {
            width: 80%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #471687;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #471687;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
    </style>
';

$html = '
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
        <title>Relatório de ' . $titulo . '</title>
        ' . $css . '
    </head>
    <body>
        <div class="container">
            <h1>Relatório de ' . $titulo . '</h1>';

if (in_array($tipo, ['agen', 'cliente'])) {
    $html .= '
            <div style="text-align: center; margin-bottom: 20px;">
                <p><strong>Total de Agendamentos por Status:</strong></p>
                <p>Finalizados: ' . $totais['finalizado'] . '</p>
                <p>Em Andamento: ' . $totais['em andamento'] . '</p>
                <p>Cancelados: ' . $totais['cancelado'] . '</p>
            </div>';
}

$html .= '
            <table>
                <thead>
                    <tr>';

switch ($tipo) {
    case 'cliente':
        $html .= '<th>ID</th><th>Nome</th><th>Gênero</th><th>Data de Nascimento</th><th>CEP</th><th>Telefone</th><th>Email</th><th>Data</th><th>Horario Início</th><th>Horario Final</th><th>Situação</th>';
        break;
    case 'agen':
        $html .= '<th>ID Agendamento</th><th>ID Cliente</th><th>ID Empresa</th><th>ID Serviço</th><th>ID Funcionário</th><th>Status</th><th>Data</th><th>Horario Início</th><th>Horario Final</th>';
        break;
    case 'emp':
        $html .= '<th>ID Empresa</th><th>Nível</th><th>Nome</th><th>Nome Fantasia</th><th>Tipo</th><th>CNPJ/CPF</th><th>Telefone</th><th>CEP</th><th>Número</th><th>Email</th>';
        break;
    case 'func':
        $html .= '<th>ID Funcionário</th><th>Nome</th><th>Dias de Trabalho</th><th>Email</th><th>Data de Nascimento</th><th>Telefone</th><th>CPF</th><th>Total de Agendamentos</th><th>Finalizados</th><th>Em Andamento</th><th>Cancelados</th>';
        break;
        case 'serv':
            $html .= '
            <th>ID Serviço</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Descrição</th>
            <th>Duração</th>
            <th>Total de Agendamentos</th>
            <th>Finalizados</th>
            <th>Em Andamento</th>
            <th>Cancelados</th>
            <th>Total Ganho</th>';  // Nova coluna para exibir o ganho por serviço
        
                // Calcular o total de ganho por serviço
                $totalGanhoGeral = 0; // Inicialização do total de ganho geral
                while ($dados = $result->fetch_assoc()) {
                    // Calcular o total ganho por cada serviço (finalizados * preço_serv)
                    $totalGanhoPorServico = $dados['finalizados'] * $dados['preco_serv'];
                    $totalGanhoGeral += $totalGanhoPorServico; // Atualizar o total de ganho geral
            
                    $html .= '<tr>';
                    $html .= '<td>' . $dados['idservico'] . '</td>';
                    $html .= '<td>' . $dados['nome_serv'] . '</td>';
                    $html .= '<td>' . number_format($dados['preco_serv'], 2, ',', '.') . '</td>';
                    $html .= '<td>' . $dados['descricao_serv'] . '</td>';
                    $html .= '<td>' . $dados['duracao_serv'] . '</td>';
                    $html .= '<td>' . $dados['total_agendamentos'] . '</td>';
                    $html .= '<td>' . $dados['finalizados'] . '</td>';
                    $html .= '<td>' . $dados['em_andamento'] . '</td>';
                    $html .= '<td>' . $dados['cancelados'] . '</td>';
                    $html .= '<td>' . number_format($totalGanhoPorServico, 2, ',', '.') . '</td>';  // Exibir o total ganho por serviço
                    $html .= '</tr>';
                }
                
                // Exibir o total ganho de todos os serviços após a tabela de serviços.
                $html .= '
                    <tr>
                        <td colspan="9" style="text-align: right;"><strong>Total Geral:</strong></td>
                        <td><strong>' . number_format($totalGanhoGeral, 2, ',', '.') . '</strong></td>
                    </tr>
                ';
                break;
        
}

$html .= '</tr>
                </thead>
                <tbody>';

while ($dados = $result->fetch_assoc()) {
    $html .= '<tr>';

    switch ($tipo) {
        case 'cliente':
            $html .= '<td>' . $dados['idcliente'] . '</td><td>' . $dados['nome_cliente'] . '</td><td>' . $dados['genero'] . '</td><td>' . $dados['data_nasc'] . '</td><td>' . $dados['cep'] . '</td><td>' . formatarTelefone($dados['telefone']) . '</td><td>' . truncarEmail($dados['email']) . '</td><td>' .date('d/m/Y', strtotime($dados['dt_agendamento'])). '</th><td>' . $dados['horario_ini'] . '</td><td>' . $dados['horario_fim'] . '</td><td>' . $dados['status'] . '</td>';
            break;
        case 'agen':
            $html .= '<td>' . htmlspecialchars($dados['idagendamento'] ?? 'N/A') . '</td><td>' . htmlspecialchars($dados['idcliente'] ?? 'N/A') . '</td><td>' . htmlspecialchars($dados['idempresa'] ?? 'N/A') . '</td><td>' . htmlspecialchars($dados['idservico'] ?? 'N/A') . '</td><td>' . htmlspecialchars($dados['idfuncionario'] ?? 'N/A') . '</td><td>' . htmlspecialchars($dados['status'] ?? 'N/A') . '</td><td>' . htmlspecialchars($dados['data_agendamento'] ?? 'N/A') . '</td><td>' . htmlspecialchars($dados['horario_ini'] ?? 'N/A') . '</td><td>' . htmlspecialchars($dados['horario_fim'] ?? 'N/A') . '</td>';
            break;
        case 'emp':
            $html .= '<td>' . $dados['idempresa'] . '</td><td>' . $dados['nivel'] . '</td><td>' . $dados['nome'] . '</td><td>' . $dados['nome_fantasia'] . '</td><td>' . $dados['tipo'] . '</td><td>' . $dados['CNPJ_CPF'] . '</td><td>' . formatarTelefone($dados['telefone']) . '</td><td>' . $dados['cep'] . '</td><td>' . $dados['numero'] . '</td><td>' . truncarEmail($dados['email']) . '</td>';
            break;
        case 'func':
            $html .= '<td>' . $dados['idfuncionario'] . '</td>
                      <td>' . $dados['nome_func'] . '</td>
                      <td>' . $dados['dias_trabalho'] . '</td>
                      <td>' . truncarEmail($dados['email']) . '</td>
                      <td>' . $dados['dt_nasc'] . '</td>
                      <td>' . formatarTelefone($dados['cel']) . '</td>
                      <td>' . formatarCPF($dados['cpf']) . '</td>
                      <td>' . $dados['total_agendamentos'] . '</td>
                      <td>' . $dados['finalizados'] . '</td>
                      <td>' . $dados['em_andamento'] . '</td>
                      <td>' . $dados['cancelados'] . '</td>';
            break;
    }
   

    $html .= '</tr>';
}

$html .= '
                </tbody>
            </table>
        </div>
    </body>
    </html>';

// Criar o arquivo PDF usando mPDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('relatorio_' . $titulo . '.pdf', 'I'); // Exibir diretamente no navegador
?>
