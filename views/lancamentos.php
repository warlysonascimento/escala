<?php
// Define variáveis com valores padrão seguros
$mes_selecionado = $data['mes_selecionado'] ?? date('Y-m');
$lancamentos_do_mes = $data['lancamentos_do_mes'] ?? [];
$lista_militares = $data['lista_militares'] ?? [];
?>

<h2>Lançamentos de Ajustes (Horas Extras / Saídas)</h2>

<!-- Aplicado '.form-filtro' -->
<form method="GET" action="<?php echo BASE_URL; ?>lancamentos" class="form-filtro" style="margin-bottom: 20px;">
    <div class="form-group">
        <label for="mes">Visualizar Lançamentos do Mês:</label>
        <!-- Adicionado type="month" que estava faltando -->
        <input type="month" id="mes" name="mes" value="<?php echo htmlspecialchars($mes_selecionado); ?>">
    </div>
    <!-- Aplicado '.btn-submit' -->
    <button type="submit" class="btn-submit">Carregar Mês</button>
</form>

<hr>

<!-- Aplicado '.form-padrao' -->
<form action="<?php echo BASE_URL; ?>lancamentos/salvar" method="POST" class="form-padrao">
    <h3>Novo Lançamento</h3>
    
    <div class="form-group">
        <label for="id_militar">Militar:</label>
        <select id="id_militar" name="id_militar" required>
            <option value="">-- Selecione um militar --</option>
            <?php foreach ($lista_militares as $militar): ?>
                <option value="<?php echo $militar['id']; ?>">
                    <?php echo htmlspecialchars(($militar['posto'] ?? '') . ' ' . ($militar['nome'] ?? '')); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="data_lancamento">Data:</label>
        <input type="date" id="data_lancamento" name="data_lancamento" value="<?php echo date('Y-m-d'); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="horas_ajuste">Horas de Ajuste (Use negativo para saídas):</label>
        <input type="number" step="0.5" id="horas_ajuste" name="horas_ajuste" placeholder="Ex: 2.5 (extra) ou -1 (saída)" required>
    </div>
    
    <div class="form-group">
        <label for="justificativa">Justificativa (Opcional):</label>
        <input type="text" id="justificativa" name="justificativa" placeholder="Ex: Apoio evento X">
    </div>
    
    <!-- Aplicado '.btn-submit' -->
    <button type="submit" class="btn-submit">Salvar Lançamento</button>
</form>

<hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

<h3>Lançamentos Salvos (<?php echo htmlspecialchars($mes_selecionado); ?>)</h3>
<table class="tabela-dados">
    <thead>
        <tr>
            <th>Data</th>
            <th>Militar</th>
            <th>Ajuste (Horas)</th>
            <th>Justificativa</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lancamentos_do_mes as $lancamento): ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($lancamento['data_lancamento'])); ?></td>
                <td><?php echo htmlspecialchars(($lancamento['militar_posto'] ?? '') . ' ' . ($lancamento['militar_nome'] ?? '')); ?></td>
                <td style="color: <?php echo ($lancamento['horas_ajuste'] ?? 0) > 0 ? 'green' : 'red'; ?>; font-weight: bold;">
                    <?php echo ($lancamento['horas_ajuste'] ?? 0) > 0 ? '+' : ''; ?>
                    <?php echo number_format($lancamento['horas_ajuste'] ?? 0, 2, ',', '.'); ?>h
                </td>
                <td><?php echo htmlspecialchars($lancamento['justificativa'] ?? ''); ?></td>
                <td>
                    <!-- Link de Excluir (mantido sem botão para simplicidade na tabela) -->
                    <a href="<?php echo BASE_URL; ?>lancamentos/excluir/<?php echo $lancamento['id']; ?>?mes=<?php echo $mes_selecionado; ?>"
                       onclick="return confirm('Tem certeza que deseja excluir este lançamento?');">
                        Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        
        <?php if (empty($lancamentos_do_mes)): ?>
            <tr>
                <td colspan="5">Nenhum lançamento de ajuste encontrado para este mês.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

