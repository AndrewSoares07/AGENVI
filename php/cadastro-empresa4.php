<?php
include('includes/conecta.php');
$sql = "SELECT * FROM planos";
$planos = mysqli_query($con, $sql);


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/cadastro-emp.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Agenvi</title>
</head>

<body>
    <style>
        .linha {
            width: 100%;
            height: 4px;
            margin: 30px 0px 30px 0px;
            background-color: var(--c02);
            border-radius: 10px;
        }

        .linha2 {
            width: 85%;
            height: 4px;
            margin: 30px 0px 30px 0px;
            background-color: var(--c08);
            border-radius: 10px;

        }
        h2{
            margin-top: 6px;
            font-size: 24px;
            font-weight: bolder;
            color: var(--c08);
        }
        .logo{
            margin-top: 6px;
        }
       
       

      

      
    </style>


<div class="bigbig">
    
    
        <div class="maior">
    
            <div class="parte-img">
                 <p class="logona"><img class="logocad" src="../img/logo-agenvi-branca.png" alt=""> AGENVI <br></p><br><br>
                 <p class="bemv">Bem vindo!</p><br><br>
                 <p class="txtF">Escolha um plano que mais combine com o seu espaço de trbalho</p>
            </div>
    
            <div class="parte-cadastro">
    
    
                <div class="header-cad">
                    <h2>Escolha seu plano</h2>
                    <div class="linha"><div class="linha2"></div></div>
                </div>
    
    
    
                <div class="body-cad">
    
    
        <div class="d-flex justify-content-around">
    
    
    <?php while ($info = mysqli_fetch_array($planos)) { ?>
    
    
    
        <div class="card-plano">
    
            <div class="cad1">
    
                    <div class="img-logo">
                        <div class="figure">
                        </div>
                    </div>
                    <div class="texto-plan">
                        <p class="ppla">Plano</p>
                        <div class="tipe">
                            <?php echo $info['Nome']; ?>
                        </div>
    
                </div>
    
            </div>
    
            <p class="mini-text"><?php echo $info['desc']; ?></p>
            <div class="preco-pan">
                <h3>R$ <?php echo $info['preco']; ?></h3><p>/mês</p>
            </div>
    
    
            <ul>
                <li>Até
                    <?php echo $info['clientes-dia']; ?> clientes por dia
                </li>
    
                <li>Até
                    <?php echo $info['profissionais']; ?> profissionais
                </li>
            </ul>
    
            <div class="footercard"><a class="but-plan" href="pagamento_plano.php?plan=<?php echo urlencode($info['nivel']); ?>">Assine já</a></div>
    
    
    
    
    
    
        </div>
    <?php } ?>
    
    </div><br><br>
    
    
    
    
    
    
        </div>
    
    
    
         </div>
    
        </div>
    
    
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>