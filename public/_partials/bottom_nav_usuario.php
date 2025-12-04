<?php
// Captura o nome do arquivo PHP atual que está sendo executado.
// Exemplo: se a página for "perfil_usuario.php", então $current_page recebe esse nome.
$current_page = basename($_SERVER['PHP_SELF']);
?>
 
<!-- MENU INFERIOR (versão para o usuário comum) -->
<div class="bottom-nav">

  <!-- Item: Rotas -->
  <!-- Se o usuário estiver na página usuario_home.php, o item recebe a classe 'active' -->
  <a href="usuario_home.php" class="<?php echo $current_page == 'usuario_home.php' ? 'active' : ''; ?>">
    <i class="ri-map-pin-line"></i> <!-- Ícone representando mapas/rotas -->
    <span>Rotas</span> <!-- Texto mostrado abaixo do ícone -->
  </a>

  <!-- Item: Perfil do Usuário -->
  <!-- Ativo quando a página atual for perfil_usuario.php -->
  <a href="perfil_usuario.php" class="<?php echo $current_page == 'perfil_usuario.php' ? 'active' : ''; ?>">
    <i class="ri-user-smile-line"></i> <!-- Ícone de perfil -->
    <span>Perfil</span>
  </a>

  <!-- Item: Logout -->
  <!-- Este item não possui estado ativo, pois serve apenas para sair da conta -->
  <a href="logout.php">
    <i class="ri-logout-box-r-line"></i> <!-- Ícone de sair -->
    <span>Sair</span>
  </a>

</div>
