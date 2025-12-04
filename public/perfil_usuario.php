<?php
// Importa o arquivo de autenticação que verifica login e sessão
require_once('../assets/config/auth.php');

// Obtém os dados do usuário logado armazenados na sessão
$user = $_SESSION['user'] ?? null;

// Impede que administradores acessem esta página destinada apenas a usuários comuns
if ($user['role'] !== 'user') {
  header('Location: dashboard.php'); // Redireciona caso não seja usuário
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meu Perfil - Usuário</title>

  <!-- Importa estilos gerais do sistema -->
  <link href="../assets/css/styles.css" rel="stylesheet">
  <!-- Ícones Remix -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

  <div class="layout-wrapper">
    <?php include '_partials/sidebar_user.php'; // Barra lateral do usuário ?>

    <div class="main-content">
      <div class="top-header">
        <!-- Título da página com ícone -->
        <h1><i class="ri-user-smile-line"></i> Meu Perfil</h1>
      </div>

      <div class="container" style="padding-bottom: 100px;">

        <!-- Card principal com avatar e nome -->
        <div class="card" style="text-align: center; padding: 32px 24px; margin-bottom: 24px;">

          <!-- Ícone arredondado simulando foto de perfil -->
          <div
            style="width: 80px; height: 80px; background: var(--brand-light); color: var(--brand); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 32px;">
            <i class="ri-user-line"></i>
          </div>

          <!-- Exibe o nome do usuário logado -->
          <h2 style="margin-bottom: 4px;"><?php echo htmlspecialchars($user['name']); ?></h2>

          <p class="text-muted">Usuário PagTrem</p>
        </div>

        <!-- Card com informações da conta -->
        <div class="card">
          <h3 style="margin-bottom: 16px;">Informações da Conta</h3>

          <!-- Linha exibindo o nome -->
          <div
            style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border);">
            <span class="text-muted">Nome</span>
            <span style="font-weight: 500;"><?php echo htmlspecialchars($user['name']); ?></span>
          </div>

          <!-- Linha exibindo o e-mail -->
          <div
            style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border);">
            <span class="text-muted">E-mail</span>
            <span style="font-weight: 500;"><?php echo htmlspecialchars($user['email']); ?></span>
          </div>

          <!-- Linha exibindo o tipo de conta -->
          <div style="display: flex; justify-content: space-between; padding: 12px 0;">
            <span class="text-muted">Tipo de Conta</span>
            <span class="badge blue">Usuário</span>
          </div>

          <!-- Botão para editar o perfil -->
          <a href="editar_perfil_usuario.php" class="btn secondary"
            style="width: 100%; margin-top: 24px; justify-content: center;">
            <i class="ri-pencil-line" style="margin-right: 8px;"></i> Editar Perfil
          </a>
        </div>

      </div>
    </div>
  </div>

</body>

</html>