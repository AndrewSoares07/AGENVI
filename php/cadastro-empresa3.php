<?php
session_start();  // Inicia a sessão para armazenar mensagens e dados

// Diretório onde as imagens serão salvas
$uploadDir = "../img/arquivo/";  
$imagePaths = []; // Array para armazenar os caminhos das imagens enviadas

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  // Verifique se o formulário foi enviado
    for ($i = 1; $i <= 6; $i++) {  // Processar até 6 arquivos (foto_amb1 a foto_amb6)
        $fileInputName = 'fileInput' . $i;  // Nome do input no formulário

        // Verificar se o arquivo foi enviado e está sem erros
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == UPLOAD_ERR_OK) {
            $tmpName = $_FILES[$fileInputName]['tmp_name'];
            $fileExtension = strtolower(pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION));

            // Gera um nome único para evitar conflitos
            $newFileName = "foto_amb{$i}_" . uniqid() . '.' . $fileExtension;
            $filePath = $uploadDir . $newFileName;

            // Verificar se o diretório existe; se não, criar
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Mover o arquivo para o diretório de upload
            if (move_uploaded_file($tmpName, to: $filePath)) {
                $imagePaths["foto_amb{$i}"] = $newFileName;  // Salvar o nome do arquivo no array
            } else {
                // Adicionar mensagem de erro na sessão
                $_SESSION['mensagem_erro'][] = "Falha ao mover o arquivo: " . $_FILES[$fileInputName]['name'];
            }
        } else {
            // Caso o arquivo não tenha sido enviado ou tenha erro, tratar como vazio
            $imagePaths["foto_amb{$i}"] = '';  // Define como vazio para imagens ausentes
        }
    }

    // Armazenar os caminhos das imagens na sessão
    $_SESSION['imagePaths'] = $imagePaths;

    // Mensagens de feedback
    if (!empty(array_filter($imagePaths))) {
        $_SESSION['mensagem_sucesso'] = "<p class='foi'><i class='bx bx-check'></i>Imagens processadas com sucesso!</p>";
    } else {
        $_SESSION['mensagem_erro'][] = "<p class='erro'><i class='bx bx-error'></i>Nenhuma imagem foi enviada ou processada com sucesso.</p>";
    }

    // Redirecionar para a próxima página
    header('Location: cadastro-empresa4.php');
    exit();
}
?>





<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/cadastro-emp.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Agenvi</title>
</head>
<body>
<style>
        .linha{
    width: 100%;
    height: 4px;
    margin: 30px 0px 30px 0px;
   background-color: var(--c02);
    border-radius: 10px;
}
.linha2{
    width: 40%;
    height: 4px;
    margin: 30px 0px 30px 0px;
   background-color: var(--c08);
    border-radius: 10px;
   
}

input[type="file"] {
    display: none;
}

.container{
    width: 300px;
    border: 1px solid var(--c08);
    height: 162px;
    border-radius: 5px;
    margin: 15px;
    
}
.fotoamb{
    margin-top:  -60px; 
    margin-left: -2px;
    width: 305px;
    height: 175px;
    border-radius: 5px;
}
p{
    text-align: center;
}
    </style>
    

    
