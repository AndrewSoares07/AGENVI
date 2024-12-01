<?php
session_start();
include_once('includes/conecta.php');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? "";
    $nomeE = $_POST['nomeE'] ?? "";
    $tipo = $_POST['tipo'] ?? "";
    $email = $_POST['email'] ?? "";
    $tel = $_POST['tel'] ?? "";
    $cnpjj = $_POST['cnpj/cpf'] ?? "";
    $senha = $_POST['senha'] ?? "";
    $Vsenha = $_POST['Vsenha'] ?? "";
    $comple = $_POST['comple'] ?? "";

    // Check if email already exists
    $select_email = "SELECT email FROM empresa WHERE email = ?";
    $stmt = mysqli_prepare($con, $select_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $mensagem_erro = 'O email já existe.';
    } elseif ($senha !== $Vsenha) {
        $mensagem_erro = 'As senhas não coincidem. Por favor, verifique.';
    } elseif (empty($nome) || empty($nomeE) || empty($tipo) || empty($email) || empty($tel) || empty($cnpjj) || empty($senha) || empty($Vsenha) || empty($comple)) {
        $mensagem_erro = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        // Handle file upload
        if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] == UPLOAD_ERR_OK) {
            $nome_arquivo_especifico = 'nome_teste';
            $extensao = strtolower(pathinfo($_FILES['fileInput']['name'], PATHINFO_EXTENSION));
            $novo_nome = $nome_arquivo_especifico . '.' . $extensao;
            $diretorio = "../img/arquivo/";

            // Create directory if not exists
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            if (move_uploaded_file($_FILES['fileInput']['tmp_name'], $diretorio . $novo_nome)) {
                $_SESSION['imagem_empresa'] = $novo_nome;
            } else {
                $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>Erro ao fazer upload da imagem.</p>";
            }
        } else {
            $_SESSION['imagem_empresa'] = '';
        }

        if (empty($mensagem_erro)) {
            // Additional actions, e.g., insert data into the database
            header('location:cadastro-empresa2.php');
            exit();
        }
    }
    
    // Close connection
    mysqli_close($con);
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/cadastro-emp.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Agenvi</title>

</head>

