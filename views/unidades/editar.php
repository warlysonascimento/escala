<?php
/*
  Esta view recebe $data['unidade'] do UnidadesController
*/
$unidade = $data['unidade'];
?>

<h2>Gestão de Unidades</h2>
<h3>Editar Unidade: <?php echo htmlspecialchars($unidade['nome_grupo']); ?></h3>
        
<form action="<?php echo BASE_URL; ?>unidades/atualizar/<?php echo $unidade['id']; ?>" method="POST" class="form-cadastro">

    <div class="form-group">
        <label for="nome_grupo">Nome da Unidade:</label>
        <input type="text" id="nome_grupo" name="nome_grupo" 
               value="<?php echo htmlspecialchars($unidade['nome_grupo']); ?>" required>
    </div>
    <div class="form-group">
        <label for="codigo_grupo">Código (Ex: 0684, 10BPM, 5CIA):</label>
        <input type="text" id="codigo_grupo" name="codigo_grupo" 
               value="<?php echo htmlspecialchars($unidade['codigo_grupo']); ?>" required>
    </div>
    
    <button type="submit">Atualizar Unidade</button>
    <a href="<?php echo BASE_URL; ?>unidades" style="margin-left: 10px;">Cancelar</a>
</form>
