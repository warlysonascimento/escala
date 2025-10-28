<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * Gerencia o cadastro de Usuários e suas permissões
 * Somente 'admin' pode acessar.
 */
class UsersController extends Controller {

    private $userModel;
    private $unidadeModel; // Precisamos dele para listar as unidades

    public function __construct() {
        // Protege o controller inteiro. 
        $this->checkRole(['admin']); 
        
        $this->userModel = $this->model('User');
        $this->unidadeModel = $this->model('Unidade'); // Model que criamos na etapa anterior
    }

    /**
     * Exibe a lista de usuários
     * Rota: GET /users
     */
    public function index() {
        $data = [
            'users' => $this->userModel->getAll()
        ];
        $this->view('users/index', $data);
    }

    /**
     * Exibe o formulário de criação
     * Rota: GET /users/criar
     */
    public function criar() {
        $this->view('users/criar');
    }

    /**
     * Salva um novo usuário
     * Rota: POST /users/salvar
     */
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];

            if (empty($dados['nome']) || empty($dados['email']) || empty($dados['password'])) {
                die("Erro: Nome, Email e Senha são obrigatórios para criar.");
            }
            
            // Hash da senha
            $dados['password_hash'] = password_hash($dados['password'], PASSWORD_DEFAULT);

            if ($this->userModel->create($dados)) {
                header("Location: ". BASE_URL ."users");
                exit;
            } else {
                die("Erro ao salvar o usuário. O email já pode estar em uso.");
            }
        }
    }

    /**
     * Exibe o formulário de edição de um usuário e suas permissões
     * Rota: GET /users/editar/{id}
     */
    public function editar($id) {
        $user = $this->userModel->getById($id);
        if (!$user) {
            header("Location: ". BASE_URL ."users");
            exit;
        }

        $data = [
            'user' => $user,
            // Busca todas as unidades para o formulário
            'unidades' => $this->unidadeModel->getAll(),
            // Busca as unidades/roles que o usuário já possui
            'user_unidades_map' => $this->userModel->getUnidadesMapByUserId($id)
        ];
        
        $this->view('users/editar', $data);
    }

    /**
     * Atualiza um usuário e suas permissões
     * Rota: POST /users/atualizar/{id}
     */
    public function atualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados_user = [
                'nome' => $_POST['nome'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '' // Senha (opcional)
            ];
            
            // 1. Pega o mapa de permissões do formulário
            // Ex: [ 'unidade_id_1' => 'leitor', 'unidade_id_2' => 'admin', 'unidade_id_3' => 'nenhum' ]
            $dados_unidades = $_POST['unidades'] ?? [];

            if (empty($dados_user['nome']) || empty($dados_user['email'])) {
                die("Erro: Nome e Email são obrigatórios.");
            }

            // 2. Chama o Model para fazer a atualização (que é complexa)
            if ($this->userModel->update($id, $dados_user, $dados_unidades)) {
                header("Location: ". BASE_URL ."users");
                exit;
            } else {
                die("Erro ao atualizar o usuário.");
            }
        }
    }

    /**
     * Exclui um usuário (e seus vínculos)
     * Rota: GET /users/excluir/{id}
     */
    public function excluir($id) {
        // Não permita que o usuário se auto-exclua
        if ($id == $_SESSION['user_id']) {
            die("Erro: Você não pode excluir a si mesmo.");
        }

        if ($this->userModel->delete($id)) {
            header("Location: ". BASE_URL ."users");
            exit;
        } else {
            die("Erro ao excluir o usuário.");
        }
    }
}
