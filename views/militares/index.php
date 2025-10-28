<?php 
// Incluir seu header (com CSS, menu, etc.)
// require_once APPROOT . '/views/partials/header.php'; 
?>

<style>
    .container { width: 90%; margin: auto; }
    .gestao-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
    .card { padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f4f4f4; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; }
    .form-group input { width: 100%; padding: 8px; }
    .btn { padding: 10px 15px; border: none; cursor: pointer; }
    .btn-primary { background-color: #004a91; color: white; }
    .btn-danger { background-color: #d9534f; color: white; }
</style>

<div class="container">
    
    <h2>Gestão de Militares</h2>
    <h3>Organização: <?php echo htmlspecialchars($data['tenant_nome']); ?></h3>
    <hr>

    <div class="gestao-grid">
        
        <div class="card">
            <h4>Cadastrar Novo Militar</h4>
            <form action="<?php echo BASE_URL; ?>militares/store" method="POST">
                <div class="form-group">
                    <label for="nome">Nome de Guerra:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="carga_padrao">Carga Padrão (h):</label>
                    <input type="number" id="carga_padrao" name="carga_padrao" value="160" required>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Militar</button>
            </form>
        </div>

        <div class="card">
            <h4>Militares Cadastrados</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Carga Padrão</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['militares'])): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Nenhum militar cadastrado.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($data['militares'] as $militar): ?>
                        <tr>
                            <td><?php echo $militar['id']; ?></td>
                            <td><?php echo htmlspecialchars($militar['nome']); ?></td>
                            <td><?php echo $militar['carga_horaria_padrao']; ?> h</td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>militares/edit/<?php echo $militar['id']; ?>" class="btn">Editar</a>
                                <a href="<?php echo BASE_URL; ?>militares/delete/<?php echo $militar['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Incluir seu footer
// require_once APPROOT . '/views/partials/footer.php'; 
?>