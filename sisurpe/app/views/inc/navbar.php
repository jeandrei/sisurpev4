<nav class="navbar navbar-expand-lg navbar-dark bg-dark md-3" style="margin-bottom:10px;">
  <div class="container">
      <a class="navbar-brand" href="<?php echo URLROOT; ?>/pages/index"><?php echo SITENAME; ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
              

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        
        <ul class="navbar-nav mr-auto">  
          
            <li class="nav-item">
                <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about">Sobre</a>
            </li> 
            <?php if(isLoggedIn()) :?>

              <!--FAZ A VERIFICAÇÃO SE O USUÁRIO É ADMINISTRADOR, OU SECRETÁRIO SE SIM CARREGA OS MENUS DE CADASTRO-->
                        
              <?php if((isset($_SESSION[DB_NAME . '_user_type']))&&((($_SESSION[DB_NAME . '_user_type']) == "admin")||(($_SESSION[DB_NAME . '_user_type']) == "sec"))) : ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Consulta
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/fbuscaservidores">Busca Servidor</a>
                    <!-- 
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/buscaalunos">Busca Alunos</a>
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/buscadadosescolars">Busca Dados Escolares</a>
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/buscadadostransportes">Busca Dados Transporte</a>-->
                  </div>
                </li>       
              <?php endif; ?> 

              
              <!--FAZ A VERIFICAÇÃO SE O USUÁRIO É ADMINISTRADOR, OU SECRETÁRIO SE SIM CARREGA OS MENUS DE CADASTRO-->
              <?php if(isset($_SESSION[DB_NAME . '_user_type']) && ($_SESSION[DB_NAME . '_user_type']) == "admin") : ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Administração
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/adminusers/index">Usuários</a>          
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/escolas/index">Unidades</a>
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/certificados/index">Certificados</a>
                  </div>
                </li>              
              <?php endif; ?> 

              <?php if((isset($_SESSION[DB_NAME . '_user_type']))&&((($_SESSION[DB_NAME . '_user_type']) == "admin")||(($_SESSION[DB_NAME . '_user_type']) == "sec"))) : ?>
                <li class="nav-item">
                <a class="nav-link" href="<?php echo URLROOT; ?>/relatorios/index">Relatórios</a>
                </li> 
              <?php endif; ?> 

              <?php if(isset($_SESSION[DB_NAME . '_user_type']) && ($_SESSION[DB_NAME . '_user_type']) == "admin" || ($_SESSION[DB_NAME . '_user_type']) == "coleta") : ?>



                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Coletas
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/coletas/index">Coletar Dados</a>
                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/coletas/geradordebilhetes">Gerador de bilhetes</a>
                  </div>
                </li>               
              <?php endif; ?> 
              

              <?php if(isLoggedIn()) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/datausers/show">Alunos</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/inscricoes/index">Inscrições</a>
                </li> 
              <?php endif; ?>
            <?php endif;?>
        </ul>


        <ul class="navbar-nav ml-auto">
          <?php if(isset($_SESSION[DB_NAME . '_user_id'])) : ?>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Bem vindo <?php echo $_SESSION[DB_NAME . '_user_name']; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                  <a class="dropdown-item" href="<?php echo URLROOT; ?>/users/alterasenha">Alterar a Senha</a>
                  <a class="dropdown-item" href="<?php echo URLROOT; ?>/fuserescolaanos/userEscolaAno/<?php echo $_SESSION[DB_NAME . '_user_id']; ?>">Dados do Servidor</a>                          
                  <a class="dropdown-item" href="<?php echo URLROOT; ?>/userscertificados/index/<?php echo $_SESSION[DB_NAME . '_user_id']; ?>">Meus Certificados</a>
                </div>
            </li>  
            
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Sair</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Registre-se</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Entrar</a>
            </li>           
          <?php endif; ?>         
        </ul>

      </div>
  </div>
</nav>