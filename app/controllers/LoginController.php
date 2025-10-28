<?php
namespace App\Controllers;

use App\Core\Controller;

class LoginController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserAuth'); 
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "dashboard");
            exit;
        }
        $this->view('login');
    }

    public function processar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "login");
            exit;
        }

        $email = $_POST['email'];
        $password = $_POST['password']; 

        // 1. AUTENTICAÇÃO
        $user = $this->userModel->findUserByEmail($email);

       
        // 2. VALIDAÇÃO
        if ($user && password_verify($password, $user['password_hash'])) {
            // Senha correta!
            
            // 3. AUTORIZAÇÃO (Busca Tenants)
            $tenants = $this->userModel->findTenantsByUserId($user['id']);

            if (empty($tenants)) {
                $_SESSION['login_error'] = "Acesso negado. Sua conta não está vinculada a nenhuma organização.";
                header("Location: ". BASE_URL ."login");
                exit;
            }
            
            $_SESSION['base_user_id'] = $user['id'];
            $_SESSION['base_user_nome'] = $user['nome'];

            if (count($tenants) == 1) {
                // Caso 1: Loga direto
                $tenant = $tenants[0];
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['tenant_id'] = $tenant['tenant_id'];
                $_SESSION['tenant_nome'] = $tenant['nome_grupo'];
                $_SESSION['user_role'] = $tenant['role']; 

                header("Location: " . BASE_URL . "dashboard");
                exit;

            } else {
                // Caso 2: Manda para seleção
                $_SESSION['tenants_para_selecionar'] = $tenants;
                header("Location: " . BASE_URL . "login/selecionarTenant");
                exit;
            }

        } else {
            // Email ou senha incorretos
            $_SESSION['login_error'] = "Email ou senha inválidos.";
            header("Location: " . BASE_URL . "login");
            exit;
        }
    }

    // ... (O restante dos métodos selecionarTenant, definirTenant e logout permanecem iguais) ...

    public function selecionarTenant() {
        if (!isset($_SESSION['base_user_id']) || empty($_SESSION['tenants_para_selecionar'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }
        $data = ['tenants' => $_SESSION['tenants_para_selecionar']];
        $this->view('selecionar_tenant', $data);
    }

    public function definirTenant() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['base_user_id'])) {
             header("Location: " . BASE_URL . "login");
             exit;
        }
        $tenant_id_selecionado = (int)$_POST['tenant_id'];
        $tenant_encontrado = null;
        foreach ($_SESSION['tenants_para_selecionar'] as $tenant) {
            if ($tenant['tenant_id'] == $tenant_id_selecionado) {
                $tenant_encontrado = $tenant;
                break;
            }
        }
        if ($tenant_encontrado) {
            $_SESSION['user_id'] = $_SESSION['base_user_id'];
            $_SESSION['user_nome'] = $_SESSION['base_user_nome'];
            $_SESSION['tenant_id'] = $tenant_encontrado['tenant_id'];
            $_SESSION['tenant_nome'] = $tenant_encontrado['nome_grupo'];
            $_SESSION['user_role'] = $tenant_encontrado['role']; 
            unset($_SESSION['tenants_para_selecionar']);
            unset($_SESSION['base_user_id']);
            unset($_SESSION['base_user_nome']);
            header("Location: " . BASE_URL . "dashboard");
            exit;
        } else {
            session_destroy();
            header("Location: " . BASE_URL . "login");
            exit;
        }
    }
    
     public function logout() {
        session_destroy();
        header("Location: ". BASE_URL ."login");
        exit;
     }
}