<?php
include('includes/logar-sistema.php');

include('protect_cliente.php');

$idcliente = $_SESSION['idcliente'];

$codigo = $_GET['idempresa'];

$sql = "SELECT 
e.nome,
e.nome_fantasia,
e.tipo,
e.CNPJ_CPF,
e.telefone,
e.email,
e.numero,
l.cep,
l.logradouro,
l.bairro,
l.UF,
l.cidade,
e.foto_empresa,
e.foto_amb1,
e.foto_amb2,
e.foto_amb3,
e.foto_amb4,
e.foto_amb5,
e.foto_amb6,
e.complemento,
p.port_img1,
p.port_img2,
p.port_img3,
p.port_img4,
p.port_img5,
p.port_img6,
p.port_img7,
p.port_img8,
p.port_img9,
p.port_img10,
p.port_img11,
p.port_img12
FROM empresa e
inner join localidade l on l.cep = e.cep
right join portifolio_empresa p on p.idempresa = e.idempresa
WHERE e.idempresa = $codigo";

$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $empresa = $result->fetch_assoc();

    $bairro = $empresa['bairro'];
    $uf = $empresa['UF'];
    $cidade = $empresa['cidade'];
    $logradouro = $empresa['logradouro'];
    $numero = $empresa['numero'];
    $cep = $empresa['cep'];
    $nome = $empresa['nome'];
    $email = $empresa['email'];
    $nomef = $empresa['nome_fantasia'];
    $tel = $empresa['telefone'];
    $img1 = $empresa['foto_empresa'];
    $ft1 = $empresa['foto_amb1'];
    $ft2 = $empresa['foto_amb2'];
    $ft3 = $empresa['foto_amb3'];
    $ft4 = $empresa['foto_amb4'];
    $ft5 = $empresa['foto_amb5'];
    $ft6 = $empresa['foto_amb6'];
    $comp = $empresa['complemento'];
    $pot1 = $empresa['port_img1'];
    $pot2 = $empresa['port_img2'];
    $pot3 = $empresa['port_img3'];
    $pot4 = $empresa['port_img4'];
    $pot5 = $empresa['port_img5'];
    $pot6 = $empresa['port_img6'];
    $pot7 = $empresa['port_img7'];
    $pot8 = $empresa['port_img8'];
    $pot9 = $empresa['port_img9'];
    $pot10 = $empresa['port_img10'];
    $pot11 = $empresa['port_img11'];
    $pot12 = $empresa['port_img12'];


    $endereco_completo = "{$logradouro}, {$numero}, {$bairro}, {$cidade}, {$uf}";
    $endereco_url = urlencode($endereco_completo);


} else {
    echo "Nenhum resultado encontrado para o ID da empresa.";
}

?>














<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title> Agenvi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/perfil-cliente.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/perfil-emp.css">
</head>
<style>

    .pai{
        height: auto;
        padding: 20px;
        margin-top: 20px;
        border: none;
    }
</style>
<body>
   
<header>
        <a href="../php/agendamento.php?id=<?php echo $codigo?>"><img class="seta" src="../img/seta-esquerda.png" alt=""><img class="logo" src="../img/logo-completona.png" alt=""></a> 
   


        <div class="bt2">
         <img src="../img/sair.png" alt="">
       </div>
    </header>

  

    <div class="pai">

<?php
            echo"<h5>Imagens principais</h5>";
            echo"<div class='bloco1'>";
            echo"<img class='caixa' src='arquivos/$ft1'>";
            echo"<img class='caixa' src='arquivos/$ft2'>";
            echo"<img class='caixa' src='arquivos/$ft3'>";
            echo"</div>";
            echo"<div class='bloco1'>";
            echo"<img class='caixa' src='arquivos/$ft4'>";
            echo"<img class='caixa' src='arquivos/$ft5'>";
            echo"<img class='caixa' src='arquivos/$ft6'>";
            echo"</div>";
          ?>
          <br>
          <br>
          <hr>
          <br>
          <br>
<?php
            echo"<h5>Imagens secundárias</h5>";
            echo"<div class='bloco1'>";
            echo"<img class='caixa' src='arquivos/$pot1'>";
            echo"<img class='caixa' src='arquivos/$pot2'>";
            echo"<img class='caixa' src='arquivos/$pot3'>";
            echo"</div>";
            echo"<div class='bloco1'>";
            echo"<img class='caixa' src='arquivos/$pot4'>";
            echo"<img class='caixa' src='arquivos/$pot5'>";
            echo"<img class='caixa' src='arquivos/$pot6'>";
            echo"</div>";
            echo"<div class='bloco1'>";
            echo"<img class='caixa' src='arquivos/$pot7'>";
            echo"<img class='caixa' src='arquivos/$pot8'>";
            echo"<img class='caixa' src='arquivos/$pot9'>";
            echo"</div>";
            echo"<div class='bloco1'>";
            echo"<img class='caixa' src='arquivos/$pot10'>";
            echo"<img class='caixa' src='arquivos/$pot11'>";
            echo"<img class='caixa' src='arquivos/$pot12'>";
            echo"</div>";
          ?>

   
   

      
    
            
</div>
     

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>

<?php
$stmt->close();
$mysqli->close();
?>