<body>
    <style>
        .linha {
            width: 100%;
            height: 4px;
            margin: 30px 0px 30px 0px;
            background-color: var(--c02);
            border-radius: 10px;
        }

        .linha2 {
            width: 1%;
            height: 4px;
            margin: 30px 0px 30px 0px;
            background-color: var(--c08);
            border-radius: 10px;

        }

        .img {
            background-image: url(../img/semimagem.png);
            background-size: cover;

            margin: auto;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            border: 1px solid var(--c08);
        }

        #fileInput {
            position: relative;
            opacity: 0;
            width: 200px;
            height: 200px;
            border-radius: 100px;

        }

        .erro {
            text-align: center;
        }
    </style>


    

    <div class="bigbig">
        <div class="maior">
            <div class="parte-img">
                <!-- <div class="voltar">
                    <a href="../php/login-empresa.php"><img class="seta" src="../img/seta-esquerda.png" alt=""></a>
                 </div> -->
                 <p class="logona"><img class="logocad" src="../img/logo-agenvi-branca.png" alt=""> AGENVI <br></p><br><br>
                 <p class="bemv">Bem vindo!</p><br><br>
                 <p class="txt">Para iniciar seu cadastro,<br> precisamos das seguintes informações.</p>
        
            </div>
            <div class="parte-cadastro">
                <div class="header-cad">
                   
                      
                        <h2>Informações da empresa</h2>
                  
                    <div class="linha"><div class="linha2"></div></div>
                </div>
        
                <div class="body-cad">
                    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <p class="erro" id="mostrar"><?php echo $mensagem_erro; ?></p>
                        <div class="lado2">
                            <div>
                                <label for="nome">Seu nome</label>
                                <div class="caixa">
                                    <i class='bx bxs-user' style='color:#999999'></i>
                                    <input type="text" name="nome" id="nome" required placeholder="Nome">
                                </div>
                                <div class="lado">
                                    <div>
                                        <label for="nomeE">Nome da Empresa</label>
                                        <div class="caixa2">
                                            <i class='bx bxs-user' style='color:#999999'></i>
                                            <input class="menor" type="text" name="nomeE" id="nomeE" required
                                                placeholder="Nome do seu negócio">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="tel">Telefone</label>
                                        <div class="caixa2">
                                            <i class='bx bxs-phone' style='color:#999999'></i>
                                            <input class="menor" type="text" name="tel" id="tel" maxlength="15" required
                                                minlength="11" onkeyup="mascaraTelefone(this)" placeholder="(21)">
                                        </div>
                                    </div>
                                </div>
                                <div class="lado">
                                    <div class="tipoemp">
                                        <label for="tipo">Tipo empresa</label><br>
                                        <select name="tipo" id="tipo" required>
                                            <option value="">Tipo de empresa</option>
                                            <option value="advocacia">Advocacia</option>
                                            <option value="clinica">Clínica</option>
                                            <option value="barbearia">Barbearia</option>
                                            <option value="salaobeleza">Salão de Beleza</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="cnpj_cpf">CNPJ/CPF</label>
                                        <div class="caixa2">
                                            <i class='bx bx-notepad' style='color:#999999'></i>
                                            <input class="menor" type="text" name="cnpj/cpf" id="cnpj/cpf" maxlength="18"
                                                onkeyup="mascaraCnpjCpf(this)" required>
                                        </div>
                                    </div>
                                </div>
        
                                <label for="email">E-mail</label>
                                <div class="caixa">
                                    <i class='bx bxs-envelope' style='color:#999999'></i>
                                    <input type="email" name="email" id="email" required
                                        placeholder="Digite o email da empresa">
                                </div>
        
                                <div class="lado">
                                        <div>
                                            <label for="senha">Senha:</label>
                                            <div class="caixa2">
                                                <i class='bx bxs-lock' style='color:#999999'></i>
                                                <input class="menor" type="password" name="senha" id="senha" required placeholder="Senha" minlength="6" maxlength="18">
                                                <i class='bx bxs-show' id="togglesenha" style='cursor:pointer; color:#999999;'
                                                    onclick="togglePassword('senha', 'togglesenha')"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="Vsenha">Confirme a Senha:</label>
                                            <div class="caixa2">
                                                <i class='bx bxs-lock' style='color:#999999'></i>
                                                <input  type="password" name="Vsenha" id="Vsenha" required placeholder="Confirmar Senha" minlength="6" maxlength="18">
                                                <i class='bx bxs-show' id="toggleVsenha" style='cursor:pointer; color:#999999;'
                                                    onclick="togglePassword('Vsenha', 'toggleVsenha')"></i>
                                            </div>
                                        </div>
                                </div>
        
                            </div>
                            <div>
                                <div id="sectionfotos">
                                    <div class="sec-logo">
                                        <p>Logo do seu estabelecimento</p><br>
                                        <div class='img'>
                                            <input type="file" name="fileInput" id="fileInput" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <div>
        
                                        <div>
                                            <textarea name="comple" id="comple"  class="areatx" style="height: 100px" placeholder="Fale sobre seu espaço de trabalho"></textarea>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="footer-form">
                            <br><br>
                        <input class="button" type="submit" onclick="proximapg()" id="enviar" name="enviar" value="Próximo">
                    </div>
        
                    </form>
                </div>
        
        
        </div>
        
        
        
        </div>
    </div>





    <script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bxs-show');
            icon.classList.add('bxs-hide');
        } else {
            input.type = "password";
            icon.classList.remove('bxs-hide');
            icon.classList.add('bxs-show');
        }
    }

    function mascaraTelefone(telefone) {
        telefone.value = telefone.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        telefone.value = telefone.value.replace(/^(\d{2})(\d)/, '($1) $2'); // Formata como (99) 99999
        telefone.value = telefone.value.replace(/(\d{5})(\d)/, '$1-$2'); // Formata como (99) 99999-9999
    }

    function mascaraCnpjCpf(input) {
        let valor = input.value;
        valor = valor.replace(/\D/g, ""); // Remove caracteres não numéricos

        if (valor.length <= 11) { // CPF
            valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
            valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
            valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        } else { // CNPJ
            valor = valor.replace(/^(\d{2})(\d)/, "$1.$2");
            valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            valor = valor.replace(/\.(\d{3})(\d)/, ".$1/$2");
            valor = valor.replace(/(\d{4})(\d{1,2})$/, "$1-$2");
        }

        input.value = valor;
    }

    document.getElementById('fileInput').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const imgContainer = document.querySelector('.img');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imgContainer.style.backgroundImage = `url(${e.target.result})`;
            };
            reader.readAsDataURL(file);
        }
    });
    function proximapg() {
    const fileInput = document.getElementById('fileInput').files[0] ? document.getElementById('fileInput').files[0].name : '';
    const nome = document.getElementById('nome').value;
    const nomeE = document.getElementById('nomeE').value;
    const tipo = document.getElementById('tipo').value;
    const email = document.getElementById('email').value;
    const tel = document.getElementById('tel').value;
    const cnpjj = document.getElementById('cnpj/cpf').value;
    const senha = document.getElementById('senha').value;
    const Vsenha = document.getElementById('Vsenha').value;
    const comple = document.getElementById('comple').value;

    // Verifica se as senhas coincidem
    if (senha !== Vsenha) {
        return;
    }

    // Verifica se todos os campos obrigatórios estão preenchidos
    if (!nome || !nomeE || !tipo || !email || !tel || !cnpjj || !senha || !Vsenha || !comple) {
        return;
    }

    // Salva os dados no sessionStorage
    sessionStorage.setItem('fileInput', fileInput);
    sessionStorage.setItem('nome', nome);
    sessionStorage.setItem('nomeE', nomeE);
    sessionStorage.setItem('tipo', tipo);
    sessionStorage.setItem('email', email);
    sessionStorage.setItem('tel', tel);
    sessionStorage.setItem('cnpj', cnpjj);
    sessionStorage.setItem('senha', senha);
    sessionStorage.setItem('confirmarSenha', Vsenha);
    sessionStorage.setItem('comple', comple);


    
}

</script>

</body>

</html>