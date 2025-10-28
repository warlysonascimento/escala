<?php 
// $data['tenants'] é injetado pelo controller
// Assumindo um header e footer
?>

<style>
    /* ... (pode usar o mesmo CSS do login) ... */
    .tenant-container { width: 400px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .tenant-btn { display: block; width: 100%; padding: 15px; margin-bottom: 10px; font-size: 16px; text-align: left; cursor: pointer; }
</style>

<div class="tenant-container">
    <h2>Olá, <?php echo $_SESSION['base_user_nome']; ?>!</h2>
    <p>Você tem acesso a múltiplas organizações. Por favor, selecione qual deseja acessar:</p>

    <form action="<?php echo BASE_URL; ?>login/definirTenant" method="POST">
        <?php foreach ($data['tenants'] as $tenant): ?>
            <button type="submit" name="tenant_id" value="<?php echo $tenant['tenant_id']; ?>" class="tenant-btn">
                <strong><?php echo htmlspecialchars($tenant['nome_grupo']); ?></strong><br>
                <small>(Seu acesso: <?php echo htmlspecialchars($tenant['role']); ?>)</small>
            </button>
        <?php endforeach; ?>
    </form>
</div>