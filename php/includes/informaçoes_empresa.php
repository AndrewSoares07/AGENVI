
<?php
$idempresa = $_SESSION['idempresa'];

$sql_code = "SELECT 
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
WHERE e.idempresa = $idempresa";

$sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

if ($sql_query->num_rows == 1) {
   
    $empresa = $sql_query->fetch_assoc();
    $nome = $empresa['nome'];
    $email = $empresa['email'];
    $nomef = $empresa['nome_fantasia'];
    $tipo = $empresa['tipo'];
    $cnpf = $empresa['CNPJ_CPF'];
    $tel = $empresa['telefone'];
    $img_perfil = $empresa['foto_empresa'];
    $ft1 = $empresa['foto_amb1'];
    $ft2 = $empresa['foto_amb2'];
    $ft3 = $empresa['foto_amb3'];
    $ft4 = $empresa['foto_amb4'];
    $ft5 = $empresa['foto_amb5'];
    $ft6 = $empresa['foto_amb6'];
    $comple = $empresa['complemento'];
    $bairro = $empresa['bairro'];
    $num = $empresa['numero'];
    $cidade = $empresa['cidade'];
    $logradouro = $empresa['logradouro'];
    $uf = $empresa['UF'];
    $cep = $empresa['cep'];
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
    

} else {
    echo "Erro ao recuperar informações da empresa.";
}

?>