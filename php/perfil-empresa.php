
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
            <span class="text">Perfil</span>
          </div>
          <form action="editar_perfil.php" class="form-perf"><input class="button" type="submit" value="Editar seu perfil"></form>
        </div>
   <hr>
        <div class="pai">

           
              <div class="divlado">
                
                <div class="div1">
                    <?php
                      echo "<img class='imgper' src='arquivos/$img_perfil'><br> ";
                      echo "<h4>$nomef</h4>";
                      echo"<hr>";
                      echo "<div class='complement'> $comple</div>";
                
                      ?>
                </div>
                
                <div class="div2">
                <?php
                 echo"<div><h6>Telefone</h6><p class='infop'> $tel</p></div>";
                 echo "<div><h6>Email</h6> <p class='infop'>$email</p></div>";
                 echo "<div><h6>Endereço</h6> <p class='infop'>$logradouro, $num - $bairro - $uf</p></div>";
                      ?>
                
                </div>
              </div>



         
                <button  id="but_img"><a href="visualizar_imagens.php" style="text-decoration: none; color:white;">Ver suas imagens</a></button>
          
           

              
              </div><br><br>
                    
        </div><br><br>
             
            
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































