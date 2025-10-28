<?php
namespace App\Controllers;

use App\Core\Controller;

class EscalaController extends Controller {

    private $escalaModel;
    private $turnoModel;
    private $lancamentoModel; // <-- ADICIONADO

    public function __construct() {
        $this->escalaModel = $this->model('Escala');
        $this->turnoModel = $this->model('Turno');
        $this->lancamentoModel = $this->model('Lancamento'); // <-- ADICIONADO
    }

    // GET /escala
    public function index() {
        
        // --- 1. Lógica do Seletor de Mês (Sem alteração) ---
        $mes_selecionado = $_GET['mes'] ?? date('Y-m');
        $mes_ano_sql = $mes_selecionado . '-01';
        $fmt = new \IntlDateFormatter('pt_BR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null, 'MMMM/yyyy');
        $mes_ano_string = ucfirst($fmt->format(strtotime($mes_ano_sql)));
        $dias_no_mes = (int)date('t', strtotime($mes_ano_sql));


        // --- 2. Buscar Dados ---
        // O EscalaModel agora retorna $dados['ajustes'] = [] (array vazio)
        $militares_db = $this->escalaModel->getEscalaDoMes($mes_ano_sql); 
        $turnos_map_horas = $this->turnoModel->getTurnoMap();
        $lista_de_turnos = $this->turnoModel->getAll();
        $turnos_map_tipo = $this->turnoModel->getTurnoTypeMap();
        
        /*
         * --- NOVA LÓGICA DE HIDRATAÇÃO ---
         * Vamos popular o array $militares_db['ajustes'] 
         * com os dados da nova tabela 'lancamentos_ajustes'
        */
        foreach ($militares_db as $id_militar => &$dados) { // Usamos & para modificar o array
            $ajustes_do_militar = $this->lancamentoModel->getByMilitarMes($id_militar, $mes_ano_sql);
            
            $mapa_ajustes_dia = [];
            foreach($ajustes_do_militar as $lancamento) {
                $dia = (int)date('j', strtotime($lancamento['data_lancamento']));
                // Acumula os ajustes (ex: se houver 2 lançamentos no mesmo dia)
                $mapa_ajustes_dia[$dia] = ($mapa_ajustes_dia[$dia] ?? 0) + (float)$lancamento['horas_ajuste'];
            }
            // Injeta o mapa de ajustes [dia => total_horas] no militar
            $dados['ajustes'] = $mapa_ajustes_dia; 
        }
        unset($dados); // Limpa a referência
        /* --- FIM DA NOVA LÓGICA --- */

        
        // --- 3. LÓGICA DE CÁLCULO DO ANALISADOR (REESCRITA) ---
        // (Esta lógica agora funciona, pois $dados['ajustes'] está populado)
        $dia_inicio = $_GET['dataInicio'] ?? 1;
        $dia_fim = $_GET['dataFim'] ?? $dias_no_mes;
        $analise_resultados = [];

        foreach ($militares_db as $id_militar => $dados) {
            
            $meta_padrao = $dados['carga_padrao'];
            $horas_neutras = 0;
            $horas_trabalhadas = 0;
            $total_ajustes = 0;

            // Loop A: Calcula as Horas Neutras do MÊS INTEIRO
            for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
                $codigo = $dados['escala'][$dia] ?? 'F';
                $tipo_turno = $turnos_map_tipo[$codigo] ?? 'Folga';
                $horas_turno = $turnos_map_horas[$codigo] ?? 0;
                if ($tipo_turno === 'Neutro') {
                    $horas_neutras += $horas_turno;
                }
            }
            $meta_ajustada_mes_inteiro = $meta_padrao - $horas_neutras;
            
            // Loop B: Calcula o trabalho realizado APENAS NO PERÍODO SELECIONADO
            for ($dia = $dia_inicio; $dia <= $dia_fim; $dia++) {
                $codigo = $dados['escala'][$dia] ?? 'F';
                $tipo_turno = $turnos_map_tipo[$codigo] ?? 'Folga';
                $horas_turno = $turnos_map_horas[$codigo] ?? 0;
                if ($tipo_turno === 'Trabalho') {
                    $horas_trabalhadas += $horas_turno;
                }
                $total_ajustes += (float)($dados['ajustes'][$dia] ?? 0);
            }
            $total_realizado = $horas_trabalhadas + $total_ajustes;
            
            // --- Recalculando o saldo para o MÊS INTEIRO ---
            $horas_trabalhadas_mes_inteiro = 0;
            $total_ajustes_mes_inteiro = 0;
            for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
                 $codigo = $dados['escala'][$dia] ?? 'F';
                 $tipo_turno = $turnos_map_tipo[$codigo] ?? 'Folga';
                 $horas_turno = $turnos_map_horas[$codigo] ?? 0;
                 if ($tipo_turno === 'Trabalho') {
                    $horas_trabalhadas_mes_inteiro += $horas_turno;
                 }
                 $total_ajustes_mes_inteiro += (float)($dados['ajustes'][$dia] ?? 0);
            }
            $total_realizado_mes_inteiro = $horas_trabalhadas_mes_inteiro + $total_ajustes_mes_inteiro;
            $saldo_final_mes = $total_realizado_mes_inteiro - $meta_ajustada_mes_inteiro;
            
            
            // Guarda os resultados para a view
            if (isset($_GET['dataInicio'])) {
                 $analise_resultados[$id_militar] = [
                    'meta_ajustada' => $meta_ajustada_mes_inteiro / $dias_no_mes * ($dia_fim - $dia_inicio + 1), 
                    'total_realizado' => $total_realizado,
                    'saldo' => $total_realizado - ($meta_ajustada_mes_inteiro / $dias_no_mes * ($dia_fim - $dia_inicio + 1)), 
                    'horas_trabalhadas' => $horas_trabalhadas,
                    'total_ajustes' => $total_ajustes,
                    'horas_neutras' => $horas_neutras 
                ];
            } else {
                 $analise_resultados[$id_militar] = [
                    'meta_ajustada' => $meta_ajustada_mes_inteiro,
                    'total_realizado' => $total_realizado_mes_inteiro,
                    'saldo' => $saldo_final_mes,
                    'horas_trabalhadas' => $horas_trabalhadas_mes_inteiro,
                    'total_ajustes' => $total_ajustes_mes_inteiro,
                    'horas_neutras' => $horas_neutras
                ];
            }
        }

