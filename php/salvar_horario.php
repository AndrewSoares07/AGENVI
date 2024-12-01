<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agenvi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$employee_id = isset($_POST['idfuncionario']) ? $_POST['idfuncionario'] : null;
$days_of_week = isset($_POST['dia_semana']) ? $_POST['dia_semana'] : [];
$start_times = isset($_POST['horario_ini']) ? $_POST['horario_ini'] : [];
$end_times = isset($_POST['horario_fim']) ? $_POST['horario_fim'] : [];

$success = true;

foreach ($days_of_week as $day) {
    $start_time = isset($start_times[$day]) ? $start_times[$day] : null;
    $end_time = isset($end_times[$day]) ? $end_times[$day] : null;


    $check_sql = "SELECT * FROM horario_func WHERE idfuncionario = '$employee_id' AND dia_semana = '$day'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {

        echo "Já existe um horário cadastrado para $day.<br>";
        continue;
    }


    if (!empty($start_time) && !empty($end_time)) {
        $sql = "INSERT INTO `horario_func`(`idfuncionario`, `dia_semana`, `horario_ini`, `horario_fim`) VALUES ('$employee_id', '$day', '$start_time', '$end_time')";
        if ($conn->query($sql) !== TRUE) {
            $success = false;
            echo "Erro ao adicionar horário para $day: " . $conn->error . "<br>";
        } else {
            echo "Horário adicionado para $day com sucesso.<br>";
        }
    }
}

$conn->close();

if ($success) {
    header("Location: lista_func.php");
} else {
    echo "Ocorreu um erro ao adicionar os horários.<br>";
}
?>

