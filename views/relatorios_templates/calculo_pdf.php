<?php
// Variáveis $militares, $dias_no_mes, $lista_completa_turnos, $turnos_map_horas
// são injetadas pelo RelatoriosController
?>

<style>
    body {
        font-family: Arial, sans-serif;
    }
    h1, h2, h3 {
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        font-size: 11px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 4px 6px;
        text-align: center;
        white-space: nowrap;
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

    /* Tabela de Escala (AGORA EM HORAS) */
    .tabela-escala-horas th.dia {
        width: 28px;
    }
    .tabela-escala-horas .ajuste-dia {
        display: block;
        font-size: 9px;
        color: #007bff;
        font-weight: bold;
    }
    /* Célula de hora "zero" (Folga) */
    .tabela-escala-horas .hora-zero {
        color: #bbb;
        font-size: 10px;
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
    
    .saldo-negativo { color: #d9534f; /* Vermelho */ }
    .saldo-positivo { color: #0275d8; /* Azul */ }
    .saldo-zero { color: #5cb85c; /* Verde */ }

    /* Estilo para Legenda */
    .legenda-tabela { 
        margin-top: 20px;
        width: 60%; 
    }
    .legenda-tabela th, .legenda-tabela td { 
        text-align: left; 
        padding: 6px; 
        font-size: 12px;
        white-space: normal;
    }
    .legenda-tabela th { width: 20%; }


    @media print {
        body { font-size: 10px; }
        h1 { font-size: 16pt; }
        h2 { font-size: 14pt; }
        h3 { font-size: 12pt; }
        table { font-size: 9pt; }
        .tabela-controle td, .tabela-controle th { font-size: 10pt; }
        .legenda-tabela { width: 80%; font-size: 10pt; page-break-inside: avoid; }
    }
</style>


<h1>CÁLCULO DE CARGA HORÁRIA - <?php echo strtoupper($mes_ano_string); ?></h1>


<?php if (empty($militares)): ?>
    <p>Nenhum dado de escala encontrado para o período selecionado.</p>
<?php else: ?>

    <h2>DETALHAMENTO DIÁRIO DE HORAS (ESCALA + AJUSTES)</h2>
    <table class="tabela-escala-horas">
        <thead>
            <tr>
                <th>Militar</th>
                <?php for ($dia = 1; $dia <= $dias_no_mes; $dia++): ?>
                    <th class="dia"><?php echo $dia; ?></th>
                <?php endfor; ?>
                <th style="background-color: #e0e0e0;">Total (Escala)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($militares as $militar): ?>
                <tr>
                    <td class="militar-nome"><?php echo htmlspecialchars($militar['posto_e_nome']); ?></td>
                    
                    <?php 
                    // Loop para os dias (1 a 31)
                    for ($dia = 1; $dia <= $dias_no_mes; $dia++):
                        
                        // 1. Pega o código do turno (ex: 'S', 'H', 'F')
                        $codigo = $militar['escala'][$dia] ?? null;
                        
                        // 2. Converte o código em horas usando o map
                        $horas_dia = 0;
                        $tipo_turno = $turnos_map_tipo[$codigo] ?? 'Folga';
                        
                        // Só conta horas se for do tipo 'Trabalho'
                        if ($tipo_turno === 'Trabalho') {
                             $horas_dia = (float)($turnos_map_horas[$codigo] ?? 0);
                        }
                        
                        // 3. Verifica o CSS para dias de folga
                        $css_class = ($horas_dia == 0) ? 'class="hora-zero"' : '';
                    ?>
                        <td <?php echo $css_class; ?>>
                            <?php 
                                // Exibe a hora do dia
                                echo $horas_dia; 
                                
                                // Se houver ajuste, exibe também
                                if (isset($militar['ajustes'][$dia]) && $militar['ajustes'][$dia] != 0) {
                                    echo '<span class="ajuste-dia">(' . $militar['ajustes'][$dia] . ')</span>';
                                }
                            ?>
                        </td>
                    <?php endfor; // Fim do loop de dias ?>
                    
                    <td style="font-weight: bold; background-color: #f4f4f4;">
                        <?php echo $militar['total_horas_escala']; ?> h
                    </td>
                </tr>
            <?php endforeach; // Fim do loop de militares ?>
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
                    
                    <!-- CORREÇÃO: Usando a variável correta vinda do Controller -->
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

