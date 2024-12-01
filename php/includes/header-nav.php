<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


<header>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <nav class="navie">
    <div class="container">
      <a class="navbar-brand" href="../php/principal-cliente.php">
        <img src="../img/Logo-agenvi.png" alt="Bootstrap" width="40" height="40">
      </a>
    </div>
  </nav>
  <?php include('informacao_cliente.php')?>
    <div class="container-fluid">
  
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
  
         
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../php/salao_pesquisa.php">Salão de beleza</a>
          </li>
  
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../php/barbearia_pesquisa.php">Barbearia</a>
          </li>
  
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../php/clinica_pesquisa.php">Clínica</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../php/advocacia_pesquisa.php">Advocacia</a>
          </li>
  
         </ul>
  
        <form action="" method="get" class="pesquisarbar" role="search">
          <input class="form-control" name="buscar" type="text" placeholder="pesquisar empresas" aria-label="Search">
          <button class="bt1" type="submit"><i class="bi bi-search"></i></button>
        </form>

      
          <!-- Button trigger modal -->
          <button class="perfil" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"></button>





  
  
     
    </div>
  </div>
</div>
          
       
   
  </nav>
  

  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">

       <img class="perfiloffcanv" src="arquivos/<?php echo $img_perfil;?>" alt="">
     <div class="infonome">
       
       <p><strong><?php   echo" $nome_cliente";?></strong><br> Minha conta</p>
     
     </div>
     
    </div>
    
    <div class="offcanvas-body">

          <div>
          
          <a class="exit"  href="../php/perfil-cliente.php"><i class='bx bx-user' ></i> Meu Perfil</a>
        </div>

          <div>
      
            <a class="exit" href="../php/favoritos.php">   <i class='bx bx-heart'></i>Favoritos</a>
          </div>

          <div>
        
            <a class="exit" href="../php/historico-cliente.php"> <i class='bx bx-history'></i>Histórico</a>
          </div>
          
            <div>
              <a class="exit" href="../php/notificacao.php"><i class='bx bx-time'></i>Agendamentos</a>
            </div>

            <div>
          
            <a class="exit" href="logout.php"> <i class="bi bi-box-arrow-right"></i>Log-out</a>
          </div>

      </div>


     
    </div>
  </div>
</header>


<style>

.perfiloffcanv{
margin: 10px;
  width: 55px;
  border: 1px solid #fff;
  height: 55px;
  border-radius: 100%;
}


    :root{
    --c01:#fff;
    --c02:#E9E3FF; 
    --c03:#999999;
    --c04:#161616;
    --c05:#000;
    --c06:#B583F5;
    --c07:#7C5AFF;
    --c08:#6023B0;
    --c09:#471687;
}

.img{
 
  width:60px;
  
  border-radius:100%;
}
.perfil{
  text-align: center;
  margin:20px;
  border: 1px solid #606060;
  width:60px;

  height: 60px;
  background-size: cover;
  background-repeat: no-repeat;
 
  background-image: url('arquivos/<?php echo $img_perfil;?>');
  border-radius:100%;
  
}
 
.offcanvas-end{
  
  height: 330px;
  border-radius: 10px;
  width: 30%;

 
}

.offcanvas-body{
margin-left: 10px;
 width: 100%;
}

.exit{
  
   text-decoration: none;
   color: #161616;
   font-size: 17px;
}
.exit:hover{
  color: var(--c08);
 font-weight: 600;
}


.sair{
    width: 80px;
    text-align: center;
}
li{
    width: 150px;
    text-align: center;
}
.form-control{
    border: 1px solid var(--c08);
    width: 400px;
    height: 40px;
    border-radius: 5px 0px 0px 5px;
}
.bt1{
    width: 40px;
    border: none;
  background-color: var(--c08);
  color: var(--c01);
  border-radius: 0px 10px 10px 0px;
}


header{
  box-shadow: 2px 2px 7px var(--c03);
}
.bg-body-tertiary{
  height: 90px;

}
.nome{
  font-size: 20px;
}
.offcanvas-body>div{
  margin-top: -10;
  margin-bottom: 10px;
}
i{
  margin: 5px;
}

.navbar-brand{
  margin-left:25px;
  background-color: transparent !important;
}

.pesquisarbar{
  display: flex;
  align-items: center;
  margin: 0;
}
.bt1{
  margin: 0;
  height: 40px;
}


</style>
