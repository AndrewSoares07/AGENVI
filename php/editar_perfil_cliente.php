<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');
include("includes/informacao_cliente.php");

$idcliente = $_SESSION['idcliente'];
$sql = "SELECT * FROM cliente WHERE idcliente = $idcliente";
$rs = mysqli_query($mysqli, $sql);
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
    <link rel="shortcut icon" type="image/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link rel="stylesheet" href="../css/perfil-cliente.css">
</head>

<body>
<header>
<a href="../php/perfil-cliente.php"><img class="seta" src="../img/seta-esquerda.png" alt=""><img class="logo" src="../img/logo-completona.png" alt=""></a> 
<a class="sair" href="logout.php"><i class='bx bx-exit' style='color:#ffffff'  ></i> Sair</a>

</header>


    


    <form action="atualizar_perfil_cliente.php" method="POST" enctype="multipart/form-data" >
        
            <div class="div1">
                
              
            <h3>Atualizar meus dados</h3>
            <hr><br>
                
              <div class="ladin">
    
                    <div>
                        <p class="infos">Clique na foto para altera-la:</p>
                        <label for="foto_perfil" class="custom-file-upload">
                        <img id="preview_foto_empresa_img" class='batata' src="arquivos/<?php echo $info['foto_perfil']; ?>" alt="">

                            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" onchange="previewImage(event, 'preview_foto_empresa_img')">
                        </label>
                    </div>

                    <div>
                      
                        <p class="infos">Nome</p>
                        <input type="text" class="infos2" id="nome" name="nome" value="<?php echo $info['nome_cliente']; ?>">
                      
                      
                        <p class="infos">Telefone</p>
                        <input type="text" class="infos2" id="telefone" name="telefone" maxlength="15" value="<?php echo $info['telefone']; ?>" oninput="aplicarMascaraTelefone(this)">

                    
                        <p class="infos">Email</p>
                        <input type="text" class="infos2" id="email" name="email" value="<?php echo $info['email']; ?>">
                      
                        <p class="infos">Data de nascimento</p>
                        <input type="text" class="infos2" id="data_nasc" name="data_nasc" value="<?php echo $info['data_nasc']; ?>">
                    </div>
             </div>

               
            </div>
    <br>
        <input type="submit" value="Salvar" class="bt1">
    </form>
    <style>
        .div1{
            width: 100%;
        }
       
    </style>


<script>
    // Função para aplicar máscara ao telefone
    function aplicarMascaraTelefone(input) {
        // Remove qualquer caractere que não seja número
        let telefone = input.value.replace(/\D/g, "");

        // Aplica a máscara (xx) xxxxx-xxxx
        telefone = telefone.replace(/^(\d{2})(\d)/g, "($1) $2");
        telefone = telefone.replace(/(\d)(\d{4})$/, "$1-$2");

        input.value = telefone;
    }

    // Função para pré-visualizar a imagem selecionada
    function previewImage(event, previewId) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById(previewId);
            output.src = reader.result;
        };
        reader.readAsDataURL(input.files[0]);
    }





    // Função para pré-visualizar a imagem selecionada
    function previewImage(event, previewId) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById(previewId);
            if (previewId.includes('_img')) {
                output.src = reader.result;
            } else {
                output.style.backgroundImage = 'url(' + reader.result + ')';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
