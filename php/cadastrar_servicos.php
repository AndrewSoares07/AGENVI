<?php
include ("protect.php");

include("includes/logar-sistema.php");

include("includes/informaçoes_empresa.php");

?>
 <?php
    $nome = $_POST['nome_serv'] ?? null;
    $preco = $_POST['preco_serv'] ?? null;
    $desc = $_POST['descr_serv'] ?? null;
    $duracao = $_POST['duracao'] ?? null;
    if(empty($nome) || empty($preco) || empty($desc) || empty($duracao)){
         $mensagem_erro = "";
    }
  else{
    $sql = "INSERT INTO `servicos`(`nome_serv`, `preco_serv`, `descricao_serv`, `duracao_serv`) VALUES ('$nome', '$preco', '$desc', '$duracao')";
    $certo = mysqli_query($mysqli, $sql);
    
    $mensagem_erro = ''; // Inicialize a variável
     if($certo){

     $codigo = mysqli_insert_id($mysqli);

     $id_empresa = $_SESSION['idempresa'];


    $sql_lista = "INSERT INTO lista_servicos_empresa (idempresa, idservico) 
                      VALUES ('$id_empresa', '$codigo')";
                      
    $result_lista = mysqli_query($mysqli, $sql_lista);

    if ($result_lista) {
        $mensagem_erro = "<p class='foi'><i class='bx bx-error'></i>cadastrado com sucesso</p>";
        header('Location: lista_servicos.php');
    } else {
        $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Erro ao cadastrar o relacionamento: </p>" . mysqli_error($mysqli);
    }

     }
     else{
         $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>erro total</p>";
     }
  }
    
    ?>

    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cadastro_serv.css">
    <title>Agenvi</title>
</head>
<body>
<?php
include("includes/navbar.php");
?>

<section class="home-section">
    
    <div class="home-content">
          <i class='bx bx-menu' ></i>
          <span class="text">Serviços</span>
        </div>
    
    <div class="bodysec">
        <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
            <h3>Cadastro de serviço</h3>
            <hr>
            <p id="mostrar"><?php echo $mensagem_erro; ?></p>
                        <div class="ladin">
                            <div>
                                <p class="txs">Nome:</p>
                               <input type="text" name="nome_serv">
                            </div>
                            <div>
                                <p class="txs">Preço:</p>
                                <input type="number" step="0.01" name="preco_serv" id="preco_serv" value="0.00" />
                            </div>
                        </div>
        
                        <p class="txs">Duração:</p>
                       <input type="time" name="duracao" class="dur">
                        <br>
                        <p class="txs">Descrição:</p>
                        <input type="text" name="descr_serv" class="desc">
                        <br><br>
        
        
                        <input type="submit" value="enviar" class="salvarf">
                    </form>
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