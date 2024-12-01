<?php
// Inclui o arquivo de conexão
include_once 'includes/conecta.php';

// Inicializa variáveis e trata a requisição POST
$errors = [];
$success = false;
$mensagem_erro = ''; // Inicialize a variável

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST["nome"] ?? null;
    $datan = $_POST["data_nasc"] ?? "";
    $telefone = $_POST["telefone"] ?? "";
    $email = $_POST['email'] ?? null;
    $cep = $_POST["cep"] ?? "";
    $senha = $_POST['senha'] ?? null;
    $genero = $_POST["genero"] ?? "";
    $Vsenha = $_POST["Vsenha"] ?? null;

    // Valida dados
    if (empty($cep)) {
        $errors[] = "CEP não pode estar vazio.";
    } elseif (empty($email) || empty($senha)) {
        $errors[] = "Email ou senha não pode estar vazio.";
    } elseif (strlen($senha) < 6 || strlen($senha) > 18) {
        $errors[] = "A senha deve conter entre 6 a 18 caracteres.";
    } elseif ($senha !== $Vsenha) {
        $errors[] = "As senhas devem ser iguais.";
        
        $mensagem_erro = "<p class='erro'><i class='bx bx-error'></i>As senhas devem ser iguais!</p>";

    }

    // Se não houver erros, continue com o processamento
    if (empty($errors)) {
        // Verifica se o CEP já está cadastrado na tabela localidade
        $sql = "SELECT * FROM localidade WHERE cep = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $cep);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Busca os dados do CEP na API ViaCEP
            $url = "https://viacep.com.br/ws/$cep/json/";
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if (isset($data['erro'])) {
                $errors[] = "CEP não encontrado.";
            } else {
                $bairro = $data['bairro'];
                $cidade = $data['localidade'];
                $estado = $data['uf'];
                $logradouro = $data['logradouro'];

                // Insere os dados na tabela localidade
                $sql_insert = "INSERT INTO localidade (cep, bairro, cidade, uf, logradouro) VALUES (?, ?, ?, ?, ?)";
                $stmt_insert = $con->prepare($sql_insert);
                $stmt_insert->bind_param("sssss", $cep, $bairro, $cidade, $estado, $logradouro);
                $stmt_insert->execute();
            }
        }

        // Verifica se o email já existe
        $sqluser = $con->prepare("SELECT email FROM cliente WHERE email = ?");
        $sqluser->bind_param("s", $email);
        $sqluser->execute();
        $total = $sqluser->get_result()->num_rows;

        if ($total > 0) {
            $errors[] = "O email já existe.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO cliente (nome_cliente, data_nasc, telefone, email, cep, senha, genero, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, 'semimagem.png')";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssssss", $nome, $datan, $telefone, $email, $cep, $hash, $genero);
            $result = $stmt->execute();

            $sql_code = "SELECT * FROM cliente WHERE email = '$email'";
            $sql_query = $con->query($sql_code) or die("Falha na execução do código SQL: " . $con->error);

            $empresa = $sql_query->fetch_assoc();
            if ($result) {
                // Inicia a sessão e armazena os dados
                session_start();
                $_SESSION['idcliente'] = $empresa['idcliente'];
                $_SESSION['nome'] = $empresa['nome'];

                // Redireciona para a página de perfil do cliente
                header("Location: perfil-cliente.php");
                exit();
            } else {
                $errors[] = "Não foi possível se cadastrar.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/cadastro-cli.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Agenvi</title>
    <script src="../js/api.js"></script>
    <script>
        function mascaraCEP(cep) {
            cep.value = cep.value.replace(/\D/g, ''); // Remove caracteres não numéricos
            cep.value = cep.value.replace(/^(\d{5})(\d)/, '$1-$2'); // Formata como 12345-678
        }

        function mascaraTelefone(telefone) {
            telefone.value = telefone.value.replace(/\D/g, ''); // Remove caracteres não numéricos
            telefone.value = telefone.value.replace(/^(\d{2})(\d)/, '($1) $2'); // Formata como (99) 99999
            telefone.value = telefone.value.replace(/(\d{5})(\d)/, '$1-$2'); // Formata como (99) 99999-9999
        }

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
    </script>
</head>
<body>
  

<div class="bigbig">
    
    <div class="tudao">
    
    
            <div class="papai">
                <div class="imgcad">
                    <p class="logona"><img class="logocad" src="../img/logo-agenvi-branca.png" alt=""> AGENVI <br></p><br><br><br>
                    <p class="txt"><strong>Seja bem vindo!</strong><br> <br>Acessece sua conta <br> a qualquer momento!</p>
                </div>
    
    
                <div class="div-cadastro">
    
                    <div class="header-cad">
                        <h2>Crie sua conta</h2>
                    </div>
                    <p><?php echo $mensagem_erro;?></p>
                    <div class="body-cad">
                        <form action="#" enctype="multipart/form-data" method="post">
                            <div class="grande">
                                <div class="parte1">
                                    <div>
                                        <label for="nome">Nome</label>
                                        <div class="caixa">
                                            <i class='bx bxs-user' style='color:#999999'></i>
                                            <input class="menor" type="text" name="nome" id="nome" required placeholder="Nome">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="data_nasc">Data de nascimento</label>
                                        <div class="caixa">
                                            <i class='bx bxs-calendar' style='color:#999999'></i>
                                            <input class="menor" type="date" name="data_nasc" id="data_nasc" required class="inpt1">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="genero">Gênero</label><br>
                                        <select name="genero" id="genero" required>
                                            <option value="M">Masculino</option>
                                            <option value="F">Feminino</option>
                                            <option value="NI">Prefiro não informar</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="telefone">Telefone:</label>
                                        <div class="caixa">
                                            <i class='bx bxs-phone' style='color:#999999'></i>
                                            <input class="menor" type="text" name="telefone" id="telefone" maxlength="15" onkeyup="mascaraTelefone(this)" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="email">E-mail</label>
                                        <div class="caixa">
                                            <i class='bx bxs-envelope' style='color:#999999'></i>
                                            <input class="menorE" type="email" name="email" id="email" required placeholder="Digite o seu email">
                                        </div>
                                    </div>
                                </div>
    
                                <div class="parte2">
                                    <div class="lado">
                                        <div>
                                            <label for="cep">CEP</label>
                                            <div class="caixa">
                                                <i class='bx bxs-pin' style='color:#999999'></i>
                                                <input class="menor" type="text" name="cep" id="cep" maxlength="10" onblur="pesquisacep(this.value);" onkeyup="mascaraCEP(this)" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="uf">UF</label>
                                            <div class="caixaUF">
                                                <i class='bx bxs-pin' style='color:#999999'></i>
                                                <input class="menorUF" type="text" name="uf" id="uf" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="rua">Rua</label>
                                        <div class="caixa">
                                            <i class='bx bxs-pin' style='color:#999999'></i>
                                            <input class="menor" type="text" name="rua" id="rua" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="bairro">Bairro</label>
                                        <div class="caixa">
                                            <i class='bx bxs-pin' style='color:#999999'></i>
                                            <input class="menor" type="text" name="bairro" id="bairro" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="cidade">Cidade</label>
                                        <div class="caixa">
                                            <i class='bx bxs-pin' style='color:#999999'></i>
                                            <input class="menor" type="text" name="cidade" id="cidade" required>
                                        </div>
                                    </div>
                                    <div class="lado">
                                        <div>
                                            <label for="senha">Senha</label>
                                            <div class="caixaS">
                                                <i class='bx bxs-lock' style='color:#999999'></i>
                                                <input class="menorS" type="password" name="senha" id="senha" required placeholder="senha" minlength="6" maxlength="18">
                                                <i class='bx bxs-show' id="toggleSenha" style='cursor:pointer;color:#999999;' onclick="togglePassword('senha', 'toggleSenha')"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="Vsenha">Confirme a Senha</label>
                                            <div class="caixaS">
                                                <i class='bx bxs-lock' style='color:#999999'></i>
                                                <input class="menorS" type="password" name="Vsenha" id="Vsenha" required placeholder="senha" minlength="6" maxlength="18">
                                                <i class='bx bxs-show' id="toggleVsenha" style='cursor:pointer; color:#999999;' onclick="togglePassword('Vsenha', 'toggleVsenha')"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                            <div class="footer-form"><input class="button" type="submit" value="Começar"></div>
                        </form>
                    </div>
                </div>
            </div>
    
    </div>
</div>

</body>
</html>
