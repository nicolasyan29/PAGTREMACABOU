<?php
// Importa a conexão com o banco de dados
require_once('../assets/config/db.php');

// Inicia a sessão para armazenar dados do usuário logado
session_start();

// Variável que receberá possíveis mensagens de erro
$error = '';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Recebe o email e remove espaços extras
  $email = trim($_POST['email'] ?? '');

  // Recebe a senha digitada
  $pass = $_POST['password'] ?? '';

  // Verifica se ambos os campos foram preenchidos
  if ($email && $pass) {

    // Prepara a consulta para buscar o usuário pelo e-mail
    $stmt = $mysqli->prepare("SELECT id,name,email,password,role,avatar FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();

    // Obtém o resultado da consulta
    $res = $stmt->get_result();

    // Verifica se encontrou um usuário
    if ($row = $res->fetch_assoc()) {

      // Verifica se a senha está correta e se o usuário é administrador
      if (password_verify($pass, $row['password']) && $row['role'] === 'admin') {

        // Guarda os dados do usuário na sessão
        $_SESSION['user'] = $row;

        // Redireciona para o painel administrativo
        header('Location: dashboard.php');
        exit;
      } else {

        // Se a senha está certa mas não é admin → bloqueia acesso
        $error = 'Acesso negado. Esta conta não é de administrador.';
      }

    } else {
      // Nenhum usuário encontrado com esse email
      $error = 'Usuário não encontrado.';
    }

  } else {
    // Caso o formulário não esteja completo
    $error = 'Preencha todos os campos.';
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Administrativo - PagTrem</title>

  <!-- CSS principal -->
  <link href="../assets/css/styles.css" rel="stylesheet">

  <!-- Ícones Remix -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

  <div class="auth-card">

    <!-- Ícone representando administrador -->
    <div class="brand-icon">
      <i class="ri-shield-user-line" style="font-size: 40px;"></i>
    </div>

    <h2>Área Administrativa</h2>
    <p class="text-muted" style="margin-bottom: 24px;">Acesso restrito</p>

    <!-- Exibe mensagens de erro, caso existam -->
    <?php if ($error): ?>
      <div class="badge red" style="margin-bottom: 16px; width: 100%; justify-content: center; padding: 8px;">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <!-- Formulário de login -->
    <form method="post">

      <label>Email</label>
      <input class="input" type="email" name="email" placeholder="admin@pagtrem.com" required>

      <label>Senha</label>
      <input class="input" type="password" name="password" placeholder="Senha" required>

      <!-- Botão de enviar -->
      <button class="btn" type="submit" style="width: 100%; margin-top: 24px;">Entrar</button>

    </form>

  </div>

</body>

</html>
