<?php
/*
  Esta view recebe $data['unidades'] do UnidadesController
*/
?>

<h2>Gestão de Unidades</h2>
        
<form action="<?php echo BASE_URL; ?>unidades/salvar" method="POST" class="form-cadastro">
    <h3>Cadastrar Nova Unidade</h3>
    <div class="form-group">
        <label for="nome_grupo">Nome da Unidade:</label>
        <input type="text" id="nome_grupo" name="nome_grupo" required>
    </div>
    <div class="form-group">
        <label for="codigo_grupo">Código (Ex: 0684, 10BPM, 5CIA):</label>
        <input type="text" id="codigo_grupo" name="codigo_grupo" required>
    </div>
    <button type="submit">Salvar Unidade</button>
</form>

<hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

<h3>Unidades Cadastradas</h3>

<table class="tabela-dados">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome da Unidade</th>
            <th>Código</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['unidades'] as $unidade): ?>
            <tr>
                <td><?php echo $unidade['id']; ?></td>
                <td><?php echo htmlspecialchars($unidade['nome_grupo']); ?></td>
                <td><?php echo htmlspecialchars($unidade['codigo_grupo']); ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>unidades/editar/<?php echo $unidade['id']; ?>">
                        Editar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        
        <?php if (empty($data['unidades'])): ?>
            <tr>
                <td colspan="4">Nenhuma unidade cadastrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
