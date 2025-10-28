<?php
namespace App\Core;

// Importe a classe Database
use App\Core\Database;
use PDO;

class Model {

    // Propriedades que todos os Models filhos (Escala, Militar, etc.) herdarão
    protected $db;
    protected $tenant_id;

    public function __construct() {
        
        // --- CORREÇÃO ESTÁ AQUI ---
        // 1. Pega a instância do Banco de Dados Singleton
        $this->db = Database::getInstance();
        
        // -------------------------

        // 2. Pega o tenant_id da sessão
        if (isset($_SESSION['tenant_id'])) {
            $this->tenant_id = (int)$_SESSION['tenant_id'];
        } else {
            // Se o usuário não está logado (sem tenant), define como nulo
            $this->tenant_id = null;
            
            // Segurança: Se um model (exceto UserAuth) for chamado 
            // sem um tenant, algo está errado.
            // (Opcional, mas recomendado):
            // if (get_class($this) != 'App\Models\UserAuthModel') {
            //     die('Erro: Tentativa de acessar um Model de gestão sem estar logado em um tenant.');
            // }
        }
    }
}