<?php
namespace App\Controllers; 

use App\Core\Controller; 

class RelatoriosController extends Controller {

    private $militarModel;
    private $escalaModel;
    private $turnoModel;
    private $lancamentoModel;

    public function __construct() {
        // Protege o controller
        $this->checkRole(['admin', 'gestor', 'leitor']); 
        
        $this->militarModel = $this->model('Militar');
        $this->escalaModel = $this->model('Escala');
        $this->turnoModel = $this->model('Turno');
        $this->lancamentoModel = $this->model('Lancamento');
    }

    public function index() {
        $this->view('relatorios');
    }

    public function gerar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "relatorios");
            exit;
        }

        // --- 1. OBTER DADOS ---
        $mes_selecionado = $_POST['mes'] ?? date('Y-m');
        $tipo_relatorio = $_POST['tipo_relatorio'] ?? 'escala_codigos_pdf'; 
        
        $mes_ano_sql = $mes_selecionado . '-01'; 
        $timestamp_mes_atual = strtotime($mes_ano_sql);
        $dias_no_mes = (int)date('t', $timestamp_mes_atual);
        $mes_atual_num = (int)date('n', $timestamp_mes_atual);
        $ano_atual_num = (int)date('Y', $timestamp_mes_atual);
        
        $fmt = new \IntlDateFormatter('pt_BR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null, 'MMMM/yyyy');
        $mes_ano_string = ucfirst($fmt->format($timestamp_mes_atual));

        // --- 2. BUSCAR DADOS-BASE (COMUNS A TODOS OS CÁLCULOS) ---
        $militares_relatorio = $this->militarModel->getAllMilitaresRelatorio(); 
        $turnos_map_horas = $this->turnoModel->getTurnoMap(); 
        $turnos_map_tipo = $this->turnoModel->getTurnoTypeMap();
        $lista_completa_turnos = $this->turnoModel->getAll();
        
        $saldos_anteriores_map = [];
        foreach ($militares_relatorio as $militar) {
            $saldos_anteriores_map[$militar['id']] = 0; // Inicializa o saldo anterior
        }

        // ===================================================================
        // --- 3. CÁLCULO DO SALDO ANTERIOR (CUMULATIVO) ---
        // ===================================================================
        // Loop por todos os meses ANTERIORES ao mês selecionado (desde Jan)
        
        for ($m = 1; $m < $mes_atual_num; $m++) {
            $mes_loop_sql = $ano_atual_num . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) . '-01';

            // 3.1. Busca dados do mês anterior (loop)
            $escalas_loop_raw = $this->escalaModel->getEscalasSqlDoMes($mes_loop_sql);
            $ajustes_loop = $this->lancamentoModel->getByTenantMes($mes_loop_sql); 

            $escalas_map = [];
            foreach ($escalas_loop_raw as $esc) {
                $escalas_map[$esc['id_militar']] = $esc;
            }

            // 3.2. Calcula o saldo de CADA militar para aquele mês
            foreach ($militares_relatorio as $militar) {
                $id_militar = $militar['id'];
                
                // --- INÍCIO DA CORREÇÃO ---
                // O cálculo do saldo SÓ acontece se o militar tiver uma escala salva para este mês
                if (isset($escalas_map[$id_militar])) {
                    
                    $total_horas_escala_loop = 0;
                    $horas_neutras_loop = 0;

                    $dias_json = $escalas_map[$id_militar]['dias_json'] ?? '[]';
                    $dias_array = json_decode($dias_json, true);

                    // Loop pelos dias (1 a 31)
                    for ($d = 1; $d <= 31; $d++) { 
                        $codigo_turno = $dias_array[$d] ?? 'F';
                        $horas = (float)($turnos_map_horas[$codigo_turno] ?? 0);
                        $tipo = $turnos_map_tipo[$codigo_turno] ?? 'Folga';
                        
                        if ($tipo === 'Trabalho') {
                            $total_horas_escala_loop += $horas;
                        } elseif ($tipo === 'Neutro') {
                            $horas_neutras_loop += $horas;
                        }
                    }

                    // Pega ajustes do mês (loop)
                    $ajustes_militar_loop = $ajustes_loop[$id_militar] ?? [];
                    $total_horas_ajuste_loop = array_sum($ajustes_militar_loop);

                    // Calcula o saldo do mês (loop)
                    $total_trabalhado_loop = $total_horas_escala_loop + $total_horas_ajuste_loop;
                    $meta_ajustada_loop = (float)$militar['carga_horaria_padrao'] - $horas_neutras_loop;
                    $saldo_do_mes_loop = $total_trabalhado_loop - $meta_ajustada_loop;

                    // 3.3. Acumula no saldo anterior
                    $saldos_anteriores_map[$id_militar] += $saldo_do_mes_loop;
                }
                // Se não houver escala (isset falhar), nada é somado. O saldo para este mês é 0.
                // --- FIM DA CORREÇÃO ---
            }
        }
        // ===================================================================
        // --- FIM DO CÁLCULO CUMULATIVO ---
        // ===================================================================


        // --- 4. BUSCAR DADOS DO MÊS ATUAL (SELECIONADO) ---
        $escalas_db_raw = $this->escalaModel->getEscalasSqlDoMes($mes_ano_sql); 
        $ajustes_db = $this->lancamentoModel->getByTenantMes($mes_ano_sql); 

        $escalas_db = [];
        foreach ($escalas_db_raw as $esc) {
            $escalas_db[$esc['id_militar']] = $esc;
        }
        
        // --- 5. PROCESSAMENTO E CÁLCULO DE HORAS (MÊS ATUAL) ---
        $militares_processados = []; 

        foreach ($militares_relatorio as $militar) {
            $id_militar = $militar['id'];
            
            $militar['posto_e_nome'] = trim(($militar['posto'] ?? '') . ' ' . ($militar['nome'] ?? ''));
            
            // Inicializa dados
            $militar['escala'] = [];
            $militar['ajustes'] = [];
            for ($d = 1; $d <= $dias_no_mes; $d++) {
                $militar['escala'][$d] = 'F'; 
            }

            // 5.1. Hidrata a Escala (mês atual)
            if (isset($escalas_db[$id_militar])) {
                $dias_json = $escalas_db[$id_militar]['dias_json'] ?? '[]';
                $dias_array = json_decode($dias_json, true);
                if (is_array($dias_array)) {
                    foreach ($dias_array as $dia => $codigo) {
                        if ($dia > 0 && $dia <= $dias_no_mes) {
                            $militar['escala'][(int)$dia] = $codigo;
                        }
                    }
                }
            }
            
            // 5.2. Hidrata os Ajustes (mês atual)
            if (isset($ajustes_db[$id_militar])) {
                $militar['ajustes'] = $ajustes_db[$id_militar]; 
            }

            // 5.3. Calcula os totais (mês atual)
            $total_horas_escala = 0;
            $horas_neutras = 0;
            
            foreach ($militar['escala'] as $dia => $codigo_turno) {
                $horas = (float)($turnos_map_horas[$codigo_turno] ?? 0);
                $tipo = $turnos_map_tipo[$codigo_turno] ?? 'Folga';
                
                if ($tipo === 'Trabalho') {
                    $total_horas_escala += $horas;
                } elseif ($tipo === 'Neutro') {
                    $horas_neutras += $horas;
                }
            }
            
            $total_horas_ajuste = array_sum($militar['ajustes']);
            $total_trabalhado = $total_horas_escala + $total_horas_ajuste;
            $meta_ajustada = (float)$militar['carga_horaria_padrao'] - $horas_neutras;
            
            // ===================================================================
            // --- CÁLCULO FINAL CUMULATIVO ---
            // ===================================================================
            $saldo_mes = $total_trabalhado - $meta_ajustada;
            $saldo_anterior = $saldos_anteriores_map[$id_militar] ?? 0;
            $saldo_acumulado = $saldo_anterior + $saldo_mes;
            // ===================================================================
            
            $militar['total_horas_escala'] = $total_horas_escala;
            $militar['total_horas_ajuste'] = $total_horas_ajuste;
            $militar['total_trabalhado']   = $total_trabalhado;
            $militar['meta_ajustada'] = $meta_ajustada; 
            
            $militar['saldo_anterior'] = $saldo_anterior; // <- NOVO
            $militar['saldo_horas'] = $saldo_mes; // (Renomeado mentalmente de 'saldo_horas' para 'saldo_mes')
            $militar['saldo_acumulado'] = $saldo_acumulado; // <- NOVO
            
            $militares_processados[$id_militar] = $militar;
        }
        
        // --- 6. DETERMINAR QUAL TEMPLATE USAR ---
        // ... (O resto do controller continua igual) ...
        
        $titulo_relatorio = "";
        $template_path = "";

        if (strpos($tipo_relatorio, 'calculo_horas') !== false) {
            $titulo_relatorio = "Relatório de Cálculo de Horas - " . $mes_ano_string;
            $template_path = __DIR__ . '/../../views/relatorios_templates/calculo_pdf.php';
        } else {
            $titulo_relatorio = "Relatório de Escala - " . $mes_ano_string;
            $template_path = __DIR__ . '/../../views/relatorios_templates/escala_pdf.php';
        }
        
        // --- 7. CARREGAR A VIEW MANUALMENTE ---
        extract([
            'militares' => $militares_processados, 
            'dias_no_mes' => $dias_no_mes,
            'turnos_map_horas' => $turnos_map_horas,
            'turnos_map_tipo' => $turnos_map_tipo,
            'mes_ano_string' => $mes_ano_string,
            'lista_completa_turnos' => $lista_completa_turnos
        ]);

        // Carrega o header (que inclui o CSS)
        require_once __DIR__ . '/../../views/partials/header.php';

        // Botões de Ação (Não imprimir)
        echo '<div class="no-print">';
        echo '<h2>' . htmlspecialchars($titulo_relatorio) . '</h2>';
        echo '<p><a href="' . BASE_URL . 'relatorios">« Voltar à seleção de relatórios</a></p>';
        echo '<button onclick="imprimirRelatorio()" style="margin: 10px 0; padding: 10px 15px; background-color: #004a91; color: white; border: none; border-radius: 4px; cursor: pointer;">Imprimir / Salvar em PDF</button>';
        echo '<hr style="margin: 20px 0;">';
        echo '</div>';

        // Área Imprimível
        echo '<div id="printable-area">';
        include $template_path; // A view vai encontrar 'carga_horaria_padrao'
        echo '</div>';

        // Script de Impressão
        echo <<<HTML
        <style>
            @media print {
                /* Esconde o menu, footer e botões */
                .menu-principal, .site-footer, .no-print {
                    display: none !important;
                }
                 /* Reseta o padding do body que o header.php adiciona */
                body {
                    padding-top: 0 !important;
                }
            }
        </style>
        
        <script>
        function imprimirRelatorio() {
            const printContents = document.getElementById('printable-area').innerHTML;
            const printWindow = window.open('', '_blank');
            
            // Tenta pegar os links de CSS da página principal
            let cssLinks = '';
            document.querySelectorAll('link[rel="stylesheet"]').forEach(link => {
                cssLinks += link.outerHTML;
            });
            
            // Tenta pegar os estilos inline da página principal (como o do header)
            let styleTags = '';
            document.querySelectorAll('style').forEach(style => {
                styleTags += style.outerHTML;
            });

            printWindow.document.write(`
                <html>
                <head>
                    <title>Imprimir Relatório - \${document.title}</title>
                    \${cssLinks}
                    \${styleTags}
                </head>
                <body style="margin:0; padding: 10px;">
                    \${printContents}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
            }, 500); 
        }
        </script>
        HTML;

        // Carrega o footer
        require_once __DIR__ . '/../../views/partials/footer.php';
    }
    

    private function formatarHoras($decimal_horas) {
        $sinal = $decimal_horas < 0 ? '-' : '';
        $decimal_horas = abs($decimal_horas);
        $horas = floor($decimal_horas);
        $minutos_decimais = ($decimal_horas - $horas) * 60;
        $minutos = round($minutos_decimais);
        if ($minutos == 60) {
            $horas++;
            $minutos = 0;
        }
        return sprintf("%s%02d:%02d:00", $sinal, $horas, $minutos);
    }
}

