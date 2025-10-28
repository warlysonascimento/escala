<?php
namespace App\Models;

use App\Core\Model;

/**
 * Gerencia a tabela 'tenants' (Unidades)
 * Este Model é especial: ele NÃO filtra por tenant_id,
 * pois o admin precisa ver todos.
 */
class UnidadeModel extends Model {
    
    // O __construct() PAI já define $this->db

    /**
     * Busca TODAS as unidades do sistema
     */
    public function getAll() {
        $sql = "SELECT * FROM tenants ORDER BY nome_grupo";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Busca uma unidade específica pelo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM tenants WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Cria uma nova unidade (tenant)
     */
    public function create($data) {
        $sql = "INSERT INTO tenants (nome_grupo, codigo_grupo) 
                VALUES (:nome_grupo, :codigo_grupo)";
        
        $this->db->query($sql);
        $this->db->bind(':nome_grupo', $data['nome_grupo']);
        $this->db->bind(':codigo_grupo', $data['codigo_grupo']);
        
        return $this->db->execute();
    }

    /**
     * Atualiza uma unidade (tenant)
     */
    public function update($id, $data) {
        $sql = "UPDATE tenants 
                SET nome_grupo = :nome_grupo, codigo_grupo = :codigo_grupo
                WHERE id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':nome_grupo', $data['nome_grupo']);
        $this->db->bind(':codigo_grupo', $data['codigo_grupo']);
        
        return $this->db->execute();
    }
}
