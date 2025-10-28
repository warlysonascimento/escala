<?php
namespace App\Core;

class Controller {
    
    /**
     * Carrega uma classe de Model
     */
    protected function model($modelName) {
        $modelName = ucfirst($modelName) . 'Model';
        $modelFile = __DIR__ . '/../models/' . $modelName . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            $class = "App\\Models\\" . $modelName;
            return new $class();
        } else {
            die("Model '$modelName' não encontrado.");
        }
    }

    /**
     * Carrega um arquivo de View
     * $data (opcional) torna variáveis acessíveis na view
     */
    protected function view($viewName, $data = []) {
        // Extrai o array $data em variáveis individuais
        // Ex: $data['militares'] vira a variável $militares
        extract($data);
        
        $viewFile = __DIR__ . '/../../views/' . $viewName . '.php';

        if (file_exists($viewFile)) {
            // Inicia o buffer de saída
            ob_start();
            
            // Inclui o cabeçalho
            require_once __DIR__ . '/../../views/partials/header.php';
            
            // Inclui o conteúdo da view principal
            require_once $viewFile;
            
            // Inclui o rodapé
            require_once __DIR__ . '/../../views/partials/footer.php';
            
            // Limpa e envia o buffer
            ob_end_flush();
        } else {
            die("View '$viewName' não encontrada.");
        }
    }
    
    /**
     * Verifica se o usuário está logado
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['tenant_id']);
    }

    /**
     * Verifica se o usuário tem uma das funções (roles) permitidas
     * Ex: $this->checkRole(['admin', 'gestor']);
     */
    protected function checkRole(array $roles_permitidas) {
        if (!$this->isLoggedIn()) {
            header("Location: " . BASE_URL . "login");
            exit;
        }

        if (!in_array($_SESSION['user_role'], $roles_permitidas)) {
            // Se não tem permissão, manda para o dashboard (sem acesso)
            $_SESSION['error_message'] = "Você não tem permissão para acessar esta página.";
            header("Location: " . BASE_URL . "dashboard");
            exit;
        }
        // Se chegou aqui, tem permissão
    }
}
?>