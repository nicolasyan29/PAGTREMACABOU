<?php
// Inicia carregando a conexão com o banco de dados
require_once('../assets/config/db.php');
// Inicia a sessão para armazenar mensagens e dados temporários
session_start();

// Mensagens de feedback
$msg = ''; // Mensagem de sucesso
$err = ''; // Mensagem de erro

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? ''); // Captura e remove espaços do nome
  $email = trim($_POST['email'] ?? ''); // Captura o e-mail
  $password = $_POST['password'] ?? ''; // Captura a senha

  // Verifica se todos os campos obrigatórios foram preenchidos
  if ($name && $email && $password) {

    // Prepara consulta para verificar se o e-mail já está cadastrado
    $check = $mysqli->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->bind_param('s', $email); // Vincula o e-mail na consulta
    $check->execute(); // Executa a consulta
    $check->store_result(); // Armazena o resultado

    // Se encontrou um usuário com o mesmo e-mail
    if ($check->num_rows > 0) {
      $err = 'Este e-mail já está cadastrado. Faça login ou use outro.';
    } else {
      // Gera o hash seguro da senha
      $hash = password_hash($password, PASSWORD_DEFAULT);

      // Insere novo usuário no banco de dados
      $stmt = $mysqli->prepare("INSERT INTO users(name, email, password, role) VALUES (?, ?, ?, 'user')");
      $stmt->bind_param('sss', $name, $email, $hash); // Envia dados para a query

      // Executa o INSERT
      if ($stmt->execute()) {
        $msg = 'Cadastro realizado com sucesso! Faça login.';
      } else {
        $err = 'Erro ao cadastrar: ' . $mysqli->error; // Mostra erro do MySQL
      }

      $stmt->close(); // Fecha o statement de inserção
    }

    $check->close(); // Fecha o statement de verificação

  } else {
    // Caso algum campo esteja vazio
    $err = 'Preencha todos os campos.';
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrar - PagTrem</title>

  <!-- CSS principal da aplicação -->
  <link href="../assets/css/styles.css" rel="stylesheet">
  <!-- Ícones Remix -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

  <!-- JavaScript da aplicação -->
  <script src="../assets/js/app.js" defer></script>
</head>

<body>

  <div class="auth-card">

    <!-- Ícone do topo -->
    <div class="brand-icon">
      <i class="ri-user-add-line" style="font-size: 40px;"></i>
    </div>

    <h2>Crie sua conta</h2>
    <p class="text-muted" style="margin-bottom: 24px;">Pronto para viajar com a gente?</p>

    <!-- Exibição da mensagem de sucesso -->
    <?php if ($msg): ?>
      <div class="badge success" style="margin-bottom: 16px; width: 100%; justify-content: center; padding: 8px;">
        <?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <!-- Exibição da mensagem de erro -->
    <?php if ($err): ?>
      <div class="badge red" style="margin-bottom: 16px; width: 100%; justify-content: center; padding: 8px;">
        <?php echo htmlspecialchars($err); ?></div>
    <?php endif; ?>

    <!-- Formulário de registro -->
    <form method="post">

      <label>Nome completo</label>
      <input class="input" name="name" placeholder="Seu nome" required>

      <label>E-mail</label>
      <input class="input" type="email" name="email" placeholder="email@exemplo.com" required>

      <label>Senha</label>
      <input class="input" type="password" name="password" placeholder="Digite uma senha" required>

      <!-- Campos de endereço -->
      <label>Endereço</label>
      <div class="row-cep" style="display: flex; gap: 12px; margin-bottom: 12px;">
        <input class="input" id="cep" placeholder="CEP" style="flex: 1;">
        <input class="input" id="city" placeholder="Cidade" style="flex: 2;">
        <input class="input" id="uf" placeholder="UF" style="width: 60px;">
      </div>

      <!-- Botão para buscar CEP automaticamente -->
      <button type="button" class="btn secondary" onclick="buscarCEP('cep',{city:'city',state:'uf'})"
        style="width: 100%; margin-bottom: 24px;">
        Buscar CEP
      </button>

      <!-- Botão de envio do formulário -->
      <button type="submit" class="btn" style="width: 100%">Registrar</button>

      <!-- Link para login -->
      <div style="margin-top: 24px; text-align: center; font-size: 0.875rem;">
        Já tem conta? <a href="login.php">Entrar</a>
      </div>
    </form>

  </div>

</body>

</html>
