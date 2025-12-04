<?php
// Importa os arquivos essenciais: autenticação e conexão com o banco de dados
require_once('../assets/config/auth.php');
require_once('../assets/config/db.php');

// ================================
// PROCESSAMENTO DO FORMULÁRIO (POST)
// ================================
// Verifica se o formulário foi enviado através do método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? ''; // Ação que será executada (create, update, delete)
    $id = $_POST['id'] ?? ''; // ID usado para update/delete

    // Novos campos capturados do formulário
    $origin = trim($_POST['origin'] ?? '');        // Local de embarque
    $destination = trim($_POST['destination'] ?? ''); // Destino final do trem

    // Nome da rota é criado automaticamente usando origem + setinha + destino
    $name = "$origin → $destination";

    // Campo de informações extras
    $extra_info = trim($_POST['extra_info'] ?? '');

    // Status da rota: ativa ou manutenção
    $status = $_POST['status'] ?? 'ativa';

    // -------- CRIAR NOVA ROTA --------
    if ($action === 'create') {
        $stmt = $mysqli->prepare("INSERT INTO routes (name, origin, destination, extra_info, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $name, $origin, $destination, $extra_info, $status);
        $stmt->execute();

    // -------- ATUALIZAR ROTA EXISTENTE --------
    } elseif ($action === 'update' && $id) {
        $stmt = $mysqli->prepare("UPDATE routes SET name=?, origin=?, destination=?, extra_info=?, status=? WHERE id=?");
        $stmt->bind_param('sssssi', $name, $origin, $destination, $extra_info, $status, $id);
        $stmt->execute();

    // -------- APAGAR ROTA --------
    } elseif ($action === 'delete' && $id) {
        $stmt = $mysqli->prepare("DELETE FROM routes WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    // Redireciona para evitar reenvio do formulário
    header('Location: rotas.php');
    exit;
}

// ================================
// EXCLUSÃO VIA GET (CLIQUE EM UM LINK)
// ================================
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $mysqli->query("DELETE FROM routes WHERE id=$id");
    header('Location: rotas.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rotas - PagTrem</title>

    <!-- Ícones e CSS -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">

</head>

<body>

    <div class="layout-wrapper">
        <?php
        // Sidebar do administrador
        include '_partials/sidebar_admin.php';
        ?>

        <div class="main-content">

            <!-- TÍTULO DA PÁGINA -->
            <div class="top-header">
                <h1><i class="ri-map-pin-line"></i> Rotas</h1>
            </div>

            <div class="container">

                <!-- LISTAGEM DAS ROTAS CADASTRADAS -->
                <div class="route-list" id="routeList">
                    <?php
                    // Busca todas as rotas cadastradas
                    $res = $mysqli->query("SELECT * FROM routes ORDER BY id DESC");

                    // Loop para exibir cada rota
                    while ($r = $res->fetch_assoc()) {

                        // Define cor e texto da badge dependendo do status
                        $badgeClass = ($r['status'] === 'manutencao') ? 'red' : 'blue';
                        $badgeText = ($r['status'] === 'manutencao') ? 'Manutenção' : 'Ativa';

                        // Prepara dados para serem enviados para o JavaScript
                        $jsonData = htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8');

                        // Renderiza cartão da rota
                        echo "
                        <div class='route-card' onclick='editRoute($jsonData)'>
                            <div class='route-title'>
                                <span>" . htmlspecialchars($r['name']) . "</span>
                                <span class='badge $badgeClass'>$badgeText</span>
                            </div>

                            <div class='live-info'>
                                <i class='ri-notification-3-line' style='font-size:18px; color:var(--text-light);'></i> 
                                <span>" . (!empty($r['extra_info']) ? htmlspecialchars($r['extra_info']) : 'Sem informações adicionais') . "</span>
                            </div>
                        </div>";
                    }
                    ?>
                </div>

            </div>

            <!-- BOTÃO FLUTUANTE PARA ADICIONAR NOVA ROTA -->
            <div class="fab" onclick="openCreateModal()">
                <i class="ri-add-line" style="font-size: 32px;"></i>
            </div>

            <!-- MODAL DE CRIAÇÃO/EDIÇÃO DE ROTAS -->
            <div class="modal-bg" id="modal">
                <div class="modal" onclick="event.stopPropagation()">
                    
                    <!-- Título muda conforme a ação -->
                    <h2 id="modalTitle" style="margin-bottom: 24px;">Nova Rota</h2>

                    <form method="post">

                        <!-- Campos ocultos -->
                        <input type="hidden" name="action" id="formAction" value="create">
                        <input type="hidden" name="id" id="routeId">

                        <!-- Campos de origem e destino -->
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                            <div>
                                <label>Local de Embarque</label>
                                <input class="input" name="origin" id="routeOrigin" placeholder="Ex: São Paulo" required>
                            </div>

                            <div>
                                <label>Destino</label>
                                <input class="input" name="destination" id="routeDestination" placeholder="Ex: Rio de Janeiro" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <label>Status</label>
                        <select class="select" name="status" id="routeStatus">
                            <option value="ativa">Ativa</option>
                            <option value="manutencao">Manutenção</option>
                        </select>

                        <!-- Informações adicionais -->
                        <label>Informações Adicionais</label>
                        <textarea class="textarea" name="extra_info" id="routeExtra" rows="2" placeholder="Ex: Atrasos, previsões..."></textarea>

                        <!-- Botões de ação -->