<?php
include('includes/conecta.php');
$plan = (int) $_GET['plan'];
$sql = "SELECT * FROM planos WHERE nivel = $plan";
$planos = mysqli_query($con, $sql);

if ($planos->num_rows == 1) {
    
    $planoss = $planos->fetch_assoc();
    $preco = $planoss['preco'];
    $nome = $planoss['Nome'];
    $nivel = $planoss['nivel'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/pagamento.css">
    <link rel="stylesheet" href="../css/cadastro-emp.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Agenvi</title>
    <style>
        .linha {
            width: 100%;
            height: 4px;
            margin: 30px 0;
            background-color: var(--c02);
            border-radius: 10px;
        }
        .linha2 {
            width: 98%;
            transition: 1s ease-in-out;
            height: 4px;
            margin: 30px 0;
            background-color: var(--c08);
            border-radius: 10px;
        }
       
    
        .form-container {
            width: 300px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form {
            position: relative;
            display: none;
            top: 100%;
            left: 50%; 
            transform: translateX(-50%);
            padding: 20px;
           
            border-radius: 0px 0px 8px 8px;
            background-color: #fff;
            display: none;
            flex-direction: column;
            gap: 20px;
        }
        .form.active {
            display: flex;
        }
        .dropdown-container {
            position: relative;
        }
        .header-cad>h2{
            margin-top: 6px;
            font-size: 24px;
            font-weight: bolder;
            color: var(--c08);
        }
        .logo{
            margin-top: 6px;
        }
    </style>
</head>
<body>
    
    <div class="bigbig">
        <div class="maior">
            <div class="parte-img">
                 <p class="logona"><img class="logocad" src="../img/logo-agenvi-branca.png" alt=""> AGENVI <br></p><br><br>
                 <p class="bemv">Bem vindo!</p><br><br>
                 <p class="txtF">Escolha um plano que mais combine com o seu espaço de trbalho</p>
            </div>
            <div class="parte-cadastro">
                <div class="header-cad">
                    <h2>Forma de pagamento</h2>
                    <div class="linha"><div class="linha2"></div></div>
                </div>
        
        
                <div class="body-cad">
                <div class="parte1pg">
        <div class="caixa-pg">
            <div class="pagpt1">
                <div class="caixapg1">
                    <p>Plano escolhido</p>
                    <p class="tipoplan"><?php echo $nome; ?> </p>
                </div>
                <div>
                    <p>Preço</p>
                    <p class="ttpre">R$ <?php echo $preco; ?><strong class="mes"> /mês</strong></p>
                </div>
                <hr class="vertical">
                <a class="editar" href="../php/cadastro-empresa4.php">Editar</a>
            </div>
            <br><br>
            <div class="imgpay">
                <?php include('includes/animacaopay.php'); ?>
            </div>
        </div>
        <div class="formapagamento">
            <!-- Botão estilizado como rádio -->
            <div class="toggle-radio">
                <input type="radio" id="qr-option" name="paymentOption" checked onclick="togglePaymentMethod('qr')">
                <label for="qr-option">Pix ❖</label>
                <input type="radio" id="card-option" name="paymentOption" onclick="togglePaymentMethod('card')">
                <label for="card-option">Cartão <i class='bx bxs-credit-card-front'></i></label>
            </div>
            <!-- Seção do QR Code -->
            <div class="caixa-qr" id="caixa-qr">
                <br>
                <h2 class="Qr">Use o QR code para executar o pagamento</h2>
                <br>
                <img class="Qrcode" src="../img/codigoQr-maua.jpg" alt="QR Code">
                <br>
                <p>Chave pix: <strong>21973628323</strong></p>
            </div>
            <!-- Seção do Cartão -->
            <div class="papai" id="papai" style="display: none;">
        
               <div class="body-cartao">
                          <form id="form1" class="oi" method="post" action="relizar-cadastro.php">
                                    <input type="hidden" name="nivel" id="nivel" value="<?php echo $nivel ?>">
                                    <div class="infoscartao">
                                        <div>
                                            <label for="name">Nome do titular do cartão</label><br>
                                            <input class="caixapg" type="text" id="name" name="name" required>
                                        </div>
                                        <div>
                                            <label for="cardNumber">Número do Cartão</label><br>
                                            <input  class="caixapg"  type="text" id="cardNumber" name="cardNumber" required placeholder="">
                                        </div>
                                        <div class="ladopg">
                                            <div>
                                                <label for="expiryDate">Data de vencimento</label>
                                                <input  class="caixapg2"  type="date" id="expiryDate" name="expiryDate" required class="data">
                                            </div>
                                            <div>
                                                <label for="cvv">Código de segurança(CVV)</label>
                                                <input  class="caixapg2"  type="text" id="cvv" name="cvv" required>
                                            </div>
                                         </div>
                                        <div>
                                            <label for="cpf">CPF</label><br>
                                            <input  class="caixapg"  type="text" id="cpf" name="cpf" maxlength="14" onkeyup="mascaraCPF(this)" required>
                                        </div>
                                    </div>
                                    <section class="certo">
                                        <input type="checkbox" id="terms" name="terms" required>
                                        <label for="terms2">Ao marcar esta opção, você concorda com os <strong>termos de uso do site</strong>, incluindo o pagamento único no valor atual (<strong>R$<?php echo $preco; ?></strong>). Este pagamento não será renovado automaticamente, e você não será cobrado novamente a menos que faça um novo pagamento manualmente.</label>
                                    </section>
                                    <input type="submit" value="Concluir" class="subim">
                                    <!-- Campos ocultos -->
                                    <input type="hidden" id="nomeE" name="nomeE">
                                    <input type="hidden" id="nome" name="nome">
                                    <input type="hidden" id="tipo" name="tipo">
                                    <input type="hidden" id="email" name="email">
                                    <input type="hidden" id="tel" name="tel">
                                    <input type="hidden" id="cnpj" name="cnpj">
                                    <input type="hidden" id="rua" name="rua">
                                    <input type="hidden" id="num" name="num">
                                    <input type="hidden" id="bairro" name="bairro">
                                    <input type="hidden" id="cep" name="cep">
                                    <input type="hidden" id="cidade" name="cidade">
                                    <input type="hidden" id="uf" name="uf">
                                    <input type="hidden" id="senha" name="senha">
                                    <input type="hidden" id="Vsenha" name="Vsenha">
                                    <input type="hidden" id="comple" name="comple">
                                </form>
               </div>
            </div>
        </div>
        
        <script>
            function togglePaymentMethod(option) {
                const caixaQr = document.getElementById("caixa-qr");
                const papai = document.getElementById("papai");
                if (option === 'qr') {
                    caixaQr.style.display = "block";
                    papai.style.display = "none";
                } else {
                    caixaQr.style.display = "none";
                    papai.style.display = "block";
                }
            }
        </script>
        </div>
        
        
        
        
        </div>
        
        
        
         </div>
        </div>
        
        
    </div>         

 




 
    
<!-- Inclua o CSS do Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Inclua o JS do Bootstrap com Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    function preencherCampos() {
        document.getElementById('nome').value = sessionStorage.getItem('nome') || '';
        document.getElementById('nomeE').value = sessionStorage.getItem('nomeE') || '';
        document.getElementById('tipo').value = sessionStorage.getItem('tipo') || '';
        document.getElementById('email').value = sessionStorage.getItem('email') || '';
        document.getElementById('tel').value = sessionStorage.getItem('tel') || '';
        document.getElementById('cnpj').value = sessionStorage.getItem('cnpj') || '';
        document.getElementById('cep').value = sessionStorage.getItem('cep') || '';
        document.getElementById('cidade').value = sessionStorage.getItem('cidade') || '';
        document.getElementById('uf').value = sessionStorage.getItem('uf') || '';
        document.getElementById('bairro').value = sessionStorage.getItem('bairro') || '';
        document.getElementById('rua').value = sessionStorage.getItem('rua') || '';
        document.getElementById('num').value = sessionStorage.getItem('num') || '';
        document.getElementById('tipo').value = sessionStorage.getItem('tipo') || '';
        document.getElementById('comple').value = sessionStorage.getItem('comple') || '';
        document.getElementById('senha').value = sessionStorage.getItem('senha') || '';
        document.getElementById('Vsenha').value = sessionStorage.getItem('Vsenha') || '';
        
        
    }

    preencherCampos();
    
    document.getElementById('fileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const imagePreview = document.createElement('img');
        const reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.width = '200px'; 
            document.body.appendChild(imagePreview); 
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('cadastroForm').addEventListener('submit', function(event) {
        event.preventDefault();

        
        finalizar();
    });

    function finalizar() {
        const senha = document.getElementById('senha').value;
        const Vsenha = document.getElementById('Vsenha').value;

        if (senha !== Vsenha) {
            alert('As senhas não coincidem. Por favor, verifique.');
            return false;
        }
        return false;
    }
});



function mascaraCPF(input) {
    let valor = input.value;

    // Remove tudo que não for número
    valor = valor.replace(/\D/g, "");

    // Aplica a máscara de CPF (000.000.000-00)
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

    // Atualiza o valor do campo com a máscara aplicada
    input.value = valor;}

</script>
</body>
</html>

