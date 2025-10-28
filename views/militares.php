<?php
$militares = $data['militares'] ?? $militares_cadastrados ?? [];
?>

<h2>Gestão de Militares</h2>
        
<!-- 'form-cadastro' alterado para 'form-padrao' -->
<form action="<?php echo BASE_URL; ?>militares/salvar" method="POST" class="form-padrao">
    <h3>Cadastrar Novo Militar</h3>
    <div class="form-group">
        <label for="num">Número:</label>
        <input type="text" id="num" name="numero" required>
    </div>
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    <div class="form-group">
        <label for="posto">Posto/Grad.:</label>
        <input type="text" id="posto" name="posto">
    </div>
    <div class="form-group">
        <label for="cargaHoraria">Carga Horária Padrão (h):</label>
        <input type="number" id="cargaHoraria" name="carga_horaria_padrao" value="160" required>
    </div>
    <!-- Botão padronizado -->
    <button type="submit" class="btn-submit">Salvar Militar</button>
</form>

<hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

<h3>Militares Cadastrados</h3>

<table class="tabela-dados">
    <thead>
        <tr>
            <th>Número</th>
            <th>Posto/Grad.</th>
            <th>Nome</th>
            <th>Carga Padrão</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($militares as $militar): ?>
            <tr>
                <td><?php echo htmlspecialchars($militar['numero']); ?></td>
                <td><?php echo htmlspecialchars($militar['posto']); ?></td>
                <td><?php echo htmlspecialchars($militar['nome']); ?></td>
                <td><?php echo $militar['carga_horaria_padrao']; ?>h</td>
                <td><?php echo ucfirst(htmlspecialchars($militar['status'])); ?></td>
                
                <td>
                    <a href="<?php echo BASE_URL; ?>militares/editar/<?php echo $militar['id']; ?>">
                        Editar
                    </a> | 
                    <a href="<?php echo BASE_URL; ?>militares/excluir/<?php echo $militar['id']; ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir este militar? (Isso também excluirá todas as escalas associadas a ele)');">
                        Excluir
                    </a>
                </td>
                
            </tr>
        <?php endforeach; ?>
        
        <?php if (empty($militares)): ?>
            <tr>
                <td colspan="6">Nenhum militar cadastrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
