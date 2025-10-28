<?php
namespace App\Controllers;

use App\Core\Controller;

class MilitaresController extends Controller {

    private $militarModel;

    public function __construct() {
        // Protege o controller inteiro. 
        // Apenas 'admin' e 'gestor' podem acessar.
        $this->checkRole(['admin', 'gestor']); 
        
        $this->militarModel = $this->model('Militar');
    }

    /**
     * Exibe a lista de militares e o formulário de cadastro
     * Rota: GET /militares
     */
    public function index() {
        // O Model (getAllMilitares) já filtra pelo tenant_id
        $militares_do_tenant = $this->militarModel->getAllMilitares();
        
        $data = [
            'militares' => $militares_do_tenant, // Usado pela view 'militares.php'
            'militares_cadastrados' => $militares_do_tenant, // Usado pela view 'militares.php' (redundante, mas seguro)
            'tenant_nome' => $_SESSION['tenant_nome']
        ];
        
        // Carrega a view principal 'militares.php'
        $this->view('militares', $data);
    }

    /**
     * Processa o cadastro de um novo militar (do form em 'militares.php')
     * Rota: POST /militares/salvar
     */
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Envia o array $_POST inteiro para o Model
            // O Model é responsável por pegar os campos e adicionar o tenant_id
            if (!$this->militarModel->createMilitar($_POST)) {
                die("Erro ao salvar o militar.");
            }
        }
        header("Location: " . BASE_URL . "militares");
        exit;
    }
    
    /**
     * Mostra o formulário de edição para um militar específico
     * Rota: GET /militares/editar/{id}
     */
    public function editar($id) {
        // Busca o militar (Model já verifica o tenant_id)
        $militar = $this->militarModel->getById($id);
        
        // Se não achou (ou não pertence ao tenant), volta
        if (!$militar) {
            header("Location: " . BASE_URL . "militares");
            exit;
        }

        $data = [
            'militar' => $militar
        ];
        
        // Carrega a view 'militares_editar.php'
        $this->view('militares_editar', $data);
    }

    /**
     * Processa a atualização do militar (do form em 'militares_editar.php')
     * Rota: POST /militares/atualizar/{id}
     */
    public function atualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Envia o ID e o array $_POST para o Model
            // O Model é responsável por verificar o tenant_id no UPDATE
            if (!$this->militarModel->updateMilitar($id, $_POST)) {
                 die("Erro ao atualizar o militar.");
            }
        }
        header("Location: " . BASE_URL . "militares");
        exit;
    }

    /**
     * Processa a exclusão de um militar
     * Rota: GET /militares/excluir/{id}
     */
    public function excluir($id) {
        // O Model é responsável por verificar o tenant_id no DELETE
        if (!$this->militarModel->deleteMilitar($id)) {
            die("Erro ao excluir o militar.");
        }
        
        header("Location: " . BASE_URL . "militares");
        exit;
    }
}

