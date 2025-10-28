<h2>Gerar Relatórios</h2>
<p>Selecione o mês e o tipo de relatório que deseja visualizar na tela.</p>

<!-- 'form-cadastro' alterado para 'form-padrao' e centralizado -->
<form method="POST" action="<?php echo BASE_URL; ?>relatorios/gerar" class="form-padrao" style="max-width: 600px; margin: 20px auto;">
    
    <div class="form-group">
        <label for="mes">Mês do Relatório:</label>
        <input type="month" id="mes" name="mes" 
               value="<?php echo date('Y-m'); ?>" required>
    </div>

    <div class="form-group">
        <label for="tipo_relatorio">Tipo de Relatório:</label>
        <select id="tipo_relatorio" name="tipo_relatorio" required>
            <option value="escala_codigos_pdf">
                Relatório de Escala 
            </option>
            <option value="calculo_horas_pdf">
                Relatório de Cálculo de Horas
            </option>
        </select>
    </div>
    
    <button type="submit" class="btn-submit">Gerar Relatório</button>
</form>
