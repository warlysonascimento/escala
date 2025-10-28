<?php
namespace App\Models;

use App\Core\Model; // Garante que está estendendo o Model base
use PDO;

class TurnoModel extends Model {
    
    // O __construct() PAI (em Core/Model) já define $this->db e $this->tenant_id

    /**
     * Pega todos os turnos cadastrados APENAS para o tenant logado.
     */
    public function getAll() {
        if (is_null($this->tenant_id)) {
            return []; 
        }

        $sql = "SELECT * FROM tipos_turno 
                WHERE tenant_id = :tenant_id 
                ORDER BY codigo";
                
        $this->db->query($sql);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->resultSet();
    }

    /**
     * Busca um único turno pelo ID, verificando o tenant.
     */
    public function getById($id) {
        if (is_null($this->tenant_id)) {
            return false; 
        }

        $sql = "SELECT * FROM tipos_turno 
                WHERE id = :id AND tenant_id = :tenant_id";
                
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->single();
    }

    /**
     * Gera o 'map' de Horas (Código => Horas)
     * Usado pelo EscalaController.
     */
    public function getTurnoMap() {
        $turnos = $this->getAll(); 
        $map = [];

        foreach ($turnos as $turno) {
            $map[$turno['codigo']] = (float)$turno['duracao_horas'];
        }
        
        // Garante valores padrão para códigos comuns, caso não cadastrados
        if (!isset($map['F'])) $map['F'] = 0; // Folga
        
        return $map;
    }

    /**
     * Gera o 'map' de Tipos (Código => Tipo)
     * Usado pelo EscalaController.
     */
    public function getTurnoTypeMap() {
        $turnos = $this->getAll(); 
        $map = [];

        foreach ($turnos as $turno) {
            $map[$turno['codigo']] = $turno['tipo'];
        }
        
        // Garante valores padrão para códigos comuns, caso não cadastrados
        if (!isset($map['F'])) $map['F'] = 'Folga';
        
        return $map;
    }

    /**
     * Cria um novo tipo de turno.
     * $data é o array vindo do $_POST.
     */
    public function create($data) {
        if (is_null($this->tenant_id)) return false;

        $sql = "INSERT INTO tipos_turno (codigo, descricao, duracao_horas, tipo, tenant_id)
                VALUES (:codigo, :descricao, :duracao_horas, :tipo, :tenant_id)";
        
        $this->db->query($sql);
        $this->db->bind(':codigo', $data['codigo']);
        $this->db->bind(':descricao', $data['descricao']);
        $this->db->bind(':duracao_horas', (float)$data['duracao_horas']);
        $this->db->bind(':tipo', $data['tipo']);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->execute();
    }

    /**
     * Atualiza um tipo de turno.
     * $data é o array vindo do $_POST.
     */
    public function update($id, $data) {
        if (is_null($this->tenant_id)) return false;

        $sql = "UPDATE tipos_turno 
                SET codigo = :codigo, 
                    descricao = :descricao, 
                    duracao_horas = :duracao_horas, 
                    tipo = :tipo
                WHERE id = :id AND tenant_id = :tenant_id"; // Segurança
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':codigo', $data['codigo']);
        $this->db->bind(':descricao', $data['descricao']);
        $this->db->bind(':duracao_horas', (float)$data['duracao_horas']);
        $this->db->bind(':tipo', $data['tipo']);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->execute();
    }

    /**
     * Deleta um tipo de turno.
     */
    public function delete($id) {
        if (is_null($this->tenant_id)) return false;

        $sql = "DELETE FROM tipos_turno 
                WHERE id = :id AND tenant_id = :tenant_id"; // Segurança
        
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':tenant_id', $this->tenant_id);
        
        return $this->db->execute();
    }
}

