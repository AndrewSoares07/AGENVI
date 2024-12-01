<?php
include("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");

$idempresa = $_SESSION['idempresa'];

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
e.complemento 
FROM empresa e
inner join localidade l on l.cep = e.cep
WHERE idempresa = $idempresa";


$rs = mysqli_query($mysqli, $sql);
$info = mysqli_fetch_array($rs);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/edit-perfil-emp.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <script src="../js/api.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
</head>
<body>
    <?php
    include("includes/navbar.php")
    ?>

    <section class="home-section">

    <div class="home-content">
      <div>
          <i class='bx bx-menu' ></i>
          <span class="text">Perfil</span>
      </div>
     
    </div>
    <hr>


        <form action="atualizar_perfil.php" method="post" enctype="multipart/form-data">
        
            <div class="pai">
                <div class="filho">
                    
                    <img id="preview_foto_empresa"  class='batata2' src="arquivos/<?php echo $info['foto_empresa']; ?>" alt="Imagem da Empresa" ><br>
                    <label for="foto_empresa" class="custom-file-upload">
                        Selecionar Imagem
                        <input type="file" id="foto_empresa" name="foto_empresa" data-preview="preview_foto_empresa" onchange="previewImage(event)" >
                    </label>
        
                
        <div class="lado">
            
            
                        <div>
                            <label for="nome">Nome:</label><br>
                            <input class="input1" type="text" id="nome" name="nome" value="<?php echo $info['nome']; ?>">
                        </div>
            
            
                        <div>
                            <label for="nome_fantasia">Nome Fantasia:</label><br>
                            <input class="input1" type="text" id="nome_fantasia" name="nome_fantasia" value="<?php echo $info['nome_fantasia']; ?>">
                        </div>
            
        </div>
        
        <div class="lado">
            
                        <div>
                            <label for="telefone">Telefone:</label><br>
                            <input class="input1" type="text" id="telefone" name="telefone" maxlength="15" value="<?php echo $info['telefone']; ?>">
                        </div>
            
            
            
                        <div>
                            <label for="cnpj_cpf">CNPJ/CPF:</label><br>
                            <input class="input1" type="text" id="cnpj_cpf" name="cnpj_cpf" value="<?php echo $info['CNPJ_CPF']; ?>">
                        </div>
            
        </div>
        
        
                    <div>
                        <label for="email">Email:</label><br>
                        <input class="input2" type="text" id="email" name="email" value="<?php echo $info['email']; ?>">
                    </div>
                    <div>
                        <label for="email">CEP:</label><br>
                        <input class="input2" type="text" id="cep" name="cep" onblur="pesquisacep(this.value);" value="<?php echo $info['cep']; ?>">
                    </div>
                    <div>
                        <label for="email">rua:</label><br>
                        <input class="input2" type="text" id="rua" name="rua" value="<?php echo $info['logradouro']; ?>">
                    </div>
                    <div>
                        <label for="email">bairro:</label><br>
                        <input class="input2" type="text" id="bairro" name="bairro" value="<?php echo $info['bairro']; ?>">
                    </div>
                    <div>
                        <label for="email">cidade:</label><br>
                        <input class="input2" type="text" id="cidade" name="cidade" value="<?php echo $info['cidade']; ?>">
                    </div>
                    <div>
                        <label for="email">UF:</label><br>
                        <input class="input2" type="text" id="uf" name="uf" value="<?php echo $info['UF']; ?>">
                    </div>
                    <div>
                        <label for="email">numero:</label><br>
                        <input class="input2" type="text" id="numero" name="numero" value="<?php echo $info['numero']; ?>">
                    </div>
                    <div>
                        <label for="email">complemento</label><br>
                        <input class="input2" type="text" id="complemento" name="complemento" value="<?php echo $info['complemento']; ?>">
                    </div>

            </div>
        
            <div class="filho2">
                <div class="lad1">
                    <div>
                        
                        <img id="preview_foto_amb1" src="arquivos/<?php echo $info['foto_amb1']; ?>" alt="Imagem Ambiente 1"class="unf"><br>
                        <label for="foto_amb1" class="custom-file-upload">
                            Selecionar Imagem
                            <input type="file" id="foto_amb1" name="foto_amb1" data-preview="preview_foto_amb1" onchange="previewImage(event)">
                        </label>
                    </div>
                    
                    <div>
                  
                        <img id="preview_foto_amb2" src="arquivos/<?php echo $info['foto_amb2']; ?>" alt="Imagem Ambiente 2" class="unf"><br>
                        <label for="foto_amb2" class="custom-file-upload">
                            Selecionar Imagem
                            <input type="file" id="foto_amb2" name="foto_amb2" data-preview="preview_foto_amb2" onchange="previewImage(event)">
                        </label>
                    </div>
                  
               </div>
                <div  class="lad1">
                <div>
                        <img id="preview_foto_amb3" src="arquivos/<?php echo $info['foto_amb3']; ?>" alt="Imagem Ambiente 3"class="unf" ><br>
                        <label for="foto_amb3" class="custom-file-upload">
                            Selecionar Imagem
                            <input type="file" id="foto_amb3" name="foto_amb3" data-preview="preview_foto_amb3" onchange="previewImage(event)">
                        </label>
                    </div>
                    <div>
                        <img id="preview_foto_amb4" src="arquivos/<?php echo $info['foto_amb4']; ?>" alt="Imagem Ambiente 4" class="unf"><br>
                        <label for="foto_amb4" class="custom-file-upload">
                            Selecionar Imagem
                            <input type="file" id="foto_amb4" name="foto_amb4" data-preview="preview_foto_amb4" onchange="previewImage(event)">
                        </label>
                    </div>
                </div>

                   <div class="lad1">
                   <div>
                        <img id="preview_foto_amb5" src="arquivos/<?php echo $info['foto_amb5']; ?>" alt="Imagem Ambiente 5"class="unf" ><br>
                        <label for="foto_amb5" class="custom-file-upload">
                            Selecionar Imagem
                            <input type="file" id="foto_amb5" name="foto_amb5" data-preview="preview_foto_amb5" onchange="previewImage(event)">
                        </label>
                    </div>
                    <div>
                        <img id="preview_foto_amb6" src="arquivos/<?php echo $info['foto_amb6']; ?>" alt="Imagem Ambiente 6" class="unf"><br>
                        <label for="foto_amb6" class="custom-file-upload">
                            Selecionar Imagem
                            <input type="file" id="foto_amb6" name="foto_amb6" data-preview="preview_foto_amb6" onchange="previewImage(event)">
                        </label>
                    </div>
                   
                   </div>
                </div>
            </div>
        </div>
        <br>
        <input type="submit" value="Salvar alterações" class="button">
         <br>
        </form>
       
    </section>

    <script>
        // Função para pré-visualizar a imagem selecionada
        function previewImage(event) {
            const input = event.target;
            const previewId = input.getAttribute('data-preview');
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById(previewId);
                output.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>

    

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
