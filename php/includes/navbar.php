
<div class="sidebar close">

    <div class="headerft">

        <div>
            <a href="../php/perfil-empresa.php">
                <?php
                      include("informaçoes_empresa.php");
                      echo "<img class='imgperfil' src='arquivos/$img_perfil'> ";
                      echo" <p class='nomef'>$nomef </p>"
                  ?>
            </a>

            
        </div>

      </div>
  
    <ul class="nav-links">




      <li>
        <a href="agendamento_emp.php">
        <i class='bx bxs-calendar' ></i>
          <span class="link_name">Agendamentos</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../php/relatorio/relatorio/gerar_relatorio.php">Agendamentos</a></li>
        </ul>
      
      </li>
      

      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-collection' ></i>
            <span class="link_name">Cadastros</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Cadastros</a></li>
          <li><a href="lista_func.php">Funcionários</a></li>
          <li><a href="lista_servicos.php">Serviços</a></li>
          
        </ul>
      </li>
      
     
      <li>
        <a href="../php/historico-empresa.php">
          <i class='bx bx-history'></i>
          <span class="link_name">Histórico</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Histórico</a></li>
        </ul>
      </li>
   

      <li>
        <a href="../php/principal-empresa.php">
        <i class='bx bx-trending-up'></i>
          <span class="link_name">Faturamentos</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Faturamentos</a></li>
        </ul>
      </li>
     
   

      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bxs-cog'></i>
            <span class="link_name">Ajustes</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Configurações</a></li>
          <li><a href="perfil-empresa.php">Perfil</a></li>
          <li><a href="lista_servicos.php">Serviços</a></li>
          <li><a href="horario_empresa.php">horario da empresa</a></li>
          <li><a href="#">Bloqueados</a></li>
          <li> <a href="logout.php">Sair</a></li>
          
        </ul>
      </li>

      <li>
    <div class="profile-details">
      <div class="profile-content">
        <!--<img src="image/profile.jpg" alt="profileImg">-->
      </div>
      <a href="logout.php">
        <div class="name-job">
          <div class="profile_name">Log-out</div>
          <div class="job">Sair da conta</div>
        </div>
        <i class='bx bx-log-out' ></i>
            </div>
      </a>
  </li>
</ul>
  </div>

