<?php

include('includes/logar-sistema.php');
include('protect_cliente.php');
include("includes/informacao_cliente.php");

$idcliente = $_SESSION['idcliente'];

?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    
    
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link rel="stylesheet" href="../css/perfil-cliente.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    
   
    </head>

    <body>
   


   
    <header>
<a href="../php/principal-cliente.php"><img class="seta" src="../img/seta-esquerda.png" alt=""><img class="logo" src="../img/logo-completona.png" alt=""></a> 
<a class="sair" href="logout.php"><i class='bx bx-exit' style='color:#ffffff'  ></i> Sair</a>

</header>

  <div class="pai">
     
      <?php
      include("includes/infos-perfil-cli.php")
      ?>


      
  <div class="div1">
          <h3>Meus dados</h3>
        <br><br>
        <div class="lado2">
          
                        <div class="ft1">
                          <?php
                              echo "<img class='batata' src='arquivos/$img_perfil'> ";
          
          
                              ?>
                        </div>
          
                    <div>
                      <?php
                          echo"<div class='lado'><div class='nome'><p><strong>Nome</strong><br> $nome_cliente</p></div>";
                          echo "<div><p><strong>Data de nascimento</strong><br>$dataInvertida</p> </div></div>";
                         
                          echo "<div><p><strong>Telefone</strong><br> <span class='telefone-cliente'>$telefone</span></p></div>";
          
                          echo "<div><p><strong>E-mail</strong><br> $email</p></div>";
                        ?>
                    </div>
          
        </div>
    
      <form action="editar_perfil_cliente.php" class="form-perf"><input class="bt1" type="submit" value="Editar perfil"></form>
  </div>

    

   
     
     </div>
    </body>



    <script>
function formatarTelefone(telefone) {
    // Remove qualquer caractere não numérico
    telefone = telefone.replace(/\D/g, "");

    // Aplica a máscara (xx) xxxxx-xxxx
    telefone = telefone.replace(/^(\d{2})(\d)/g, "($1) $2");
    telefone = telefone.replace(/(\d)(\d{4})$/, "$1-$2");

    return telefone;
}

// Formata o telefone ao carregar a página
document.addEventListener("DOMContentLoaded", () => {
    const telefoneElement = document.querySelector(".telefone-cliente");
    if (telefoneElement) {
        telefoneElement.innerText = formatarTelefone(telefoneElement.innerText);
    }
});

  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
  console.log(sidebarBtn);
  sidebarBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("close");
  });
  </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>



























