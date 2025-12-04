<?php
// Obtém o nome da página atual (ex: dashboard.php)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- MENU INFERIOR RESPONSIVO -->
<div class="bottom-nav">

  <!-- Link: Dashboard -->
  <!-- Adiciona a classe 'active' se a página atual for dashboard.php -->
  <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
    <i class="ri-dashboard-line"></i> <!-- Ícone do menu -->
    <span>Início</span> <!-- Texto exibido -->
  </a>

  <!-- Link: Avisos -->
  <!-- Marca ativo se estiver em avisos.php -->
  <a href="avisos.php" class="<?php echo $current_page == 'avisos.php' ? 'active' : ''; ?>">
    <i class="ri-notification-3-line"></i>
    <span>Avisos</span>
  </a>

  <!-- Link: Rotas -->
  <!-- Marca ativo se estiver em rotas.php -->
  <a href="rotas.php" class="<?php echo $current_page == 'rotas.php' ? 'active' : ''; ?>">
    <i class="ri-map-pin-line"></i>
    <span>Rotas</span>
  </a>

  <!-- Link: Funcionários -->
  <!-- Marca ativo se estiver em funcionarios.php -->
  <a href="funcionarios.php" class="<?php echo $current_page == 'funcionarios.php' ? 'active' : ''; ?>">
    <i class="ri-group-line"></i>
    <span>Funcionários</span>
  </a>

  <!-- Link: Perfil -->
  <!-- Marca ativo se estiver em meu_perfil.php -->
  <a href="meu_perfil.php" class="<?php echo $current_page == 'meu_perfil.php' ? 'active' : ''; ?>">
    <i class="ri-user-settings-line"></i>
    <span>Perfil</span>
  </a>

</div>
