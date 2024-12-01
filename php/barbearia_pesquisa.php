<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');

$idcliente = $_SESSION['idcliente'];

if (!$mysqli) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

function roundToHalf($num) {
    return round($num * 2) / 2;
}

// Obter o estado do cliente a partir do CEP
$sql_cliente = "SELECT l.uf 
                FROM cliente c 
                INNER JOIN localidade l ON c.cep = l.cep 
                WHERE c.idcliente = ?";
$stmt_cliente = $mysqli->prepare($sql_cliente);
$stmt_cliente->bind_param('i', $idcliente);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();
$cliente_data = $result_cliente->fetch_assoc();

$cliente_estado = $cliente_data['uf'];
$stmt_cliente->close(); // Fechando a instrução

// Variável para armazenar o título dinâmico
$title = "Empresas de barbearia mais bem avaliadas";

// Lógica para pesquisa
if (isset($_GET['buscar'])) {
    $buscar = '%' . $mysqli->real_escape_string($_GET['buscar']) . '%';

    $sql = "SELECT e.idempresa, e.nome, e.foto_empresa, e.foto_amb1, l.cidade, 
            COALESCE(AVG(a.estrelas), 0) AS media_estrelas, 
            COUNT(a.idavaliacao) AS total_avaliacoes,
            CASE WHEN l.uf = ? THEN 1 ELSE 0 END AS is_same_uf,
            (SELECT COUNT(*) FROM favoritos f WHERE f.idcliente = ? AND f.idempresa = e.idempresa) AS is_favorited
            FROM empresa e 
            INNER JOIN localidade l ON e.cep = l.cep
            LEFT JOIN avaliacao a ON e.idempresa = a.idempresa
            WHERE (e.nome LIKE ? OR e.nome_fantasia LIKE ?)
            GROUP BY e.idempresa, e.nome, e.foto_empresa, e.foto_amb1, l.cidade, l.uf
            ORDER BY is_same_uf DESC, total_avaliacoes DESC, media_estrelas DESC";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ssss', $cliente_estado, $idcliente, $buscar, $buscar);
    $stmt->execute();
    $result = $stmt->get_result();

    // Atualiza o título se houver um termo de pesquisa
    $title = "Resultado da busca por: " . htmlspecialchars($_GET['buscar']);
} else {
    // Consulta padrão sem pesquisa, exibindo todas as empresas de tipo 'advocacia'
    $sql = "SELECT e.idempresa, e.nome, e.foto_empresa, e.foto_amb1, l.cidade, 
            COALESCE(AVG(a.estrelas), 0) AS media_estrelas, 
            COUNT(a.idavaliacao) AS total_avaliacoes,
            CASE WHEN l.uf = ? THEN 1 ELSE 0 END AS is_same_uf,
            (SELECT COUNT(*) FROM favoritos f WHERE f.idcliente = ? AND f.idempresa = e.idempresa) AS is_favorited
            FROM empresa e 
            INNER JOIN localidade l ON e.cep = l.cep
            LEFT JOIN avaliacao a ON e.idempresa = a.idempresa
            WHERE e.tipo = 'barbearia'
            GROUP BY e.idempresa, e.nome, e.foto_empresa, e.foto_amb1, l.cidade, l.uf
            ORDER BY is_same_uf DESC, total_avaliacoes DESC, media_estrelas DESC";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', $cliente_estado, $idcliente);
    $stmt->execute();
    $result = $stmt->get_result();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/teste.css">
</head>
<body>
<?php include("includes/header-nav.php"); ?>
<br>
<h3>Destaques</h3>
<h4><?= htmlspecialchars($title) ?></h4>
<br>
<div class="central">

<?php while ($row = $result->fetch_assoc()) { ?>


<a href="agendamento.php?id=<?= htmlspecialchars($row['idempresa']); ?>">

    <div class="pai">

    <button class="favorite-button" data-idempresa="<?= $row['idempresa'] ?>">
        <img src="../img/<?= $row['is_favorited'] ? 'heath.png' : 'coracao.png' ?>"class="heart-icon" />
    </button>
    

        <?php
          
            $foto_amb1_path = 'arquivos/' . htmlspecialchars($row['foto_amb1']);
            echo "<div class='filho' style='background-image: url($foto_amb1_path); background-size: cover;'></div>";
        ?>
        
        <?php
          $foto_amb2_path = 'arquivos/' . htmlspecialchars($row['foto_empresa']);
          echo "<div class='filho2' style='background-image: url($foto_amb2_path); background-size: cover;'></div>";
        
        ?>
    <div class="footercard">

                 <div class="txt1">
                    <h5 style="color: #7C5AFF;   font-weight: bold;">
                        <?= htmlspecialchars($row['nome']) ?>
                    </h5>
                </div>


            <div class="star-rating">
                        <?php
                        $fullStars = floor($row['media_estrelas']);
                        $halfStar = ($row['media_estrelas'] - $fullStars) >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        for ($i = 0; $i < $fullStars; $i++) {
                            echo '  <img class="strela" src="../img/estrela.png" alt=""> ';
                        }
                        if ($halfStar) {
                            echo '  <img class="strela" src="../img/estrela.png" alt=""> ';
                        }
                        for ($i = 0; $i < $emptyStars; $i++) {
                            echo '  <img class="strela" src="../img/estrela.png" alt=""> ';
                        }
                        ?>
                    </div>
                    
                <div class="txt2">

                    <p class="end"> <?= htmlspecialchars($row['cidade']) ?> </p>
                </div>
        </div>
    </div>
</a>

<?php } ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
      $(document).ready(function () {
    $('.favorite-button').on('click', function (e) {
        e.preventDefault();
        var button = $(this);
        var idempresa = button.data('idempresa');
        var icon = button.find('.heart-icon');
        var currentIconSrc = icon.attr('src');
        var action;

        // Verifique o ícone atual para determinar a ação
        if (currentIconSrc === '../img/heath.png') {
            action = 'unfavorite';
            icon.attr('src', '../img/coracao.png'); // Troque para o ícone "favorito" imediatamente
        } else {
            action = 'favorite';
            icon.attr('src', '../img/heath.png'); // Troque para o ícone "não favorito" imediatamente
        }

        $.ajax({
            url: 'favoritar.php',
            method: 'POST',
            data: { idempresa: idempresa, action: action },
            success: function (response) {
                // Aqui você pode tratar a resposta se necessário
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Erro ao favoritar:', textStatus, errorThrown);
                // Opcionalmente, reverta a troca de ícone se a requisição falhar
                if (action === 'favorite') {
                    icon.attr('src', '../img/coracao.png'); // Reverte se falhar
                } else {
                    icon.attr('src', '../img/heath.png'); // Reverte se falhar
                }
            }
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
$stmt->close();
$mysqli->close();
?>
