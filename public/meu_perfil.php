<?php
require_once('../assets/config/auth.php'); // Importa o sistema de autenticação
require_once('../assets/config/db.php'); // Importa a conexão com o banco de dados

$feedback = ''; // Mensagem de retorno após salvar

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se o formulário foi enviado

  $name = trim($_POST['name'] ?? ''); // Nome
  $phone = trim($_POST['phone'] ?? ''); // Telefone
  $department = trim($_POST['department'] ?? ''); // Departamento
  $job = trim($_POST['job_title'] ?? ''); // Cargo

  // Avatar atual do usuário
  $avatarPath = $user['avatar'] ?? null;

  // Upload de imagem
  if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) { // Se enviou uma imagem válida
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION)); // Extensão do arquivo
    $fname = 'u' . $user['id'] . '_' . time() . '.' . $ext; // Nome único
    $dest = '../assets/uploads/profile_photos/' . $fname; // Caminho final

    // Garante que o diretório existe
    if (!is_dir(dirname($dest))) {
      mkdir(dirname($dest), 0777, true); // Cria diretório se não existir
    }

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) { // Move o arquivo
      $avatarPath = $fname; // Atualiza o nome salvo
    }
  }

  // Atualiza os dados no banco
  $stmt = $mysqli->prepare("UPDATE users SET name=?, phone=?, department=?, job_title=?, avatar=? WHERE id=?");
  $stmt->bind_param('sssssi', $name, $phone, $department, $job, $avatarPath, $user['id']); // Envia valores

  if ($stmt->execute()) { // Se atualizou com sucesso
    $_SESSION['user']['name'] = $name; // Atualiza sessão
    $_SESSION['user']['avatar'] = $avatarPath; // Atualiza avatar
    $feedback = 'Perfil atualizado com sucesso!'; // Mensagem de sucesso
  }
}

// Busca os dados atualizados do usuário
$res = $mysqli->query("SELECT * FROM users WHERE id=" . $user['id']);
$me = $res->fetch_assoc();

// Mostra avatar atual, se existir
$currentAvatar = $me['avatar'];
if ($currentAvatar && file_exists('../assets/uploads/profile_photos/' . $currentAvatar)) {
  $displayAvatar = '../assets/uploads/profile_photos/' . $currentAvatar; // Caminho da imagem
} else {
  $displayAvatar = ''; // Sem foto
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8"> <!-- Codificação -->
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ajuste mobile -->
  <title>Meu Perfil - PagTrem</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"> <!-- Ícones -->
  <link href="../assets/css/styles.css" rel="stylesheet"> <!-- CSS -->
</head>

<body>

  <div class="layout-wrapper"> <!-- Layout principal -->
    <?php include '_partials/sidebar_admin.php'; ?> <!-- Menu lateral -->

    <div class="main-content"> <!-- Área central -->

      <div class="top-header"> <!-- Cabeçalho -->
        <h1><i class="ri-user-settings-line"></i> Meu Perfil</h1>
      </div>

      <div class="container" style="padding-bottom: 100px;"> <!-- Espaçamento -->

        <?php if ($feedback): ?> <!-- Mensagem de sucesso -->
            <div class="badge success" style="margin-bottom: 24px; width: 100%; justify-content: center; padding: 12px;">
              <?php echo $feedback; ?> <!-- Exibe mensagem -->
            </div>
        <?php endif; ?>

        <div class="card"> <!-- Card de formulário -->
          <form method="post" enctype="multipart/form-data"> <!-- Form com upload -->

            <div style="text-align:center; margin-bottom:24px;"> <!-- Área da foto -->

              <img id="preview" src="<?php echo $displayAvatar; ?>"
                style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:4px solid var(--surface); box-shadow: var(--shadow); display: <?php echo $displayAvatar ? 'block' : 'none'; ?>; margin: 0 auto 16px auto;"> <!-- Mostra foto atual -->
              
              <div id="defaultIcon" 
                style="width:100px; height:100px; background:var(--brand-bg); color:var(--brand); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:40px; border:1px solid var(--brand-light); margin: 0 auto 16px auto; display: <?php echo $displayAvatar ? 'none' : 'flex'; ?>;"> <!-- Ícone padrão -->
                <i class="ri-user-line"></i>
              </div>

              <label for="photoInput" class="btn secondary"
                style="display:inline-block; width:auto; padding:8px 16px; font-size:0.875rem;">Alterar
                Foto</label> <!-- Botão para alterar a foto -->
              <input type="file" name="avatar" id="photoInput" class="file-input" accept="image/*" style="display:none;"
                onchange="handleFileUpload(this)"> <!-- Upload invisível -->
            </div>

            <label>Nome Completo</label>
            <input class="input" name="name" value="<?php echo htmlspecialchars($me['name']); ?>"
              placeholder="Nome completo"> <!-- Nome -->

            <label>E-mail</label>
            <input class="input" type="email" disabled value="<?php echo htmlspecialchars($me['email']); ?>"
              style="background: var(--bg);"> <!-- Email bloqueado -->

            <label>Telefone</label>
            <input class="input" name="phone" value="<?php echo htmlspecialchars($me['phone']); ?>"
              placeholder="Telefone"> <!-- Telefone -->

            <label>Departamento</label>
            <input class="input" name="department" value="<?php echo htmlspecialchars($me['department']); ?>"
              placeholder="Departamento"> <!-- Departamento -->

            <label>Cargo</label>
            <input class="input" name="job_title" value="<?php echo htmlspecialchars($me['job_title']); ?>"
              placeholder="Cargo"> <!-- Cargo -->

            <button class="btn" style="width: 100%; margin-top: 24px;">Salvar Alterações</button> <!-- Botão salvar -->

          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    const preview = document.getElementById("preview"); // Imagem de preview
    const defaultIcon = document.getElementById("defaultIcon"); // Ícone padrão
    const photoInput = document.getElementById("photoInput"); // Input de arquivo

    function handleFileUpload(input) { // Função para pré-visualização
      if (input.files && input.files[0]) { // Se escolheu arquivo
        preview.src = window.URL.createObjectURL(input.files[0]); // Mostra preview
        preview.style.display = "block"; // Exibe imagem
        defaultIcon.style.display = "none"; // Esconde ícone
      }
    }
  </script>

</body>

</html>