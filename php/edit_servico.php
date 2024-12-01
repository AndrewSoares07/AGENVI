<?php
include ("protect.php");

include("includes/logar-sistema.php");

$servico = $_GET['id'];
$sql = "SELECT * from servicos where idservico = $servico;";
$rs = mysqli_query($mysqli,$sql);
$info = mysqli_fetch_array($rs);

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
    <link rel="stylesheet" href="../css/edit-func.css">
    <title>Agenvi</title>
</head>
<body>



<?php include("includes/navbar.php"); ?>

<section class="home-section">

    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text">Editar Serviços</span>
    </div><br>

    <div class="editfunc">
        <form action="atualiza_serv.php" method="post" class="formser">
        <h4>Informações</h4>
        <hr>

            <div class="lado">
                <div class="divser">
                    <input type="hidden" name="idservico" value="<?php echo $info['idservico'] ?>">
                     <label for="">Nome: </label><br>
                    <input type="text" name="nome_serv" class="inp2" value="<?php echo $info['nome_serv'] ?>">
                </div>
                
        <div class="divser">
                <label for="">  Preço: </label><br>
                <input type="number" step="0.01" class="inp2" name="preco_serv" value="<?php echo $info['preco_serv'] ?>" />
             </div>
            </div>
<div class="divser">
    
               <label for=""> Descrição: </label><br>
               <input type="text" class="desc1" name="descricao_serv" value="<?php echo $info['descricao_serv'] ?>">
</div>

           <div class="divser">
               <label for=""> Duração:</label><br>
                <input type="time" class="inp2" name="duracao_serv" value="<?php echo $info['duracao_serv'] ?>">
           </div>
            <input type="submit" value="enviar" class="button">
        
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