<?php
namespace App\Models;

use App\Core\Database; 

class UserAuthModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); 
    }

    /**
     * Encontra um usuário pelo email (Tabela 'users')
     */
    public function findUserByEmail($email) {
        // Linha 18: Agora a 'query()' só vai preparar, não executar.
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        
        // A função single() abaixo irá chamar execute() (corretamente)
        return $this->db->single();
    }

    /**
     * Encontra todos os tenants/cidades aos quais um usuário pertence
     * (Tabelas 'user_tenants' e 'tenants')
     */
    public function findTenantsByUserId($user_id) {
        $sql = "SELECT 
                    t.id AS tenant_id, 
                    t.nome_grupo, 
                    t.codigo_grupo, 
                    ut.role 
                FROM user_tenants ut
                JOIN tenants t ON ut.tenant_id = t.id
                WHERE ut.user_id = :user_id";
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $user_id);

        // A função resultSet() abaixo irá chamar execute() (corretamente)
        return $this->db->resultSet();
    }
}