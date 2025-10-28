<?php
// Variáveis $militares, $dias_no_mes, $lista_completa_turnos, etc., 
// são injetadas pelo RelatoriosController
?>

<style>
    /* ... (CSS existente) ... */
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        font-size: 11px; /* Tamanho menor para caber mais dados */
    }
    th, td {
        border: 1px solid #ccc;
        padding: 4px 6px;
        text-align: center;
        white-space: nowrap; /* Impede quebra de linha */
    }
    th {
        background-color: #f4f4f4;
        color: #333;
    }
    td {
        background-color: #fff;
    }
    
    /* Classe para alinhar nomes à esquerda */
    .militar-nome {
        text-align: left;
        font-weight: bold;
        min-width: 160px;
        white-space: normal;
    }
    
    /* Tabela de Escala */
    .tabela-escala th.dia {
        width: 28px;
    }
    .tabela-escala .ajuste-dia {
        display: block;
        font-size: 9px;
        color: #007bff;
        font-weight: bold;
    }

    /* Tabela de Controle de Horas */
    .tabela-controle td, .tabela-controle th {
        font-size: 12px;
        padding: 6px;
    }
    .tabela-controle .total { 
        font-weight: bold; 
    }
    .tabela-controle .saldo { 
        font-weight: bold; 
    }
    
    .saldo-negativo {
        color: #d9534f; /* Vermelho */
    }
    .saldo-positivo {
        color: #0275d8; /* Azul */
    }
    .saldo-zero {
        color: #5cb85c; /* Verde */
    }

    /* Legenda de Turnos (Formato Tabela) */
    .legenda-tabela-grid {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .legenda-tabela-grid td {
        border: 1px solid #ddd;
        padding: 5px;
        width: 20%; /* 5 colunas */
        text-align: left;
        font-size: 10px;
        white-space: normal; /* Permite quebra de linha se a descrição for longa */
    }
    .legenda-tabela-grid td strong {
        color: #004a91;
    }


    /* Em caso de impressão */
    @media print {
        body {
            font-size: 10px;
        }
        h1 { font-size: 16pt; }
        h2 { font-size: 14pt; }
        h3 { font-size: 12pt; }
        
        table {
            font-size: 9pt;
        }
        .tabela-controle td, .tabela-controle th {
             font-size: 10pt;
        }
        .legenda-tabela-grid {
            width: 100%;
            font-size: 8pt;
            page-break-inside: avoid;
        }
    }
</style>


<h1>PREVISÃO DE CARGA HORÁRIA - <?php echo strtoupper($mes_ano_string); ?></h1>


<?php if (empty($militares)): ?>
    <p>Nenhum dado de escala encontrado para o período selecionado.</p>
<?php else: ?>

    <h2>ESCALA DE SERVIÇO</h2>
    <table class="tabela-escala">
        <thead>
            <tr>
                <th>Militar</th>
                <?php for ($dia = 1; $dia <= $dias_no_mes; $dia++): ?>
                    <th class="dia"><?php echo $dia; ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($militares as $militar): ?>
                <tr>
                    <!-- USA A VARIÁVEL 'posto_e_nome' CORRETA (VINDA DO CONTROLLER) -->
                    <td class="militar-nome"><?php echo htmlspecialchars($militar['posto_e_nome']); ?></td>
                    
                    <?php for ($dia = 1; $dia <= $dias_no_mes; $dia++): ?>
                        <td>
                            <?php 
                                $codigo = $militar['escala'][$dia] ?? ' ';
                                echo htmlspecialchars($codigo); 
                                
                                if (isset($militar['ajustes'][$dia]) && $militar['ajustes'][$dia] != 0) {
                                    echo '<span class="ajuste-dia">(' . $militar['ajustes'][$dia] . ')</span>';
                                }
                            ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    
    <h2 style="margin-top: 40px;">Legenda de Turnos</h2>
    <table class="legenda-tabela-grid">
        <tbody>
            <?php 
                $colunas = 5;
                $i = 0;
                foreach ($lista_completa_turnos as $turno):
                    if ($i % $colunas == 0) {
                        echo '<tr>'; // Inicia nova linha
                    }
            ?>
                <td>
                    <strong><?php echo htmlspecialchars($turno['codigo']); ?>:</strong>
                    <?php echo htmlspecialchars($turno['descricao']); ?>
                </td>
            <?php 
                    $i++;
                    if ($i % $colunas == 0) {
                        echo '</tr>'; // Fecha a linha
                    }
                endforeach; 
                
                // Se o loop terminar e a linha não estiver fechada
                if ($i % $colunas != 0) {
                    while ($i % $colunas != 0) {
                        echo '<td>&nbsp;</td>'; // Células vazias
                        $i++;
                    }
                    echo '</tr>'; // Fecha a última linha
                }
            ?>
        </tbody>
    </table>
    
    <h2 style="margin-top: 40px;">CONTROLE MENSAL DA CARGA HORÁRIA</h2>
    <table class="tabela-controle">
        <thead>
            <tr>
                <th>Militar</th>
                <th>Carga Padrão</th>
                <th>Meta Ajustada</th>
                <th>Horas (Escala)</th>
                <th>Horas (Ajuste)</th>
                <th>Total Trabalhado</th>
                <th>Saldo Anterior</th> <!-- NOVO -->
                <th>Saldo do Mês</th> <!-- RENOMEADO -->
                <th>Saldo Acumulado</th> <!-- NOVO -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($militares as $militar): ?>
                <tr>
                    <td class="militar-nome"><?php echo htmlspecialchars($militar['posto_e_nome']); ?></td>
                    
                    <td><?php echo $militar['carga_horaria_padrao']; ?> h</td>
                    <td><?php echo $militar['meta_ajustada']; ?> h</td>
                    <td><?php echo $militar['total_horas_escala']; ?> h</td>
                    <td><?php echo $militar['total_horas_ajuste']; ?> h</td>
                    <td class="total"><?php echo $militar['total_trabalhado']; ?> h</td>
                    
                    <!-- Coluna Saldo Anterior -->
                    <?php
                        $saldo_ant = $militar['saldo_anterior'];
                        $saldo_ant_class = $saldo_ant < 0 ? 'saldo-negativo' : ($saldo_ant > 0 ? 'saldo-positivo' : 'saldo-zero');
                    ?>
                    <td class="saldo <?php echo $saldo_ant_class; ?>">
                        <?php echo $saldo_ant; ?> h
                    </td>

                    <!-- Coluna Saldo do Mês -->
                    <?php
                        $saldo_mes = $militar['saldo_horas']; // Esta é a variável do saldo do mês
                        $saldo_mes_class = $saldo_mes < 0 ? 'saldo-negativo' : ($saldo_mes > 0 ? 'saldo-positivo' : 'saldo-zero');
                    ?>
                    <td class="saldo <?php echo $saldo_mes_class; ?>">
                        <?php echo $saldo_mes; ?> h
                    </td>

                    <!-- Coluna Saldo Acumulado -->
                    <?php
                        $saldo_acum = $militar['saldo_acumulado'];
                        $saldo_acum_class = $saldo_acum < 0 ? 'saldo-negativo' : ($saldo_acum > 0 ? 'saldo-positivo' : 'saldo-zero');
                    ?>
                    <td class="saldo <?php echo $saldo_acum_class; ?>">
                        <?php echo $saldo_acum; ?> h
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; // Fim do if (empty($militares)) ?>

