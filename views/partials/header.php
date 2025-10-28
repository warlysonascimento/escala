<?php
// Garante que a sessão foi iniciada (geralmente feito no index.php principal)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define BASE_URL se ainda não foi definida (apenas para segurança)
// Você deve definir isso no seu arquivo de configuração principal (ex: config.php)
if (!defined('BASE_URL')) {
    // Substitua pelo caminho real do seu projeto
    define('BASE_URL', '/'); 
}

// --- CORREÇÃO DE CSS ---
// Adiciona o link para o style.css global que estava faltando
// (Assumindo que seu style.css está em public/css/style.css)
echo '<link rel="stylesheet" href="' . BASE_URL . 'public/css/style.css">';
// --- FIM DA CORREÇÃO DE CSS ---

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Escala</title>
    
    <style>
        .menu-principal {
            background-color: #004a91; /* Cor escura para o menu */
            color: white;
            padding: 0 20px;
            position: fixed; /* Fixa o menu no topo */
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
        }
        .menu-container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .menu-logo {
            font-size: 1.2em;
            font-weight: bold;
        }
        .menu-principal ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        .menu-principal ul li {
            position: relative;
        }
        .menu-principal ul li a {
            color: white;
            text-decoration: none;
            padding: 20px 15px; /* Altura total do menu */
            display: block;
        }
        .menu-principal ul li a:hover {
            background-color: #003a71; /* Cor mais escura no hover */
        }
        /* Estilos do Dropdown (Gestão) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content li {
            width: 100%;
        }
        .dropdown-content li a {
            color: black; /* Texto preto no dropdown */
            padding: 12px 16px;
        }
        .dropdown-content li a:hover {
            background-color: #ddd;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        /* Menu da direita (Sair) */
        .menu-direita {
            margin-left: auto; /* Empurra para a direita */
        }
    </style>

    <style>
        html {
            height: 100%;
        }
        body {
            min-height: 100%; /* Garante que o body ocupe 100% da altura */
            display: flex;
            flex-direction: column; /* Organiza body em (header -> main -> footer) */
            padding-top: 60px; /* Adiciona espaço no topo para o menu fixo */
            margin: 0;
            font-family: Arial, sans-serif;
            box-sizing: border-box; /* Garante que o padding não afete a altura */
        }
        .container-principal {
            flex: 1 0 auto; /* FAZ O CONTEÚDO PRINCIPAL CRESCER e empurrar o rodapé */
            padding: 20px; /* Adiciona um respiro interno */
            width: 100%;
            box-sizing: border-box;
        }
    </style>

    <style>
        @media print {
            /* Esconde o menu de navegação principal durante a impressão */
            .menu-principal {
                display: none !important;
            }
            /* Remove o padding do topo no modo de impressão */
            body {
                padding-top: 0 !important;
                min-height: 0 !important; /* Reseta o min-height na impressão */
            }
            .container-principal {
                flex: none !important; /* Reseta o flex na impressão */
                padding: 0 !important;
            }
        }
    </style>

</head>
<body>

<?php 
// --- 4. LÓGICA DE LOGIN ---
// Verificamos se o usuário está logado E se já selecionou um "tenant"
// Somente se ambas as condições forem verdadeiras, o menu será exibido.
if (isset($_SESSION['user_id']) && isset($_SESSION['tenant_id'])): 
?>

    <nav class="menu-principal">
        <div class="menu-container">
            <span class="menu-logo">
                <?php echo htmlspecialchars($_SESSION['tenant_nome']); // Mostra o nome do "tenant" ?>
            </span>

            <ul>
                <li><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                <li><a href="<?php echo BASE_URL; ?>escala">Escala</a></li>
                <li><a href="<?php echo BASE_URL; ?>relatorios">Relatórios</a></li>
                
                <!-- === ITEM ADICIONADO AQUI === -->
                <li><a href="<?php echo BASE_URL; ?>lancamentos">Lançamentos</a></li> 
                <!-- === FIM DA ADIÇÃO === -->
                
                <?php 
                // Mostrar links de Gestão apenas para 'admin' ou 'gestor'
                if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'gestor'])): 
                ?>
                    <li class="dropdown">
                        <a href="#">Gestão</a>
                        <ul class="dropdown-content">
                            <li><a href="<?php echo BASE_URL; ?>militares">Militares</a></li>
                            <li><a href="<?php echo BASE_URL; ?>turnos">Turnos</a></li>
                            
                            <?php if ($_SESSION['user_role'] == 'admin'): // Somente admin pode ver isso ?>
                                <li style="border-top: 1px solid #eee;"></li>
                                <li><a href="<?php echo BASE_URL; ?>unidades">Unidades</a></li>
                                <li><a href="<?php echo BASE_URL; ?>users">Usuários</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="menu-direita">
                <li>
                    <a href="<?php echo BASE_URL; ?>login/logout">
                        Sair (<?php echo htmlspecialchars($_SESSION['user_nome']); ?>)
                    </a>
                </li>
            </ul>
        </div>
    </nav>

<?php 
endif; // Fim do "if" que verifica se está logado
?>

<main class="container-principal">

