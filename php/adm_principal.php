
<?php
include("protect_adm.php");
include("includes/logar-sistema.php");
?>

<!DOCTYPE html>
<html lang="pt-br" dir="ltr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/lista-func.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="shortcut icon" type="imagex/png" href="../img/Logo-agenvi.png">
  <title>Agenvi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <?php
  include("includes/navbar-adm.php");
  ?>
    <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu'></i>
      <span class="text">Empresas</span>
    </div>

<div class="ADM_pr">
  <table class="table table-bordered">
      <thead>
          <tr>
              <th>Nome</th>
              <th>Nome fantasia</th>
              <th>Plano</th>
              <th>Categoria</th>
              <th>CEP</th>
              <th>Telefone</th>
              <th>Email</th>
              <th>Ações</th>
          </tr>
      </thead>
      <tbody>
          <?php
          $sql_empresa = "SELECT idempresa, nome, nome_fantasia, nivel, tipo, cep, telefone, email from empresa";
          $sql_query = mysqli_query($mysqli, $sql_empresa);
          while ($result = mysqli_fetch_assoc($sql_query)) {
          ?>
              <tr>
                  <td><?php echo $result['nome']; ?></td>
                  <td><?php echo $result['nome_fantasia']; ?></td>
                  <td><?php echo $result['nivel']; ?></td>
                  <td><?php echo $result['tipo']; ?></td>
                  <td><?php echo $result['cep']; ?></td>
                  <td><?php echo $result['telefone']; ?></td>
                  <td><?php echo $result['email']; ?></td>
                  <td>
  
                      <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $result['idempresa']; ?>">Excluir</button>
  
                      <a href="visualizar_empresa.php?id=<?php echo $result['idempresa']; ?>" class="btn btn-info">Visualizar</a>
                  </td>
              </tr>
          <?php } ?>
      </tbody>
  </table>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja excluir esta empresa?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Excluir</a>
            </div>
        </div>
    </div>
</div>



  <script>
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
      arrow[i].addEventListener("click", (e) => {
        let arrowParent = e.target.parentElement.parentElement; // Selecting main parent of arrow
        arrowParent.classList.toggle("showMenu");
      });
    }

    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    sidebarBtn.addEventListener("click", () => {
      sidebar.classList.toggle("close");
    });
        
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var empresaId = button.data('id'); 
        
      
        var modal = $(this);
        modal.find('#confirmDeleteBtn').attr('href', 'excluir_empresa.php?id=' + empresaId);
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
</body>

</html>