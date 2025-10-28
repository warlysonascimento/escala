<?php
$turno = $data['turno'] ?? $turno ?? null;
if (!$turno) die('Erro: Turno não encontrado.');
?>

<h2>Gestão de Tipos de Turno</h2>
<h3>Editar Turno: <?php echo htmlspecialchars($turno['descricao']); ?></h3>
        
<!-- 'form-cadastro' alterado para 'form-padrao' -->
<form action="<?php echo BASE_URL; ?>turnos/atualizar/<?php echo $turno['id']; ?>" method="POST" class="form-padrao">

    <div class="form-group">
        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" 
               value="<?php echo htmlspecialchars($turno['codigo']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" 
               value="<?php echo htmlspecialchars($turno['descricao']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="duracao_horas">Duração em Horas:</label>
        <input type="number" step="0.01" id="duracao_horas" name="duracao_horas" 
               value="<?php echo htmlspecialchars($turno['duracao_horas']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo">
            <option value="Trabalho" <?php echo ($turno['tipo'] == 'Trabalho') ? 'selected' : ''; ?>>
                Trabalho (Conta horas para o total)
            </option>
            <option value="Folga" <?php echo ($turno['tipo'] == 'Folga') ? 'selected' : ''; ?>>
                Folga (Não conta horas)
            </option>
            <option value="Neutro" <?php echo ($turno['tipo'] == 'Neutro') ? 'selected' : ''; ?>>
                Neutro (Reduz a meta mensal. Ex: Atestado)
            </option>
        </select>
    </div>
    
    <button type="submit" class="btn-submit">Atualizar Turno</button>
    <a href="<?php echo BASE_URL; ?>turnos" class="btn-cancelar">Cancelar</a>
</form>
