<?php
include ("protect.php");

include("includes/logar-sistema.php");

include("includes/informaçoes_empresa.php");






?>
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/perfil-emp.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    </head>
<body>
    
<?php
 include ("includes/navbar.php")
?>



  <section class="home-section">

        <div class="home-content">
          <div>
            <i class='bx bx-menu' ></i>
            <span class="text">Fotos da empresa</span>
          </div>
          <form action="editar_portifolio.php" class="form-perf"><input class="button" type="submit" value="Editar minhas fotos"></form>
        </div>
   <hr>
        <div class="pai">

        <?php
                    echo"<h5>Imagens pricipais</h5>";
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
                    echo"<h5>imagens secundarias</h5>";
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
             
            
  </section>
 
  

  <script>
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



























