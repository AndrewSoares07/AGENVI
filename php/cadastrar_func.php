<?php
include ("protect.php");
include("includes/logar-sistema.php");


$id_empresa = $_SESSION['idempresa'];

$query = "SELECT s.idservico, s.nome_serv 
          FROM servicos s
          INNER JOIN lista_servicos_empresa lse ON s.idservico = lse.idservico
          WHERE lse.idempresa = ?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

$servicos = [];

while ($row = $result->fetch_assoc()) {
    $servicos[$row['idservico']] = $row['nome_serv'];
}

$selected_services = [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['servicos'])) {
        $selected_services = $_POST['servicos'];
    }
}



// Buscar os dias e horários que a empresa trabalha
$sql_horarios_empresa = "SELECT dias_semana, horario_ini, horario_fim FROM horarios_empresa WHERE idempresa = $id_empresa";
$result_horarios = $mysqli->query($sql_horarios_empresa);

$empresa_horarios = [];
if ($result_horarios->num_rows > 0) {
    while ($roww = $result_horarios->fetch_assoc()) {
        $empresa_horarios[$roww['dias_semana']] = [
            'horario_ini' => $roww['horario_ini'],
            'horario_fim' => $roww['horario_fim']
        ];
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cadastro_func.css">
    <title>Agenvi</title>
</head>
<body>

<?php
 include ("includes/navbar.php")
?>



  <section class="home-section">

    
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Cadastro de funcionário</span>
    </div>

    
    <div class="tudo">
    
    <form action="salvar_func.php" method="POST" enctype="multipart/form-data">
          <h3>Novo Funcionário</h3>
       <br>
       <p class="pft">Clique para adicionar a foto</p>
       <div class='img'>
         
          <input  type="file" name="foto_func" id="foto_func">
        </div> 
        <hr>
       
       
        
        
               
         
<div class="cadfpt1">
                   
 <div class="infos">
     <div class="lado">
        
          <div>
            <br><label for="">Nome funcionario</label>
            <div class="div1">
            <i class='bx bxs-user' style='color:#999999'></i>
            <input type="text" class="nome" id="nome_func" name="nome_func"required  >
            </div>
          </div>
        
            <div>
           <br> <label for="">Celular:</label>
              <div class="div3">
                <i class='bx bxs-phone' style='color:#999999' ></i>
                <input type="text" id="cell" name="cell" class="tel" required placeholder=" (21)"  maxlength="15" onkeyup="mascaraTelefone(this)" >
                </div>
              </div>

     </div>


     <div class="lado">
     <div>
              <br><label for="">CPF</label>
              <div class="div4">
              <i class='bx bxs-pin' style='color:#999999' ></i>
              <input type="text" id="cpf" name="cpf" class="cpf" required placeholder="Apenas números" maxlength="14" onkeyup="mascaraCpf(this)">
              </div>
          </div>
          <div >
             <br> <label for="">Data de nascimento</label>
              <div class="div2" >
              <input type="date" id="dt_nasc" name="dt_nasc" class="data"required  >
              </div>
            </div>

         
     </div>
  
        <br><label for="">Email</label><br>
        <div class="dive">
        <i class='bx bxs-envelope'style='color:#999999' ></i>
        <input type="email" id="email" name="email" class="email"required placeholder="Digite o e-mail do funcionário" >
        </div>
</div>
  
      <div class="vertical"></div>
  
         
        <div class="trab">
          <label for="servico">Selecione os dias de trabalho</label>  <br><br>
            <div class="ordem_hor">
            <?php
            $days_of_week = ["seg", "ter", "qua", "qui", "sex", "sab", "dom"];
            foreach ($days_of_week as $day) {
              
                if (!array_key_exists($day, $empresa_horarios)) {
   continue; 
                }
            
              
                $horario_ini = $empresa_horarios[$day]['horario_ini'];
                $horario_fim = $empresa_horarios[$day]['horario_fim'];
                ?>
                
              
                <div class='day-container'>
   <div class="label-day">
     <input class="chack" type="checkbox" name="dia_semana[]" value="<?php echo $day; ?>">
     <label for=""> <?php echo ucfirst($day); ?></label>
   </div>
  
   <div class='hora'>
       <div class='pt1'>
  
           <input class='inp4' type="time" name="horario_ini[<?php echo $day; ?>]" value="<?php echo $horario_ini; ?>" min="<?php echo $horario_ini; ?>" max="<?php echo $horario_fim; ?>">
  
           <label>ás</label>
           <input class='inp4' type="time" name="horario_fim[<?php echo $day; ?>]" value="<?php echo $horario_fim; ?>" min="<?php echo $horario_ini; ?>" max="<?php echo $horario_fim; ?>">
       </div>
   </div>
                </div>
            
            <?php } ?>
            </div>
        </div>
</div>


       <div class="divserv">
                <label for="servico">Selecione os serviço:</label><br><br>
                <div class="row">
                    <?php foreach ($servicos as $id => $nome) : ?>

                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicos[]" id="servico_<?php echo $id; ?>" value="<?php echo $id; ?>"
                                      <?php echo in_array($id, $selected_services) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="servico_<?php echo $id; ?>">
                                    <?php echo $nome; ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
</div>

      
          

          <input type="submit" value="Salvar" class="button">
          </form>      
          
    
    </div> 
    
  </section>


  <script>

function mascaraCpf(input) {
    let valor = input.value;

    // Remove tudo que não for número
    valor = valor.replace(/\D/g, "");

    // Aplica a máscara do CPF (000.000.000-00)
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

    // Atualiza o valor do input com a máscara aplicada
    input.value = valor;
}


  function mascaraTelefone(telefone) {
            telefone.value = telefone.value.replace(/\D/g, ''); // Remove caracteres não numéricos
            telefone.value = telefone.value.replace(/^(\d{2})(\d)/, '($1) $2'); // Formata como (99) 99999
            telefone.value = telefone.value.replace(/(\d{5})(\d)/, '$1-$2'); // Formata como (99) 99999-9999
        }



      document.getElementById('foto_func').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const imgContainer = document.querySelector('.img');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgContainer.style.backgroundImage = `url(${e.target.result})`;
                };
                reader.readAsDataURL(file);
            }
        });
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