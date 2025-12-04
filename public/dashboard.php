<?php
// Importa o sistema de autenticação (verifica se o usuário está logado)
require_once('../assets/config/auth.php');

// Importa a conexão com o banco de dados
require_once('../assets/config/db.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Título da aba -->
  <title>Dashboard - PagTrem</title>

  <!-- Ícones Remix -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

  <!-- Arquivo de estilos -->
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <div class="layout-wrapper">

    <!-- Inclui o menu lateral do administrador -->
    <?php include '_partials/sidebar_admin.php'; ?>

    <div class="main-content">

      <!-- HEADER do Dashboard -->
      <div class="top-header">
        <h1><i class="ri-dashboard-line"></i> Dashboard</h1>
      </div>

      <div class="container">

        <?php
        // Conta o total de rotas ativas no banco
        $routesAtivas = $mysqli->query("SELECT COUNT(*) AS total FROM routes WHERE status='ativa'")
                              ->fetch_assoc()['total'];

        // Conta o total de avisos cadastrados
        $notices_count = $mysqli->query("SELECT COUNT(*) AS total FROM notices")
                                ->fetch_assoc()['total'];

        // Conta o total de funcionários
        $employees = $mysqli->query("SELECT COUNT(*) AS total FROM employees")
                            ->fetch_assoc()['total'];
        ?>

        <!-- CARDS DE ESTATÍSTICAS -->
        <div class="stats-grid">

          <!-- Rotas Ativas -->
          <div class="stat-card">
            <i class="ri-route-line"></i>
            <div class="stat-value"><?php echo $routesAtivas; ?></div>
            <div class="stat-label">Rotas Ativas</div>
          </div>

          <!-- Funcionários cadastrados -->
          <div class="stat-card">
            <i class="ri-group-line"></i>
            <div class="stat-value"><?php echo $employees; ?></div>
            <div class="stat-label">Funcionários</div>
          </div>

          <!-- Avisos -->
          <div class="stat-card">
            <i class="ri-notification-3-line"></i>
            <div class="stat-value"><?php echo $notices_count; ?></div>
            <div class="stat-label">Avisos</div>
          </div>

        </div>


        <!-- SEÇÃO DE ATIVIDADES RECENTES -->
        <div class="recent-section">
          <h2>Atividades Recentes</h2>

          <!-- Item 1 -->
          <div class="recent-item">
            <i class="ri-train-line" style="font-size:24px; color:var(--brand);"></i>
            <div>
              <span style="font-weight:600; display:block;">Trem #4321</span>
              <span class="text-muted" style="font-size:0.9rem;">Partiu para Curitiba às 09:10</span>
            </div>
          </div>

          <!-- Item 2 -->
          <div class="recent-item">
            <i class="ri-user-add-line" style="font-size:24px; color:var(--success);"></i>
            <div>
              <span style="font-weight:600; display:block;">Novo funcionário</span>
              <span class="text-muted" style="font-size:0.9rem;">Cadastrado em Operações às 09:00</span>
            </div>
          </div>

          <!-- Item 3 -->
          <div class="recent-item">
            <i class="ri-notification-3-line" style="font-size:24px; color:var(--warning);"></i>
            <div>
              <span style="font-weight:600; display:block;">Câmera #7</span>
              <span class="text-muted" style="font-size:0.9rem;">Voltou ao status Online às 08:52</span>
            </div>
          </div>

          <!-- Item 4 -->
          <div class="recent-item">
            <i class="ri-tools-line" style="font-size:24px; color:var(--danger);"></i>
            <div>
              <span style="font-weight:600; display:block;">Manutenção Agendada</span>
              <span class="text-muted" style="font-size:0.9rem;">Rota SP → Campinas às 08:47</span>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

</body>
</html>
