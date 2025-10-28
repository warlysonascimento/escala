<?php
namespace App\Models;

use App\Core\Model; // Importante: estende o Model base
use PDO;

class LancamentoModel extends Model {
    
    // O __construct() PAI (em Core/Model) já define $this->db e $this->tenant_id

    /**
     * Cria um novo lançamento de ajuste (JÁ CORRIGIDO P/ MULTI-TENANT)
     */
    public function create($data) {
        if (is_null($this->tenant_id)) return false;

        try {
            $sql = "INSERT INTO lancamentos_ajustes 
                        (id_militar, data_lancamento, horas_ajuste, justificativa, tenant_id) 
                    VALUES 
                        (:id_militar, :data_lancamento, :horas_ajuste, :justificativa, :tenant_id)";
            
            $this->db->query($sql);
            
            $this->db->bind(':id_militar', $data['id_militar']);
            $this->db->bind(':data_lancamento', $data['data_lancamento']);
            $this->db->bind(':horas_ajuste', (float)$data['horas_ajuste']);
            $this->db->bind(':justificativa', $data['justificativa'] ?? null);
            $this->db->bind(':tenant_id', $this->tenant_id); // Segurança
            
            return $this->db->execute();
            
        } catch (\PDOException $e) {
            // (Opcional) Registrar $e->getMessage() em um log de erros
            return false;
        }
    }

    /**
     * Busca todos os lançamentos de um mês (JÁ CORRIGIDO P/ MULTI-TENANT)
     * Usado pela página /lancamentos
     */
    public function getByMes($mes_ano) { // $mes_ano no formato 'YYYY-MM'
        if (is_null($this->tenant_id)) return [];
        
        $sql = "SELECT 
                    la.*, 
                    m.nome AS militar_nome, 
                    m.posto AS militar_posto
                FROM 
                    lancamentos_ajustes la
                JOIN 
                    militares m ON la.id_militar = m.id
                WHERE 
                    DATE_FORMAT(la.data_lancamento, '%Y-%m') = :mes_ano
                    AND la.tenant_id = :tenant_id  -- Segurança
                ORDER BY 
                    la.data_lancamento ASC, m.nome ASC";
        
        $this->db->query($sql);
        $this->db->bind(':mes_ano', $mes_ano);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->resultSet();
    }
    
    /**
     * Busca todos os lançamentos de um militar num mês (JÁ CORRIGIDO P/ MULTI-TENANT)
     * Usado pelo EscalaController
     */
    public function getByMilitarMes($id_militar, $mes_ano_sql) { 
        if (is_null($this->tenant_id)) return [];

        $timestamp = strtotime($mes_ano_sql);
        $mes_num = date('n', $timestamp);
        $ano_num = date('Y', $timestamp);
        
        $sql = "SELECT * FROM lancamentos_ajustes 
                WHERE id_militar = :id_militar 
                AND MONTH(data_lancamento) = :mes
                AND YEAR(data_lancamento) = :ano
                AND tenant_id = :tenant_id"; // Segurança
        
        $this->db->query($sql);
        
        $this->db->bind(':id_militar', $id_militar);
        $this->db->bind(':mes', $mes_num);
        $this->db->bind(':ano', $ano_num);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->resultSet();
    }

    /**
     * Exclui um lançamento (JÁ CORRIGIDO P/ MULTI-TENANT)
     */
    public function delete($id) {
        if (is_null($this->tenant_id)) return false;

        try {
            $sql = "DELETE FROM lancamentos_ajustes 
                    WHERE id = :id AND tenant_id = :tenant_id"; // Segurança
                    
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            $this->db->bind(':tenant_id', $this->tenant_id);
            
            return $this->db->execute();
            
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    // ===================================================================
    // --- FUNÇÃO NOVA QUE ESTAVA FALTANDO ---
    // ===================================================================
    /**
     * Busca TODOS os lançamentos de TODOS os militares de um tenant em um mês.
     * Usado pelo RelatoriosController para otimização (evita N+1 query).
     *
     * @param string $mes_ano_sql (Formato 'YYYY-MM-01')
     * @return array (Formato: [id_militar => [dia => total_horas]])
     */
    public function getByTenantMes($mes_ano_sql) {
        if (is_null($this->tenant_id)) return [];

        $timestamp = strtotime($mes_ano_sql);
        $mes_num = date('n', $timestamp);
        $ano_num = date('Y', $timestamp);

        $sql = "SELECT id_militar, data_lancamento, horas_ajuste
                FROM lancamentos_ajustes
                WHERE tenant_id = :tenant_id
                AND MONTH(data_lancamento) = :mes
                AND YEAR(data_lancamento) = :ano";
        
        $this->db->query($sql);
        $this->db->bind(':tenant_id', $this->tenant_id);
        $this->db->bind(':mes', $mes_num);
        $this->db->bind(':ano', $ano_num);
        
        $lancamentos = $this->db->resultSet();
        
        // Formata o array para o Controller [id_militar => [dia => horas]]
        $mapa_ajustes = [];
        foreach ($lancamentos as $lancamento) {
            $id_militar = $lancamento['id_militar'];
            $dia = (int)date('j', strtotime($lancamento['data_lancamento']));
            $horas = (float)$lancamento['horas_ajuste'];

            if (!isset($mapa_ajustes[$id_militar])) {
                $mapa_ajustes[$id_militar] = [];
            }
            
            // Acumula horas caso haja mais de um lançamento no mesmo dia
            $mapa_ajustes[$id_militar][$dia] = ($mapa_ajustes[$id_militar][$dia] ?? 0) + $horas;
        }
        
        return $mapa_ajustes;
    }
}

