<?php
// (header.php) abriu o <main class="container-principal">
// Agora nós o fechamos:
?>
</main> 

<style>
    .site-footer {
        background-color: #f4f4f4; /* Cor de fundo leve */
        border-top: 1px solid #ddd;
        padding: 20px;
        text-align: center;
        color: #777;
        font-size: 0.9em;
        width: 100%;
        flex-shrink: 0; /* Impede que o rodapé encolha */
        box-sizing: border-box; /* Garante que o padding não estoure a largura */
    }

    /* 2. Esconde o rodapé ao imprimir */
    @media print {
        .site-footer {
            display: none !important;
        }
    }
</style>

<?php
// 3. Lógica PHP: Só exibe o rodapé se estiver logado (mesma regra do header)
if (isset($_SESSION['user_id']) && isset($_SESSION['tenant_id'])): 
?>

    <footer class="site-footer">
        &copy; <?php echo date('Y'); ?> - Sistema de Gestão de Escala.
        <br>
        <small>Acessando como: <?php echo htmlspecialchars($_SESSION['user_nome']); ?> (<?php echo htmlspecialchars($_SESSION['tenant_nome']); ?>)</small>
    </footer>

<?php 
endif; // Fim do "if" que verifica se está logado
// --- FIM DA CORREÇÃO DO RODAPÉ ---
?>

</body>
</html>