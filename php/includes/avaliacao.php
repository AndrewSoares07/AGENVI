<?php

require('includes/logar-sistema.php');

// Consulta SQL para contar o total de avaliações por estrela e somar as estrelas
$avaliacao_php = "
    SELECT av.estrelas, COUNT(av.idavaliacao) AS total_avaliacoes
    FROM avaliacao av
    WHERE av.idempresa = $idempresa
    GROUP BY av.estrelas
    ORDER BY av.estrelas DESC;
";

// Executa a consulta
$aval = $mysqli->query($avaliacao_php);

// Inicializando variáveis para as estrelas de 1 a 5 e somar o total de estrelas avaliadas
$cinco = 0;
$quatro = 0;
$tres = 0;
$dois = 0;
$um = 0;
$total_estrelas = 0; // Variável para armazenar a soma total das estrelas
$total_avaliacoes = 0; // Variável para armazenar o número total de avaliações

// Percorrendo os resultados e atribuindo os valores às variáveis corretas
while ($avaliacao = $aval->fetch_assoc()) {
    $estrelas = $avaliacao['estrelas'];
    $total_avaliacoes += $avaliacao['total_avaliacoes']; // Soma o total de avaliações
    $total_estrelas += $estrelas * $avaliacao['total_avaliacoes']; // Soma as estrelas (multiplicando pelas avaliações)

    switch ($estrelas) {
        case 5:
            $cinco = $avaliacao['total_avaliacoes'];
            break;
        case 4:
            $quatro = $avaliacao['total_avaliacoes'];
            break;
        case 3:
            $tres = $avaliacao['total_avaliacoes'];
            break;
        case 2:
            $dois = $avaliacao['total_avaliacoes'];
            break;
        case 1:
            $um = $avaliacao['total_avaliacoes'];
            break;
    }
}

// Populando os dados das barras com base nas contagens de estrelas
$data = [
    5 => ($total_avaliacoes > 0) ? ($cinco / $total_avaliacoes) * 100 : 0,
    4 => ($total_avaliacoes > 0) ? ($quatro / $total_avaliacoes) * 100 : 0,
    3 => ($total_avaliacoes > 0) ? ($tres / $total_avaliacoes) * 100 : 0,
    2 => ($total_avaliacoes > 0) ? ($dois / $total_avaliacoes) * 100 : 0,
    1 => ($total_avaliacoes > 0) ? ($um / $total_avaliacoes) * 100 : 0
];

// Calculando a média das avaliações (opcional)
if ($total_avaliacoes > 0) {
    $media_estrelas = $total_estrelas / $total_avaliacoes;
} else {
    $media_estrelas = 0; // Se não houver avaliações, a média será 0
}

?>
