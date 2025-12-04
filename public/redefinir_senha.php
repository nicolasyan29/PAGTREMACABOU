<?php
// Importa a conexão com o banco de dados
equire_once('../assets/config/db.php');
// Inicia a sessão\session_start();


// Variáveis para mensagens de retorno\$error = '';\$success = '';


// Captura o e-mail enviado via GET para validar a redefinição de senha\$email = \$_GET['email'] ?? '';


// Se não houver e-mail válido, redireciona de volta para a página 'esqueci_senha'
if (!\$email) {
header('Location: esqueci_senha.php');
exit;
}


// Verifica se o formulário foi enviado via método POST
if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
\$pass = \$_POST['password'] ?? ''; // Nova senha digitada
\$confirm = \$_POST['confirm_password'] ?? ''; // Confirmação da nova senha


// Verifica se ambos os campos foram preenchidos
if (\$pass && \$confirm) {
// Verifica se as senhas são iguais
if (\$pass === \$confirm) {


// Gera hash seguro da nova senha
\$hash = password_hash(\$pass, PASSWORD_DEFAULT);


// Prepara o comando SQL para atualizar a senha do usuário
\$stmt = \$mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
\$stmt->bind_param('ss', \$hash, \$email);


// Executa a atualização
if (\$stmt->execute()) {
\$success = 'Senha redefinida com sucesso! Redirecionando...';


// Redireciona automaticamente para o login após 2 segundos
echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
} else {
\$error = 'Erro ao atualizar a senha.';
}
} else {
// Mensagem caso as senhas não sejam iguais
\$error = 'As senhas não coincidem.';
}
} else {
// Mensagem caso algum campo esteja vazio
\$error = 'Preencha todos os campos.';
}
}
?>
<!DOCTYPE html>
<html lang="pt-BR">


<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Redefinir Senha - PagTrem</title>


<!-- Estilos principais do sistema -->
<link href="../assets/css/styles.css" rel="stylesheet">


<!-- Ícones Remix -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
</head>


<body>


<div class="auth-card">


<!-- Ícone superior da tela -->
<div class="brand-icon">
<i class="ri-key-2-line" style="font-size: 40px;"></i>
</div>


<!-- Título e e-mail de confirmação -->
<h2>Nova Senha</h2>
<p class="text-muted" style="margin-bottom: 24px;">Defina sua nova senha para
<strong><?php echo htmlspecialchars(\$email); ?></strong></p>


<!-- Exibe mensagem de erro -->
<?php if (\$error): ?>
<div class="badge red" style="margin-bottom: 16px; width: 100%; justify-content: center; padding: 8px;">
<?php echo htmlspecialchars(\$error); ?></div>
<?php endif; ?>


<!-- Exibe mensagem de sucesso -->
<?php if (\$success): ?>
<div class="badge success" style="margin-bottom: 16px; width: 100%; justify-content: center; padding: 8px;">
<?php echo htmlspecialchars(\$success); ?></div>
<?php endif; ?>


<!-- Formulário de redefinição de senha -->
<form method="post">
<label>Nova Senha</label>
<input class="input" type="password" name="password" placeholder="Nova senha" required>


<label>Confirmar Senha</label>
<input class="input" type="password" name="confirm_password" placeholder="Confirme a senha" required>


<!-- Botão principal -->
<button class="btn" type="submit" style="width: 100%; margin-top: 24px;">Redefinir Senha</button>
</form>


<!-- Link para cancelar e voltar ao login -->
<div style="margin-top: 24px; text-align: center;">
<a href="login.php"
style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-light); font-size: 0.875rem;">
<i class="ri-close-circle-line"></i> Cancelar
</a>
</div>
</div>


</body>


</html>