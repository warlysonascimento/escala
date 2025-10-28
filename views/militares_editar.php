<?php
$militar = $data['militar'] ?? $militar ?? null;
if (!$militar) die('Erro: Militar não encontrado.');
?>

<h2>Gestão de Militares</h2>
<h3>Editar Militar: <?php echo htmlspecialchars($militar['nome']); ?></h3>
        
<!-- 'form-cadastro' alterado para 'form-padrao' -->
<form action="<?php echo BASE_URL; ?>militares/atualizar/<?php echo $militar['id']; ?>" method="POST" class="form-padrao">

    <div class="form-group">
        <label for="num">Número:</label>
        <input type="text" id="num" name="numero" 
               value="<?php echo htmlspecialchars($militar['numero']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" 
               value="<?php echo htmlspecialchars($militar['nome']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="posto">Posto/Grad.:</label>
        <input type="text" id="posto" name="posto" 
               value="<?php echo htmlspecialchars($militar['posto']); ?>">
    </div>
    
    <div class="form-group">
        <label for="cargaHoraria">Carga Horária Padrão (h):</label>
        <input type="number" id="cargaHoraria" name="carga_horaria_padrao" 
               value="<?php echo $militar['carga_horaria_padrao']; ?>" required>
    </div>
    
     <div class="form-group">
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="ativo" <?php echo ($militar['status'] == 'ativo') ? 'selected' : ''; ?>>
                Ativo
            </option>
            <option value="inativo" <?php echo ($militar['status'] == 'inativo') ? 'selected' : ''; ?>>
                Inativo
            </option>
            <option value="ferias" <?php echo ($militar['status'] == 'ferias') ? 'selected' : ''; ?>>
                Férias
            </option>
             <option value="licenca" <?php echo ($militar['status'] == 'licenca') ? 'selected' : ''; ?>>
                Licença
            </option>
        </select>
    </div>
    
    <button type="submit" class="btn-submit">Atualizar Militar</button>
    <a href="<?php echo BASE_URL; ?>militares" class="btn-cancelar">Cancelar</a>
</form>
