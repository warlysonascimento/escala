<?php
$turnos_cadastrados = $data['turnos_cadastrados'] ?? $turnos_cadastrados ?? [];
?>

<h2>Gestão de Tipos de Turno</h2>
        
<!-- 'form-cadastro' alterado para 'form-padrao' -->
<form action="<?php echo BASE_URL; ?>turnos/salvar" method="POST" class="form-padrao">
    <h3>Cadastrar Novo Tipo de Turno</h3>
    <div class="form-group">
        <label for="codigo">Código (Ex: 5, H, F, AB):</label>
        <input type="text" id="codigo" name="codigo" required>
    </div>
    <div class="form-group">
        <label for="descricao">Descrição (Ex: Turno 10h, Folga):</label>
        <input type="text" id="descricao" name="descricao" required>
    </div>
    <div class="form-group">
        <label for="duracao_horas">Duração em Horas (Ex: 10.00, 5.55, 0):</label>
        <input type="number" step="0.01" id="duracao_horas" name="duracao_horas" value="0.00" required>
    </div>
    <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo">
            <option value="Trabalho">Trabalho (Conta horas para o total)</option>
            <option value="Folga">Folga (Não conta horas)</option>
            <option value="Neutro">Neutro (Reduz a meta mensal. Ex: Atestado)</option>
        </select>
    </div>
    <button type="submit" class="btn-submit">Salvar Tipo de Turno</button>
</form>

<hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

<h3>Turnos Cadastrados <span style="color:red">(Não editar ou excluir se ja tiver utilizado em alguma escala)</span></h3>

<table class="tabela-dados">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descrição</th>
            <th>Duração (Horas)</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($turnos_cadastrados as $turno): ?>
            <tr>
                <td><?php echo htmlspecialchars($turno['codigo']); ?></td>
                <td><?php echo htmlspecialchars($turno['descricao']); ?></td>
                <td><?php echo number_format($turno['duracao_horas'], 2, ',', '.'); ?> h</td>
                <td><?php echo $turno['tipo']; ?></td>
                
                <td>
                    <a href="<?php echo BASE_URL; ?>turnos/editar/<?php echo $turno['id']; ?>">
                        Editar
                    </a> | 
                    <a href="<?php echo BASE_URL; ?>turnos/excluir/<?php echo $turno['id']; ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir este tipo de turno?');">
                        Excluir
                    </a>
                </td>
                </tr>
        <?php endforeach; ?>
        
        <?php if (empty($turnos_cadastrados)): ?>
            <tr>
                <td colspan="5">Nenhum tipo de turno cadastrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
