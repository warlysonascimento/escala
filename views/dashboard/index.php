<?php
// Variáveis vêm do HomeController
$totalMilitaresAtivos = $data['totalMilitaresAtivos'] ?? 0;
$mesAtual = $data['mesAtual'] ?? date('Y-m');
$userNome = $data['userNome'] ?? 'Usuário';
$unidadeNome = $data['unidadeNome'] ?? 'Unidade';
$userRole = $_SESSION['user_role'] ?? 'leitor'; // Pega a role para mostrar/esconder links
?>

<style>
    /* Estilos específicos do Dashboard */
    .dashboard-header {
        background-color: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
    }
    .dashboard-header h2 {
        margin: 0 0 10px 0;
        color: #004a91;
        border: none;
    }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Colunas responsivas */
        gap: 25px; /* Espaçamento entre os cards */
    }
    .dashboard-card {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-decoration: none;
        color: #333;
        transition: box-shadow 0.3s ease, transform 0.2s ease;
        display: block; /* Faz o card ser um link clicável inteiro */
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 74, 145, 0.1);
    }
    .dashboard-card h3 {
        margin-top: 0;
        margin-bottom: 10px;
        color: #0056b3;
        font-size: 1.1em;
    }
    .dashboard-card p {
        margin-bottom: 0;
        font-size: 0.9em;
        color: #555;
    }
    .info-box {
        background-color: #e9ecef;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        font-size: 1.1em;
    }
    .info-box strong {
        color: #004a91;
    }
</style>

<div class="dashboard-header">
    <h2>Dashboard</h2>
    <p>Bem-vindo(a), <strong><?php echo htmlspecialchars($userNome); ?></strong>!</p>
    <p>Você está acessando a unidade: <strong><?php echo htmlspecialchars($unidadeNome); ?></strong>.</p>
</div>

<div class="info-box">
    Total de Militares Ativos: <strong><?php echo $totalMilitaresAtivos; ?></strong>
</div>

<div class="dashboard-grid">
    
    <!-- Links Comuns -->
    <a href="<?php echo BASE_URL; ?>escala?mes=<?php echo $mesAtual; ?>" class="dashboard-card">
        <h3>📅 Ver/Editar Escala</h3>
        <p>Acesse a escala do mês atual (<?php echo date('m/Y', strtotime($mesAtual.'-01')); ?>).</p>
    </a>
    
    <a href="<?php echo BASE_URL; ?>relatorios" class="dashboard-card">
        <h3>📊 Gerar Relatórios</h3>
        <p>Visualize ou imprima os relatórios de escala e horas.</p>
    </a>
    
    <a href="<?php echo BASE_URL; ?>lancamentos" class="dashboard-card">
        <h3>✏️ Lançar Ajustes</h3>
        <p>Registre horas extras, saídas ou outros ajustes no ponto.</p>
    </a>

    <!-- Links de Gestão (Gestor ou Admin) -->
    <?php if (in_array($userRole, ['admin', 'gestor'])): ?>
        <a href="<?php echo BASE_URL; ?>militares" class="dashboard-card">
            <h3>👥 Gerenciar Militares</h3>
            <p>Cadastre, edite ou remova militares da sua unidade.</p>
        </a>
        
        <a href="<?php echo BASE_URL; ?>turnos" class="dashboard-card">
            <h3>⏰ Gerenciar Turnos</h3>
            <p>Defina os códigos e durações dos tipos de turno.</p>
        </a>
    <?php endif; ?>

    <!-- Links de Administração (Somente Admin) -->
    <?php if ($userRole == 'admin'): ?>
         <a href="<?php echo BASE_URL; ?>unidades" class="dashboard-card">
            <h3>🏢 Gerenciar Unidades</h3>
            <p>Cadastre ou edite as unidades do sistema.</p>
        </a>
        
        <a href="<?php echo BASE_URL; ?>users" class="dashboard-card">
            <h3>👤 Gerenciar Usuários</h3>
            <p>Crie, edite ou remova usuários e defina suas permissões.</p>
        </a>
    <?php endif; ?>

</div>
