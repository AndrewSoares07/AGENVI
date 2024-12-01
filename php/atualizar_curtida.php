<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');

$idcliente = $_SESSION['idcliente'];

if (isset($_POST['idavaliacao'])) {
    $idAvaliacao = $_POST['idavaliacao'];
    
    // Verificar se o usuário já curtiu ou não
    $sql = "SELECT * FROM curtidas WHERE idavaliacao = ? AND idcliente = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $idAvaliacao, $idcliente);
    $stmt->execute();
    $curtida = $stmt->get_result()->fetch_assoc();
    
    if ($curtida) {
        // Se já curtiu, descurte
        $sql = "DELETE FROM curtidas WHERE idavaliacao = ? AND idcliente = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $idAvaliacao, $idcliente);
        $stmt->execute();
        $curtido = false;
    } else {
        // Se não curtiu, registra a curtida
        $sql = "INSERT INTO curtidas (idavaliacao, idcliente) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $idAvaliacao, $idcliente);
        $stmt->execute();
        $curtido = true;
    }

    // Retorna o número atualizado de curtidas para o JavaScript
    $sql = "SELECT COUNT(*) AS total_curtidas FROM curtidas WHERE idavaliacao = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $idAvaliacao);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode(['success' => true, 'curtido' => $curtido, 'total_curtidas' => $result['total_curtidas']]);
}
?>
