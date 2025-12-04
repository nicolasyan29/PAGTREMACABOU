<?php
// Inicia a sessão para poder manipular os dados dela
session_start();

// Destrói todas as variáveis armazenadas na sessão (logout)
session_destroy();

// Redireciona o usuário de volta para a tela de login
header('Location: login.php');
exit; // Boa prática: garante que o código pare aqui
?>
