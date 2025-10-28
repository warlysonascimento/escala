<?php 
// Incluir seu cabeçalho (header)
// Ex: require_once APPROOT . '/views/partials/header.php'; 
?>

<div style="padding: 20px;">
    <h1>Página Inicial (Home)</h1>
    <p>Bem-vindo ao Sistema de Escala!</p>
        
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Você está logado como <strong><?php echo htmlspecialchars($_SESSION['user_nome']); ?></strong>.</p>
        <p>Acessando a unidade: <strong><?php echo htmlspecialchars($_SESSION['tenant_nome']); ?></strong>.</p>
        <a href="<?php echo BASE_URL; ?>dashboard">Ir para o Dashboard</a>
    <?php else: ?>
        <p>Você não está logado.</p>
        <a href="<?php echo BASE_URL; ?>login">Ir para a página de Login</a>
    <?php endif; ?>
</div>

<?php 
// Incluir seu rodapé (footer)
// Ex: require_once APPROOT . '/views/partials/footer.php'; 
?>