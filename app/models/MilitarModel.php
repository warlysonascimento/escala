<?php
namespace App\Models;

use App\Core\Model; // Importante: estende o Model com $tenant_id

class MilitarModel extends Model {
    
    // O __construct() PAI (em Core/Model) já definiu $this->db e $this->tenant_id

    /**
     * Pega todos os militares ATIVOS do tenant logado (para a escala e gestão)
     */
    public function getAllMilitares() {
        if (is_null($this->tenant_id)) return []; // Segurança

        // --- CORREÇÃO APLICADA AQUI: Adicionado 'numero' ao SELECT ---
        $sql = "SELECT id, numero, nome, posto, carga_horaria_padrao, status FROM militares 
                WHERE tenant_id = :tenant_id 
                AND status = 'ativo'
                ORDER BY nome";
        // --- FIM DA CORREÇÃO ---
        
        $this->db->query($sql);
        $this->db->bind(':tenant_id', $this->tenant_id);
        return $this->db->resultSet();
    }
    
    /**
     * Pega TODOS os militares (incluindo inativos) do tenant (para relatórios)
     */
    public function getAllMilitaresRelatorio() {
        if (is_null($this->tenant_id)) return []; // Segurança

        // --- CORREÇÃO APLICADA AQUI: Adicionado 'numero' ao SELECT ---
        $sql = "SELECT id, numero, nome, posto, carga_horaria_padrao, status FROM militares 
                WHERE tenant_id = :tenant_id 
                ORDER BY nome";
        // --- FIM DA CORREÇÃO ---
        
        $this->db->query($sql);
        $this->db->bind(':tenant_id', $this->tenant_id);
        return $this->db->resultSet();
    }
    
    /**
     * Pega um militar específico pelo ID (e verifica o tenant)
     */
    public function getById($id) {
        if (is_null($this->tenant_id)) return false;

        $sql = "SELECT * FROM militares 
                WHERE id = :id AND tenant_id = :tenant_id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->single();
    }

    /**
     * Cria um novo militar DENTRO do tenant logado
     * $data é o array $_POST vindo do controller
     */
    public function createMilitar($data) {
        if (is_null($this->tenant_id)) return false;

        $sql = "INSERT INTO militares 
                    (numero, nome, posto, carga_horaria_padrao, status, tenant_id) 
                VALUES 
                    (:numero, :nome, :posto, :carga_horaria_padrao, 'ativo', :tenant_id)";
        
        $this->db->query($sql);
        // Usa 'numero' como chave, que é o 'name' do input no form
        $this->db->bind(':numero', $data['numero']); 
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':posto', $data['posto']);
        // Usa 'carga_horaria_padrao' como chave
        $this->db->bind(':carga_horaria_padrao', (int)$data['carga_horaria_padrao']); 
        $this->db->bind(':tenant_id', $this->tenant_id); // Inserção automática do tenant
        
        return $this->db->execute();
    }

    /**
     * Atualiza um militar (verificando se ele pertence ao tenant logado)
     * $data é o array $_POST vindo do controller
     */
    public function updateMilitar($id, $data) {
        if (is_null($this->tenant_id)) return false;

        $sql = "UPDATE militares SET 
                    numero = :numero, 
                    nome = :nome, 
                    posto = :posto, 
                    carga_horaria_padrao = :carga_horaria_padrao, 
                    status = :status
                WHERE 
                    id = :id AND tenant_id = :tenant_id"; // Segurança
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        // Usa as chaves corretas do $_POST (como no form 'militares_editar.php')
        $this->db->bind(':numero', $data['numero']); 
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':posto', $data['posto']);
        $this->db->bind(':carga_horaria_padrao', (int)$data['carga_horaria_padrao']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->execute();
    }

    /**
     * Deleta um militar (verificando se ele pertence ao tenant logado)
     */
    public function deleteMilitar($id) {
        if (is_null($this->tenant_id)) return false;

        // --- Adicionado: Deletar escalas associadas ANTES de deletar o militar ---
        $sql_delete_escalas = "DELETE FROM escalas_mensais WHERE id_militar = :id_militar AND tenant_id = :tenant_id";
        $this->db->query($sql_delete_escalas);
        $this->db->bind(':id_militar', $id);
        $this->db->bind(':tenant_id', $this->tenant_id);
        $this->db->execute(); // Executa a exclusão das escalas

        // --- Adicionado: Deletar lançamentos associados ANTES de deletar o militar ---
        $sql_delete_lancamentos = "DELETE FROM lancamentos_ajustes WHERE id_militar = :id_militar AND tenant_id = :tenant_id";
        $this->db->query($sql_delete_lancamentos);
        $this->db->bind(':id_militar', $id);
        $this->db->bind(':tenant_id', $this->tenant_id);
        $this->db->execute(); // Executa a exclusão dos lançamentos
        // --- FIM DAS ADIÇÕES ---

        // Agora deleta o militar
        $sql = "DELETE FROM militares WHERE id = :id AND tenant_id = :tenant_id"; // Segurança
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->execute();
    }
}

