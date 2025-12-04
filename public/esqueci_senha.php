<?php
// Importa a conexão com o banco de dados
require_once('../assets/config/db.php');

// Inicia a sessão para exibir mensagens e manter dados temporários
session_start();

// Variável que armazenará erros, caso existam
$error = '';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe o e-mail digitado e remove espaços extras
    $email = trim($_POST['email'] ?? '');

    // Verifica se o campo não está vazio
    if ($email) {

        // Prepara consulta para verificar se o e-mail existe no banco
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result(); // Necessário para usar num_rows

        // Se encontrou o e-mail
        if ($stmt->num_rows > 0) {

            // Redireciona para a página de redefinição de senha
            // OBS: Passar dados sensíveis pela URL não é seguro, mas foi pedido assim
            header('Location: redefinir_senha.php?email=' . urlencode($email));
            exit;

        } else {
            // E-mail não existe no sistema
            $error = 'E-mail não encontrado.';
        }

    } else {
        // Usuário enviou o formulário sem digitar o e-mail
        $error = 'Por favor, digite seu e-mail.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Esqueci minha senha - PagTrem</title>

    <!-- Estilos principais -->
    <link href="../assets/css/styles.css" rel="stylesheet">

    <!-- Ícones Remix -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

    <div class="auth-card">

        <!-- Ícone de segurança (senha) -->
        <div class="brand-icon">
            <i class="ri-lock-password-line" style="font-size: 40px;"></i>
        </div>

        <h2>Recuperar Senha</h2>
        <p class="text-muted" style="margin-bottom: 24px;">Digite seu e-mail para continuar</p>

        <!-- Exibe mensagens de erro caso existam -->
        <?php if ($error): ?>
            <div class="badge red" style="margin-bottom: 16px; width: 100%; justify-content: center; padding: 8px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário para inserir o e-mail -->
        <form method="post">

            <label>E-mail</label>
            <input class="input" type="email" name="email" placeholder="seu@email.com" required>

            <!-- Botão para continuar -->
            <button class="btn" type="submit" style="width: 100%; margin-top: 24px;">Continuar</button>
        </form>

        <!-- Link para voltar ao login -->
        <div style="margin-top: 24px; text-align: center;">
            <a href="login.php"
                style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-light); font-size: 0.875rem;">
                <i class="ri-arrow-left-line"></i> Voltar ao login
            </a>
        </div>
    </div>

</body>

</html>
