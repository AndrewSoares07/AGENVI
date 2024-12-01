<?php
include("includes/logar-sistema.php");

$mensagem_erro = ''; // Inicialize a variável

if (isset($_POST['email']) && isset($_POST['senha'])) {

    if (empty($_POST['email'])) {
        $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Preencha seu e-mail</p>";
    } else if (empty($_POST['senha'])) {
        $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Preencha sua senha</p>";
    } else {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $_POST['senha'];

        $sql_code = "SELECT * FROM cliente WHERE email = '$email'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        if ($sql_query->num_rows == 1) {
            $empresa = $sql_query->fetch_assoc();

            if (password_verify($senha, $empresa['senha'])) {
                session_start();
                $_SESSION['idcliente'] = $empresa['idcliente'];
                $_SESSION['nome'] = $empresa['nome'];
                header("Location:principal-cliente.php");
                exit;
            } else {
                $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Senha incorreta.</p>";
            }
        } else {
            $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>E-mail não encontrado.</p>";
        }

     
        $sql_admin = "SELECT * FROM admin_agenvi WHERE email = '$email'";
        $sql_query_admin = $mysqli->query($sql_admin) or die("Falha na execução do código SQL: " . $mysqli->error);
        
        if ($sql_query_admin->num_rows == 1) {
            $empresas = $sql_query_admin->fetch_assoc();

            if ($senha == $empresas['senha']) {
                session_start();
                $_SESSION['id_adm'] = $empresas['idadmin'];
                header("Location:adm_principal.php");
                exit;
            } else {
                $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Senha incorreta.</p>";
            }
        } else {
            $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>E-mail não encontrado.</p>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/login-emp.css">
</head>
<body>
   
<div class="tudo">
    
    <div class="pai">
    
    <div class="anima">
      <div class="main1">
                <a href="../php/conecte-se.php">
                    <div class="div1"><img class="img1" src="../img/seta-esquerda.png" alt=""></div>
                </a>
                <div class="div2"><img class="img2" src="../img/logo-completona2.png" alt=""></div>
             </div>
    
          <?php
           include("includes/animacaocad.php")
          ?>
    
      </div>
    
        <form action="" method="POST">
                 <h2>Login Cliente</h2>
            <div>
                <label for="nome">Usuário</label>
                 <div class="caixa">
                            <i class='bx bxs-user' style='color:#999999'  ></i>
                            <input type="text" name="email" id="email" placeholder="E-mail">
                        </div>
            </div><br>
    
    
                  <div>
                      <label for="senha">Senha</label>
                        <div class="caixa">
                            <i class='bx bxs-lock alt' style='color:#999999'  ></i>
                            <input type="password" name="senha" id="senha" placeholder="Senha" >
                            <i class="bi bi-eye-fill" id="btn-senha" onclick="mostrarsenha()"></i><br><br>
                        </div>
                        <p id="mostrar"><?php echo $mensagem_erro; ?></p>
                        <p>Não possui login? <a href="cadastro-cliente.php">Cadastre-se</a></p>
                  </div>
    
                      <br>
                    <input class="button" type="submit" value="Entrar">
    
    
        </form>
    
    
    
    </div>
</div>

<script>
    function mostrarsenha(){

        var inputPass = document.getElementById('senha')
        var btnShowPass = document.getElementById('btn-senha')

        if(inputPass.type === 'password'){
            inputPass.setAttribute('type','text')
            btnShowPass.classList.replace('bi-eye-fill','bi-eye-slash-fill')
        }else{
            inputPass.setAttribute('type','password')
            btnShowPass.classList.replace('bi-eye-slash-fill','bi-eye-fill')
        }
    }
</script>
    
</body>
</html>