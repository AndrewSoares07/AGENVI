<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link rel="stylesheet" href="../css/home.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <?php
     require_once 'includes/header.php';    
    ?>

    <main class="main1">

        <div class="frase1">
      
            <h1 class="titulo2">AGENVI, escolha o seu melhor horário <br>com a gente! </h1><br>
           
            <p class="negocios">Clínica Pediátrica    •    Escritório de Advocacia      •      Barbearia      •      Salão de Beleza</p><br>
            <a class="button" href="../php/conecte-se.php">Iniciar</a>
        </div>

        <div class="img-calendario" >
            <img class="calendario" src="../img/pedaco-body.png" alt="">
        </div>

    </main>

    
    <main class="main2">

            
                <div class="imgmain2">
                    <?php
                     require_once('includes/animahome.php');
                    ?>
                </div>
            
            <div class="frase2">
                <h1 class="titulo2">Gerencie seus agendamentos<br> em um só lugar!</h1><br>
                
                <p>Desenvolvemos estratégias para facilitar a rotina dessas instituições assim como a de seus clientes, substituindo o     agendamento manual por métodos mais eficientes.</p><br>

                <a class="button" href="../php/conecte-se.php">Iniciar</a>
            </div>

    </main>

    
    <main class="main3">
           <div class="planos">
         <h1 class="titulo">Confira nossos planos para grandes e <br> pequenas empresas:</h1>
       
            <div class="planin">
                <img class="img-planin" src="../img/basico.png" alt="">
                <p>•Até 40 clientes por dia</p>
                <p>•5 profissionais cadastrados  </p> <br>
                <h2>R$ 9,99</h2><br>
                <a class="buttonP" href="../php/planos.php">Ver Plano</a>
       
            </div>

            <div class="planin">
                <img class="img-planin2" src="../img/plus.png" alt="">
                <p>•Até 80 clientes por dia</p>
                <p>•10 profissionais cadastrados  </p><br>
                <h2>R$ 19,99</h2><br>
                <a class="buttonP" href="../php/planos.php">Ver Plano</a>
       
            </div>

            <div class="planin">
                <img class="img-planin3" src="../img/premium.png" alt="">
                <p>•Até 120 clientes por dia</p>
                <p>•20 profissionais cadastrados   </p><br>
                <h2>R$ 29,99</h2><br>
                <a class="buttonP" href="../php/planos.php">Ver Plano</a>
       
            </div>
           </div>
            
              
            <div class="imgplan">
                      
                <?php
                include('includes/animaplano.php');
                ?>
            
            </div>
    </main>


     <?php
     require_once 'includes/footer.php';    
    ?>


    
    
</body>
</html>