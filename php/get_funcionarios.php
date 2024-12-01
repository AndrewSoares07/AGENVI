<?php

include('includes/logar-sistema.php');

$semana = $_POST['diaDaSemana'];
$idservico = $_POST['idservico'];
$idempresa = $_POST['idempresa'];
$inicio = $_POST['inicio'];
$fim = $_POST['fim'];
$dataSelecionada = $_POST['data'];

if (empty($semana) || empty($idservico) || empty($idempresa) || empty($fim) || empty($inicio)) {
    echo "Erro: parâmetros insuficientes.";
} else {
    $sql = "SELECT DISTINCT
        f.idfuncionario,
        f.nome_func,
        f.status,
        f.foto_func,
        s.idservico,
        e.idempresa,
        hf.dia_semana,
        hf.horario_ini,
        hf.horario_fim
    FROM funcionario f
    INNER JOIN horario_func hf ON hf.idfuncionario = f.idfuncionario
    INNER JOIN servicos_funcionario sf ON sf.idfuncionario = f.idfuncionario
    INNER JOIN servicos s ON s.idservico = sf.idservico
    INNER JOIN lista_funcionario_empresa lf ON lf.idfuncionario = f.idfuncionario
    INNER JOIN empresa e ON lf.idempresa = e.idempresa 
    WHERE e.idempresa = ? 
      AND s.idservico = ? 
      AND hf.dia_semana = ? 
      AND hf.horario_ini <= ? 
      AND hf.horario_fim >= ?
      AND f.status = 'ativo' 
      AND NOT EXISTS (
          SELECT 1
          FROM agendamento a 
          WHERE a.idfuncionario = f.idfuncionario 
            AND a.dt_agendamento = ? 
            AND (
                (a.horario_ini <= ? AND a.horario_fim > ?) OR
                (a.horario_ini < ? AND a.horario_fim >= ?) OR
                (a.horario_ini >= ? AND a.horario_ini < ?)
            )
      )";

    $execute = $mysqli->prepare($sql);

    // Ajuste os parâmetros de ligação
    $execute->bind_param("iissssssssss", $idempresa, $idservico, $semana, $inicio, $fim, $dataSelecionada, $inicio, $inicio, $fim, $fim, $inicio, $fim);

    $execute->execute();
    $result = $execute->get_result();

    if ($result->num_rows > 0) {
        echo "<h5>Selecione um funcionário</h5><br>";
        echo"<div class='div_func'>";
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="func_css">
                
                <img src="arquivos/<?php echo $row['foto_func']; ?>" alt="Foto do funcionário">
                
                
                <input type="radio" name="funcionario" id="funcionario_<?php echo $row['idfuncionario']; ?>" value="<?php echo $row['idfuncionario']; ?>">
                <label for="funcionario_<?php echo $row['idfuncionario']; ?>"><?php echo $row['nome_func']; ?></label><br>
            </div>
            <?php
        }
    } else {
        echo "<h5>Nenhum funcionário disponível neste horário.</h5>";
    }
    echo"</div>";
    $execute->close();
    $mysqli->close();
}
?>
