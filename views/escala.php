<?php
/*
  VERSÃO SIMPLIFICADA (SEM AJUSTES +/-)
  Esta view agora mostra o cálculo atualizado (que virá do Controller),
  mas só permite editar os TURNOS (Select).
*/
?>

<form method="GET" action="<?php echo BASE_URL; ?>escala" class="form-cadastro">
    <div class="form-group">
        <label for="mes">Selecione o Mês/Ano:</label>
        <input type="month" id="mes" name="mes" value="<?php echo htmlspecialchars($mes_selecionado); ?>">
    </div>
    <button type="submit">Carregar Escala</button>
</form>

<h2>Editor de Escala (Previsão) - <?php echo htmlspecialchars($mes_ano_string); ?></h2>
<!--
<form method="GET" action="<?php echo BASE_URL; ?>escala" class="analisador-periodo">
    <input type="hidden" name="mes" value="<?php echo htmlspecialchars($mes_selecionado); ?>">
    
    <h3>Analisador de Período (Nova Lógica)</h3>
    <div class="form-group">
        <label for="dataInicio">Período de (Dia):</label>
        <input type="number" id="dataInicio" name="dataInicio" value="<?php echo $dia_inicio; ?>" min="1" max="<?php echo $dias_no_mes; ?>" required>
    </div>
    <div class="form-group">
        <label for="dataFim">até (Dia):</label>
        <input type="number" id="dataFim" name="dataFim" value="<?php echo $dia_fim; ?>" min="1" max="<?php echo $dias_no_mes; ?>" required>
    </div>
    <button type="submit">Analisar Saldo no Período</button>
</form>-->

<form method="POST" action="<?php echo BASE_URL; ?>escala/salvar">

    <input type="hidden" name="mes_ano_sql" value="<?php echo htmlspecialchars($mes_ano_sql); ?>">

    <div class="tabela-container">
        <table class="tabela-dados tabela-escala">
            <thead>
                <tr>
                    <th>Militar</th>
                    <th>Carga Padrão</th>
                    
                    <?php for ($i = 1; $i <= $dias_no_mes; $i++): ?>
                        <th><?php echo $i; ?></th>
                    <?php endfor; ?>
                    
                    <th class="coluna-meta-ajustada">Meta Ajustada</th>
                    <th class="coluna-total-realizado">Total Realizado</th>
                    <th class="coluna-saldo">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($militares_db as $id_militar => $dados): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dados['nome']); ?></td>
                        <td><?php echo $dados['carga_padrao']; ?>h</td>
                        
                        <?php for ($i = 1; $i <= $dias_no_mes; $i++): ?>
                            <td>
                                <select name="escala[<?php echo $id_militar; ?>][<?php echo $i; ?>]" style="padding: 5px;">
                                    <?php
                                      $valor_atual = $dados['escala'][$i] ?? 'F';
                                    ?>
                                    <?php foreach ($lista_de_turnos as $turno): ?>
                                        <option value="<?php echo htmlspecialchars($turno['codigo']); ?>"
                                            <?php echo ($turno['codigo'] == $valor_atual) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($turno['codigo']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        <?php endfor; ?>

                        <?php if (isset($analise_resultados[$id_militar])): 
                            $analise = $analise_resultados[$id_militar];
                        ?>
                            <td class="coluna-meta-ajustada">
                                <?php echo $this->formatarHoras($analise['meta_ajustada']); ?>
                                <small>(<?php echo $analise['horas_neutras']; ?>h neutras)</small>
                            </td>
                            <td class="coluna-total-realizado">
                                <?php echo $this->formatarHoras($analise['total_realizado']); ?>
                                <small>(<?php echo $analise['horas_trabalhadas']; ?>h + <?php echo $analise['total_ajustes']; ?>h)</small>
                            </td>
                            <td class="coluna-saldo">
                                <?php echo $this->formatarHoras($analise['saldo']); ?>
                            </td>
                        <?php else: ?>
                            <td class="coluna-meta-ajustada">--</td>
                            <td class="coluna-total-realizado">--</td>
                            <td class="coluna-saldo">--</td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($militares_db)): ?>
                    <tr>
                        <td colspan="<?php echo $dias_no_mes + 5; ?>">
                            Nenhum militar 'ativo' encontrado no banco de dados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px; text-align: right;">
        <button type="submit">Salvar Escala</button>
    </div>
</form>