        // --- 4. Preparar Dados para a View ---
        $data = [
            'mes_ano_string' => $mes_ano_string,
            'mes_selecionado' => $mes_selecionado, 
            'mes_ano_sql' => $mes_ano_sql,         
            'dias_no_mes' => $dias_no_mes,
            'militares_db' => $militares_db,
            'lista_de_turnos' => $lista_de_turnos,
            'analise_resultados' => $analise_resultados,
            'dia_inicio' => $dia_inicio,
            'dia_fim' => $dia_fim
        ];

        // --- 5. Chamar a View ---
        $this->view('escala', $data);
    }
    
    
    // POST /escala/salvar
    // (Este método agora SÓ salva os turnos, o que está correto)
    public function salvar() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "escala");
            exit;
        }

        $mes_ano_sql = $_POST['mes_ano_sql']; 
        $escalas = $_POST['escala'] ?? [];
        
        foreach ($escalas as $id_militar => $dias) {
            $dias_json = json_encode($dias);
            
            // Já não salva os ajustes aqui
            $this->escalaModel->salvarEscala($id_militar, $mes_ano_sql, $dias_json);
        }
        
        $mes_param = substr($mes_ano_sql, 0, 7); 
        header("Location: " . BASE_URL . "escala?mes=" . $mes_param . "&salvo=1");
        exit;
    }

    /**
     * Função auxiliar para formatar horas, usada pela View.
     */
    public function formatarHoras($decimal_horas) {
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
?>
