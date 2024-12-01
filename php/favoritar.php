<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');

$idcliente = $_SESSION['idcliente'];

if (isset($_POST['idempresa']) && isset($_POST['action'])) {
    $idempresa = intval($_POST['idempresa']);
    $action = $_POST['action'];

    if ($action === 'favorite') {
        $sql = "INSERT INTO favoritos (idcliente, idempresa) VALUES (?, ?)";
    } else if ($action === 'unfavorite') {
        $sql = "DELETE FROM favoritos WHERE idcliente = ? AND idempresa = ?";
    }

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ii', $idcliente, $idempresa);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false]);
}

$mysqli->close();
?>
