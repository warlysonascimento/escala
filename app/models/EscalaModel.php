<?php
namespace App\Models;

use App\Core\Model; // Importante: Estende o Model base

class EscalaModel extends Model {
    
    // O __construct() PAI (em Core/Model) já define $this->db e $this->tenant_id

    /**
     * Pega a escala processada do mês PARA O TENANT LOGADO.
     */
    public function getEscalaDoMes($mes_ano_sql) {
        
        if (is_null($this->tenant_id)) {
            return []; 
        }

        // 1. Busca todos os militares do tenant
        $militares = $this->getMilitaresDoTenant();
        if (empty($militares)) {
            return []; 
        }

        // 2. Busca os dias de escala salvos no banco
        $escalas_db = $this->getEscalasSqlDoMes($mes_ano_sql);
        
        $dias_no_mes = (int)date('t', strtotime($mes_ano_sql));
        
        // 3. Monta o array final
        $militares_formatado = [];

        foreach ($militares as $militar) {
            $id_militar = $militar['id'];
            
            $militares_formatado[$id_militar] = [
                'id' => $id_militar,
                'nome' => $militar['nome'],
                'carga_padrao' => $militar['carga_horaria_padrao'], 
                'escala' => [],
                'ajustes' => [] // O controller irá popular isso
            ];

            // Inicializa a escala com 'F' (Folga)
            for ($d = 1; $d <= $dias_no_mes; $d++) {
                $militares_formatado[$id_militar]['escala'][$d] = 'F'; // 'F' como padrão
            }
        }
        
        // 4. PREENCHIMENTO ATUALIZADO PARA LER O JSON
        // Itera sobre os registros (1 por militar) vindos do banco
        foreach ($escalas_db as $escala_row) {
            $id_militar = $escala_row['id_militar'];
            
            // Verifica se o militar desse registro ainda está ativo e na lista
            if (isset($militares_formatado[$id_militar])) {
                
                // Decodifica o JSON que vem do banco
                $dias_do_json = json_decode($escala_row['dias_json'], true); // 'true' para array associativo

                if (is_array($dias_do_json)) {
                    // Itera sobre os dias salvos no JSON (ex: "1": "S1", "2": "S2")
                    foreach ($dias_do_json as $dia => $turno_codigo) {
                        $dia_int = (int)$dia;
                        
                        // Atualiza o dia correspondente na escala,
                        // apenas se o dia existir no array (segurança)
                        if (isset($militares_formatado[$id_militar]['escala'][$dia_int])) {
                            $militares_formatado[$id_militar]['escala'][$dia_int] = $turno_codigo;
                        }
                    }
                }
            }
        }

        return $militares_formatado;
    }

    /**
     * Função auxiliar que busca os militares do tenant
     */
    private function getMilitaresDoTenant() {
        
        $sql = "SELECT id, nome, carga_horaria_padrao 
                FROM militares 
                WHERE tenant_id = :tenant_id 
                AND status = 'ativo' -- Boa prática: buscar apenas militares ativos
                ORDER BY nome";
                
        $this->db->query($sql);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->resultSet();
    }

    /**
     * Função auxiliar que busca os dados brutos da escala do mês
     */
    public function getEscalasSqlDoMes($mes_ano_sql) {
        
        $sql = "SELECT id_militar, dias_json 
                FROM escalas_mensais
                WHERE mes_ano = :mes_ano 
                AND tenant_id = :tenant_id"; 

        $this->db->query($sql); 
        
        $this->db->bind(':mes_ano', $mes_ano_sql);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->resultSet();
    }
    
    /**
     * --- MÉTODO CRÍTICO ADICIONADO ---
     * Salva (ou atualiza) a escala de um militar para um mês específico.
     * Usa REPLACE INTO (baseado na chave UNIQUE `escala_unica` da sua tabela)
     */
    public function salvarEscala($id_militar, $mes_ano_sql, $dias_json) {
        if (is_null($this->tenant_id)) {
            return false; // Segurança
        }
        
        // Garantimos a segurança checando o tenant_id do militar no SELECT
        $sql = "REPLACE INTO escalas_mensais (id_militar, mes_ano, dias_json, tenant_id)
                SELECT 
                    :id_militar, 
                    :mes_ano, 
                    :dias_json,
                    :tenant_id
                FROM militares m
                WHERE m.id = :id_militar_check AND m.tenant_id = :tenant_id_check";

        $this->db->query($sql);
        
        $this->db->bind(':id_militar', $id_militar);
        $this->db->bind(':mes_ano', $mes_ano_sql);
        $this->db->bind(':dias_json', $dias_json);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        // Binds para a verificação de segurança no SELECT
        $this->db->bind(':id_militar_check', $id_militar);
        $this->db->bind(':tenant_id_check', $this->tenant_id);

        return $this->db->execute();
    }
}

