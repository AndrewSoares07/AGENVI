<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/cadastro-emp.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="../js/api.js"></script>
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
    width: 20%;
    height: 4px;
    margin: 30px 0px 30px 0px;
   background-color: var(--c08);
    border-radius: 10px;
   
}
    </style>
    


<div class="bigbig">
    
    <div class="maior">
        <div class="parte-img">
    
    
    
             <p class="logona"><img class="logocad" src="../img/logo-agenvi-branca.png" alt=""> AGENVI <br></p><br><br>
             <p class="bemv">Bem vindo!</p><br><br>
             <p class="txtL">Onde que seu estabelecimento se encontrará?</p>
    
        </div>
        <div class="parte-cadastro">
            <div class="header-cad">
    
    
            <h2>Localização da empresa</h2>
                <div class="linha"><div class="linha2"></div></div>
            </div>
    
    
            <form action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
                        <div class="body-cadL">
            <div>
                <div class="lado">
                        <div>
                         <label for="cep">CEP</label>
                            <div class="caixaL">
                            <i class='bx bxs-pin' style='color:#999999'></i>
                            <input class="menor" type="text" name="cep" id="cep" maxlength="12" onblur="pesquisacep(this.value);" onkeyup="mascaraCEP(this)" required>
                            </div>
                        </div>
                        <div>
                            <label for="uf">UF</label>
                             <div class="caixaL2">
                             <i class='bx bxs-pin' style='color:#999999'></i>
                             <input class="menor" type="text" name="uf" id="uf" required>
                             </div>
                        </div>
                </div>
                     <div>
                            <label for="cidade">Cidade</label>
                            <div class="caixaL3">
                                <i class='bx bxs-pin' style='color:#999999'></i>
                                <input class="menor" type="text" name="cidade" id="cidade" required>
                            </div>
                        </div>
                        <div>
                                <label for="rua">Rua</label>
                                <div class="caixaL3">
                                    <i class='bx bxs-pin' style='color:#999999'></i>
                                    <input class="menor" type="text" name="rua" id="rua" required>
                                </div>
                            </div>
    
    
                        <div class="lado">
                        <div>
                            <label for="bairro">Bairro</label>
                            <div class="caixaL">
                                <i class='bx bxs-pin' style='color:#999999'></i>
                                <input class="menor" type="text" name="bairro" id="bairro" required>
                            </div>
                        </div>
                            <div>
                                <label for="num">Nº</label>
                                <div class="caixaL2">
                                    <i class='bx bxs-pin' style='color:#999999'></i>
                                    <input class="menor" type="text" name="num" id="num" required>
                                </div>
                            </div>
                        </div>
    
            </div>
                    <iframe class="mapp" id="map" frameborder="0" allowfullscreen></iframe>
            </div>
           <br><br>
        <div class="footer-form">
    
                <button class="button" type="button" onclick="proximapg2()">Próximo</button>
    
            </div>
         <br>
    
    
            </div>
    </form>
    
    </div>
</div>






<script>
      function mascaraCEP(cep) {
            cep.value = cep.value.replace(/\D/g, ''); // Remove caracteres não numéricos
            cep.value = cep.value.replace(/^(\d{5})(\d)/, '$1-$2'); // Formata como 12345-678
        }
     
    console.log(sessionStorage.getItem('cnpj'));

    function updateMap() {
        const cep = document.getElementById('cep').value;
        const cidade = document.getElementById('cidade').value;
        const uf = document.getElementById('uf').value;
        const bairro = document.getElementById('bairro').value;
        const rua = document.getElementById('rua').value;
        const num = document.getElementById('num').value;
        const address = `${rua}, ${num}, ${bairro}, ${cidade} - ${uf}, ${cep}`;
        const mapUrl = `https://maps.google.com/maps?q=${encodeURIComponent(address)}&z=16&output=embed`;
        document.getElementById('map').src = mapUrl;
    }

    document.getElementById('cep').addEventListener('input', updateMap);
    document.getElementById('cidade').addEventListener('input', updateMap);
    document.getElementById('uf').addEventListener('input', updateMap);
    document.getElementById('bairro').addEventListener('input', updateMap);
    document.getElementById('rua').addEventListener('input', updateMap);
    document.getElementById('num').addEventListener('input', updateMap);
   

    function proximapg2() {
        const cep = document.getElementById('cep').value;
        const cidade = document.getElementById('cidade').value;
        const uf = document.getElementById('uf').value;
        const bairro = document.getElementById('bairro').value;
        const rua = document.getElementById('rua').value;
        const num = document.getElementById('num').value;
        

        sessionStorage.setItem('cep', cep);
        sessionStorage.setItem('cidade', cidade);
        sessionStorage.setItem('uf', uf);
        sessionStorage.setItem('bairro', bairro);
        sessionStorage.setItem('rua', rua);
        sessionStorage.setItem('num', num);

        window.location.href = 'cadastro-empresa3.php';
    }
</script>

</body>
</html>
