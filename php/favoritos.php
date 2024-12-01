<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');

$idcliente = $_SESSION['idcliente'];

$sql = "SELECT 
c.idcliente,    
e.idempresa,
e.nome,
e.nome_fantasia,
e.foto_empresa,
e.foto_amb1,
1 AS is_favorited,
COALESCE(AVG(a.estrelas), 0) AS media_estrelas, 
COUNT(a.idavaliacao) AS total_avaliacoes,

l.cidade
FROM favoritos f 
inner JOIN cliente c on f.idcliente = c.idcliente
INNER JOIN empresa e ON f.idempresa = e.idempresa
left JOIN  avaliacao a ON a.idempresa = e.idempresa
INNER JOIN localidade l ON l.cep = e.cep 
WHERE c.idcliente = $idcliente
GROUP BY c.idcliente, e.idempresa,e.nome,e.foto_empresa,e.foto_amb1,l.cidade
;";

$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <title>Agenvi</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/favoritos.css">
   
</head>
<body>
    
    <header>
    <a href="../php/principal-cliente.php"><img class="seta" src="../img/seta-esquerda.png" alt=""><img class="logo" src="../img/logo-completona.png" alt=""></a> 
   

    <a class="sair" href="logout.php"><i class='bx bx-exit' style='color:#ffffff'  ></i> Sair</a>
    
    </header>



    

<div class="papai">


     <?php
      include("includes/infos-perfil-cli.php")
      ?>
    
    
    
    <div class="central">
    <h3>Favoritos  
            <?php
                $sml = "SELECT COUNT(*) AS total_favoritas
                FROM favoritos
                WHERE idcliente = $idcliente";
                $sss = $mysqli->prepare($sml);
                $sss->execute();
                $sss->bind_result($total_favoritas);
                $sss->fetch();
                $sss->close();
                echo "<strong class='fav'>({$total_favoritas})</strong>";
            
            ?></h3><br><br>

   

<div class="d-flex  justify-content-start flex-wrap" >
    
    
            <?php while ($row = $result->fetch_assoc()) { ?>
                <a href="agendamento.php?id=<?= htmlspecialchars($row['idempresa']); ?>">
    
                    <div class="pai" id="empresa-<?= $row['idempresa'] ?>">

                    <button class="favorite-button" data-idempresa="<?= $row['idempresa'] ?>">
                                    <img src="../img/<?= $row['is_favorited'] ? 'heath.png' : 'semheath.png' ?>" class="heart-icon" />
                                </button>
    
                            <?php
                            $foto_amb1_path = 'arquivos/' . htmlspecialchars($row['foto_amb1']);
                            echo "<div class='filho' style='background-image: url($foto_amb1_path); background-size: cover;'></div>";
                            ?>
                             <?php
                          $foto_amb2_path = 'arquivos/' . htmlspecialchars($row['foto_empresa']);
                          echo "<div class='filho2' style='background-image: url($foto_amb2_path); background-size: cover;'></div>";
    
                        ?>
    
    
    
                       
                           
                                <h5 style="color: #7C5AFF;   font-weight: bold;"><?= htmlspecialchars($row['nome_fantasia']) ?></h5>
    
                          
                       
                                <div class="star-rating">
                                    <?php
                                    $fullStars = floor($row['media_estrelas']);
                                    $halfStar = ($row['media_estrelas'] - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    for ($i = 0; $i < $fullStars; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    if ($halfStar) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    for ($i = 0; $i < $emptyStars; $i++) {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                              
                         
                        </div>
                    </a>
                    <?php } ?>
                </div>
    
</div>
    </div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('.favorite-button').on('click', function(e){
            e.preventDefault();
            var button = $(this);
            var idempresa = button.data('idempresa');
            var icon = button.find('.heart-icon');

            $.ajax({
                url: 'favoritar.php',
                method: 'POST',
                data: {idempresa: idempresa, action: 'unfavorite'}, // Sempre desfavoritar
                dataType: 'json',
                success: function(response){
                    if (response.success) {
                        // Remove a empresa da página imediatamente
                        button.closest('.pai').remove();
                    } else {
                        alert('Erro ao atualizar favorito');
                    }
                },
                error: function(){
                    alert('Erro ao processar a solicitação');
                }
            });
        });
    });
</script>




</body>
</html>
