<?php
namespace App\Models;

use App\Core\Model;

/**
 * Gerencia a tabela 'users' e 'user_tenants'
 * Este Model é especial: ele NÃO filtra por tenant_id,
 * pois o admin precisa ver todos os usuários.
 */
class UserModel extends Model {
    
    // O __construct() PAI já define $this->db

    /**
     * Busca TODOS os usuários do sistema
     */
    public function getAll() {
        $sql = "SELECT id, nome, email, criado_em FROM users ORDER BY nome";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /**
     * Busca um usuário específico pelo ID
     */
    public function getById($id) {
        $sql = "SELECT id, nome, email FROM users WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Busca as permissões (vínculos) de um usuário
     * Retorna um MAPA [tenant_id => role] para a view de edição
     */
    public function getUnidadesMapByUserId($user_id) {
        $sql = "SELECT tenant_id, role FROM user_tenants WHERE user_id = :user_id";
        $this->db->query($sql);
        $this->db->bind(':user_id', $user_id);
        
        $results = $this->db->resultSet();
        
        // Converte o resultado em um mapa
        $map = [];
        foreach ($results as $row) {
            $map[$row['tenant_id']] = $row['role'];
        }
        return $map;
    }

    /**
     * Cria um novo usuário
     */
    public function create($data) {
        $sql = "INSERT INTO users (nome, email, password_hash) 
                VALUES (:nome, :email, :password_hash)";
        
        $this->db->query($sql);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password_hash', $data['password_hash']);
        
        return $this->db->execute();
    }

    /**
     * Atualiza um usuário e seus vínculos com as unidades
     */
    public function update($id, $data_user, $data_unidades) {
        // (Idealmente, isso seria uma transação, mas 
        // faremos em duas etapas por simplicidade)

        // Etapa 1: Atualizar os dados do usuário (nome, email, senha opcional)
        $sql_user = "UPDATE users SET nome = :nome, email = :email";
        
        // Se uma nova senha foi fornecida, adiciona ao SQL
        if (!empty($data_user['password'])) {
            $data_user['password_hash'] = password_hash($data_user['password'], PASSWORD_DEFAULT);
            $sql_user .= ", password_hash = :password_hash";
        }
        
        $sql_user .= " WHERE id = :id";
        
        $this->db->query($sql_user);
        $this->db->bind(':nome', $data_user['nome']);
        $this->db->bind(':email', $data_user['email']);
        $this->db->bind(':id', $id);
        if (!empty($data_user['password_hash'])) {
            $this->db->bind(':password_hash', $data_user['password_hash']);
        }
        
        // Tenta executar a primeira atualização
        if (!$this->db->execute()) {
            return false; // Falha (ex: email duplicado)
        }

        // Etapa 2: Atualizar os vínculos (Permissões)
        // 2.1. Apaga todos os vínculos antigos deste usuário
        $sql_delete_vinculos = "DELETE FROM user_tenants WHERE user_id = :user_id";
        $this->db->query($sql_delete_vinculos);
        $this->db->bind(':user_id', $id);
        $this->db->execute();

        // 2.2. Insere os novos vínculos
        $sql_insert_vinculo = "INSERT INTO user_tenants (user_id, tenant_id, role) 
                               VALUES (:user_id, :tenant_id, :role)";
        
        foreach ($data_unidades as $tenant_id => $role) {
            // Se o admin selecionou "Sem Acesso", o $role será 'nenhum'.
            // Nós simplesmente ignoramos esse caso e não inserimos o vínculo.
            if ($role !== 'nenhum') {
                $this->db->query($sql_insert_vinculo);
                $this->db->bind(':user_id', $id);
                $this->db->bind(':tenant_id', (int)$tenant_id);
                $this->db->bind(':role', $role);
                $this->db->execute();
            }
        }
        
        return true;
    }

    /**
     * Exclui um usuário
     */
    public function delete($id) {
        // (Também deveria ser uma transação)
        
        // 1. Exclui os vínculos
        $sql_delete_vinculos = "DELETE FROM user_tenants WHERE user_id = :user_id";
        $this->db->query($sql_delete_vinculos);
        $this->db->bind(':user_id', $id);
        $this->db->execute();
        
        // 2. Exclui o usuário
        $sql_delete_user = "DELETE FROM users WHERE id = :id";
        $this->db->query($sql_delete_user);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
}