<div class="bigbig">
    
     <div class="maior">
         <div class="parte-img">
              <p class="logona"><img class="logocad" src="../img/logo-agenvi-branca.png" alt=""> AGENVI <br></p><br><br>
              <p class="bemv">Bem vindo!</p><br><br>
              <p class="txtF">Inclua fotos do seu espaço para gerar interesse nos clientes</p>
         </div>
         <div class="parte-cadastro">
             <div class="header-cad">
                 <h2>Fotos do seu espaço</h2>
                 <div class="linha"><div class="linha2"></div></div>
             </div>
    
          <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
             <div class="body-cad">
                         <div class="ladoF">
                             <div id="sectionfotos"><br>
                                 <div class="container">
                                     <label for="fileInput1"><svg xmlns="http://www.w3.org/2000/svg"  width="50" height="50" viewBox="0 0 24 24" style="fill: rgb(96, 35, 176);"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm1 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
                                     <input type="file" id="fileInput1" accept="image/*" name="fileInput1" class="Fotos">
                                     <div id="imageContainer1"><img class="fotoamb" id="imagePreview1" src="#" alt="Sua imagem aparecerá aqui" style="display: none;"></div>
                                     </label>
                                 </div>
                             </div>
                             <div id="sectionfotos">
                                 <br>
                                 <div class="container">
                                     <label for="fileInput2"><svg xmlns="http://www.w3.org/2000/svg"  width="50" height="50" viewBox="0 0 24 24" style="fill: rgb(96, 35, 176);"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm1 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
                                     <input type="file" id="fileInput2" accept="image/*" name="fileInput2" class="Fotos">
                                     <div id="imageContainer2"><img class="fotoamb" id="imagePreview2" src="#" alt="Sua imagem aparecerá aqui" style="display: none;"></div>
                                     </label>
                                 </div>
                             </div>
                             <div id="sectionfotos">
                                 <br>
                                 <div class="container">
                                     <label for="fileInput3"><svg xmlns="http://www.w3.org/2000/svg"  width="50" height="50" viewBox="0 0 24 24" style="fill: rgb(96, 35, 176);"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm1 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
                                     <input type="file" id="fileInput3" accept="image/*" name="fileInput3" class="Fotos">
                                     <div id="imageContainer3"><img class="fotoamb" id="imagePreview3" src="#" alt="Sua imagem aparecerá aqui" style="display: none;"></div>
                                     </label>
                                 </div>
                             </div>
                     </div>
    
                     <div class="ladoF">
    
    
                                 <div id="sectionfotos">
                                     <div class="container">
                                         <label for="fileInput4"><svg xmlns="http://www.w3.org/2000/svg"  width="50" height="50" viewBox="0 0 24 24" style="fill: rgb(96, 35, 176);"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm1 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
                                         <input type="file" id="fileInput4" accept="image/*" name="fileInput4" class="Fotos">
                                         <div id="imageContainer4"> <img class="fotoamb" id="imagePreview4" src="#" alt="Sua imagem aparecerá aqui" style="display: none;"></div>
                                         </label>
                                     </div>
                                 </div>
    
                                 <div id="sectionfotos">
                                     <div class="container">
                                         <label for="fileInput5"><svg xmlns="http://www.w3.org/2000/svg"  width="50" height="50" viewBox="0 0 24 24" style="fill: rgb(96, 35, 176);"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm1 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
                                         <input type="file" id="fileInput5" accept="image/*" name="fileInput5" class="Fotos">
                                         <div id="imageContainer5"><img class="fotoamb" id="imagePreview5" src="#" alt="Sua imagem aparecerá aqui" style="display: none;"> </div>
                                         </label>
                                     </div>
                                 </div>
    
                                 <div id="sectionfotos">
                                     <div class="container">
                                         <label for="fileInput6"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" style="fill: rgb(96, 35, 176);"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm1 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"></path></svg>
                                         <input type="file" id="fileInput6" accept="image/*" name="fileInput6" class="Fotos">
                                         <div id="imageContainer6"><img class="fotoamb" id="imagePreview6" src="#" alt="Sua imagem aparecerá aqui" style="display: none;"></div>
                                         </label>
                                     </div>
                                 </div>
                         </div>
                  </div><br>
                  <div class="footer-form">
                     <input type="submit" class="button">
    
                   </div><br>
             <br>
         </form>
     </div>
    
    
</div>
           
</div>
<script>
    document.querySelectorAll('input[type="file"]').forEach((input, index) => {
    input.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const imagePreview = document.getElementById('imagePreview' + (index + 1))
        const reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    });
});
</script>

                    

              
    

</body>
</html>
