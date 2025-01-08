<?php
  session_start();
  if (empty($_SESSION)){
      print("<script>location.href='../../index.html'</script>");
  }
?>
<?php
$relative = isset($_POST['relative']) ? $_POST['relative'] : '';

  echo '<nav class="navbar navbar-expand-md bg-body-tertiary"">
  <div class="container-fluid" style="margin-top: -9px;">
    <a class="navbar-brand" href="'.$relative.'home.php">3S</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mt-0" id="navbarNav">
      <ul class="navbar-nav" style="width: 100%;">';
  
  if($_SESSION["tipo"] == 1){
    echo '<li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" style="padding-bottom: 13px;" id="notas-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Notas</a>
      <div class="dropdown-menu" style="background-color: white; padding: 5px 0; margin-top: -7px;">
        <a class="dropdown-item" id="notas-nmonit-tab" href="'.$relative.'3ESSI_NOTAS_MONITORAMENTO/view_get_notas.php">Buscar</a>
        <a class="dropdown-item" id="notas-tab" href="'.$relative.'3SSI_CRUD/view_set_notas.php">Inserir</a>
        <a class="dropdown-item" id="notas-tab" href="'.$relative.'3ESSI_NOTAS_MONITORAMENTO/view_get_notas_2.php">Roterizador</a>
        <a class="dropdown-item" id="notas-tab" href="'.$relative.'3ESSI_NOTAS_MONITORAMENTO/view_altera_clientes.php">Alterar Clientes</a>

      </div>
    </li>';
    echo '<li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" style="padding-bottom: 13px;" id="notas-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cadastro</a>
      <div class="dropdown-menu" style="background-color: white; padding: 5px 0; margin-top: -7px;">
        <a class="dropdown-item" id="motoristas-tab" href="'.$relative.'3ESSI_MOTORIST_CAMINHOES/view_set_motorista.php" role="tab" aria-controls="Inserir" aria-selected="false">Cadastrar Motorista/ Caminhão</a>
        <a class="dropdown-item" id="motoristas-view-tab" href="'.$relative.'3ESSI_MOTORIST_CAMINHOES/view_get_motorista.php">Visualizar Motorista/ Caminhão</a>
      </div>
    </li>';
    echo '<li class="nav-item">
    <a class="nav-link" href="'.$relative.'3ESSI_VIAGENS_ABERTA/view_viagens_abertas.php">Viagens Abertas</a>
    </li>';


    echo '<li class="nav-item dropdown">
      <a class="nav-link" href="'.$relative.'3ESSI_VIAGENS_FECHADAS/view_viagens_fechadas.php">Viagens Fechadas</a>
      </li>';


    echo '<li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" style="padding-bottom: 13px;" id="notas-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Devoluções</a>
      <div class="dropdown-menu" style="background-color: white; padding: 5px 0; margin-top: -7px;">
        <a class="dropdown-item" id="devolucoes-tab" href="'.$relative.'3ESSI_DEVOLUCOES/view_devolucoes.php" role="tab" aria-controls="Inserir" aria-selected="false">Devoluções em Estoque</a>
        <a class="dropdown-item" id="motoristas-view-tab" href="'.$relative.'3ESSI_DEVOLUCOES/view_devolucoes_enviadas.php">Devoluções Enviadas</a>
        <a class="dropdown-item" id="motoristas-view-tab" href="'.$relative.'3ESSI_DEVOLUCOES/view_relatorios_retorno.php">Relatorios Retorno</a>

      </div>
    </li>';
 
    echo '<li class="nav-item dropdown">
    <a class="nav-link" href="'.$relative.'3ESSI_NOTAS_ARMAZEM/view_notas_armazem.php">Notas Armazém</a>
    </li>';
 

  } else{
    echo 
    '<li class="nav-item">'.
      '<a class="nav-link" id="notas-nmonit-tab" data-toggle="tab" href="'.$relative.'3ESSI_NOTAS_MONITORAMENTO/view_get_notas.php" role="tab" aria-controls="home" aria-selected="false" style="height: 100%">Buscar Notas</a>
    </li>';
    echo 
    '<li class="nav-item">'.
      '<a class="nav-link" id="motoristas-view-tab" data-toggle="tab" href="'.$relative.'3ESSI_MOTORIST_CAMINHOES/view_get_motorista.php" role="tab" aria-controls="home" aria-selected="false" style="height: 100%">Visualizar Motoristas</a>
    </li>';
  }
  
  echo '<li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" style="padding-bottom: 13px;" id="notas-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mapa de Carregamento</a>
      <div class="dropdown-menu" style="background-color: white; padding: 5px 0; margin-top: -7px;">
        <a class="dropdown-item" id="mapa-tab-tab" href="'.$relative.'3ESSI_MAPA_CARREGAMENTO/view_get_products.php" role="tab" aria-controls="Mapa de Carregamentos" aria-selected="false" style="height: 100%">Mapa de Carregamento</a>
        <a class="dropdown-item" id="mapa-tab-tab" href="'.$relative.'3ESSI_MAPA_CARREGAMENTO/view_get_products_2.php" role="tab" aria-controls="Mapa de Carregamentos" aria-selected="false" style="height: 100%">Mapa de Carregamento 2</a>
    </li>';
  echo
    '<li class="nav-item">
      <a class="nav-link" id="mapa-tab" data-toggle="tab" href="'.$relative.'3ESSI_MAPA_CARREGAMENTO/view_get_products.php" role="tab" aria-controls="Mapa de Carregamentos" aria-selected="false" style="height: 100%">Mapa de Carregamento</a>
    </li>';
    
  if($_SESSION["tipo"] == 1){
    echo 
    '<li class="nav-item">'.
      '<a class="nav-link" id="perfis-tab" data-toggle="tab" href="'.$relative.'view_get_perfis.php" role="tab" aria-controls="home" aria-selected="false" style="height: 100%">Perfis cadastros</a>
    </li>';
  }
    
  echo '<li class="nav-item ms-auto">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="'.$relative.'view_perfil_configs.php" role="tab" aria-controls="Clientes" aria-selected="false" style="height: 100%">
          <img src="https://cdn-icons-png.flaticon.com/256/6596/6596121.png" alt="" width="28" height="28" class="rounded-circle me-2">';
    if($_SESSION['tipo'] == 1){
      echo '<strong style="color: red;">'.$_SESSION["usuario"].'
          </strong>';
    }
    else{
      echo $_SESSION["usuario"];
    }
    echo
        '</a>
      </li>';

  echo '</ul>
    </div>
  </div>
</nav>';
?>
