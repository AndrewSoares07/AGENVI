
<?php
//$idempresa = $_GET['id'];

include('includes/logar-sistema.php');
include('protect_cliente.php');

$idempresa = $_GET['idempresa'];


$idcliente = $_SESSION['idcliente'];

$idavaliacao = $_GET['idavaliacao'];


$rating = $_POST['estrela'] ?? "";
$comentario = $_POST['comentario'] ?? "";

              if ($_SERVER["REQUEST_METHOD"] == "POST") {

                 // Receber os dados
                 $comentario = $mysqli->real_escape_string($_POST['comentario']);
                 $rating = (int)$_POST['rating'];

                 // Inserir no banco de dados
                 $sql = "UPDATE avaliacao 
                        SET 
                            estrelas = $rating, 
                            chat = '$comentario' 
                             WHERE idavaliacao=$idavaliacao";
                 if ($mysqli->query($sql) === TRUE) {
                     echo "<script>alert('Comentário salvo com sucesso!');</script>";
                     header("Location: agendamento.php?id=" . $idempresa);

                 } else {
                     echo "<script>alert('Erro ao salvar comentário: " . $conn->error . "');</script>";
                 }
             }
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 </head>
 <body>
 <form id="formComentario" method="POST" action="#">
            <textarea id="comentario" name="comentario" placeholder="Digite seu comentário..." required></textarea>
        
            <input type="hidden" name="empresaid" value="<?= $idempresa?>">
            <div class="estrelas" name="estrela">
                <span class="estrela" onclick="selectStar(1)">★</span>
                <span class="estrela" onclick="selectStar(2)">★</span>
                <span class="estrela" onclick="selectStar(3)">★</span>
                <span class="estrela" onclick="selectStar(4)">★</span>
                <span class="estrela" onclick="selectStar(5)">★</span>
            </div>
            <input type="hidden" name="rating" id="rating" value="0">
            <div class="divbutava"><button class="buttonava" type="button" onclick="submitForm()">Enviar</button></div>
        </form>
 </body>

 <script>

        let selectedRating = 0;

        function selectStar(value) {
            selectedRating = value;
            const estrelas = document.querySelectorAll('.estrela');
            estrelas.forEach((estrela, index) => {
                estrela.classList.toggle('selected', index < value);
            });
        }

        function submitForm() {
            const comentario = document.getElementById('comentario').value;
            if (comentario === '' || selectedRating === 0) {
                alert('Por favor, preencha o comentário e selecione uma avaliação.');
                return;
            }
            document.getElementById('rating').value = selectedRating; // Armazena a avaliação no campo oculto
            document.getElementById('formComentario').submit();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('commentModal');
            if (event.target == modal) {
                toggleModal();
            }
        }

        function toggleUniqueModal() {
        const modal = document.getElementById('uniqueCommentModal');
        modal.style.display = (modal.style.display === 'block') ? 'none' : 'block';
    }

   
    </script>

    
<style>

  
        

.modal {
    display: none; /* Escondido por padrão */
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.4); 
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 80%; 
}

.close {
    color: #000;
    font-weight: 400;
    font-size: 20px;
    float: right;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
}

.estrelas {
    font-size: 30px;
    cursor: pointer;
}

.estrelas .estrela {
    color: gray;
}

.estrelas .estrela.selected {
    color: purple;
}
textarea{
    width: 100%;
    padding: 10px;
    height: 100px;
    border: none;
    border: 1px solid #E9E3FF;
    border-radius: 10px;
}
</style>


 </html>