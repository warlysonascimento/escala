<?php
namespace App\Controllers;

use App\Core\Controller;

class TurnosController extends Controller {
    
    private $turnoModel;

    public function __construct() {
        $this->turnoModel = $this->model('Turno');
    }

    // GET /turnos
    public function index() {
        $turnos = $this->turnoModel->getAll();
        
        $data = [
            'turnos_cadastrados' => $turnos
        ];
        
        $this->view('turnos', $data);
    }

    // POST /turnos/salvar
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->turnoModel->create($_POST)) {
                header("Location: " . BASE_URL . "turnos");
            } else {
                die("Erro ao salvar turno.");
            }
        } else {
            header("Location: " . BASE_URL . "turnos");
        }
    }
    
    /* --- NOVOS MÉTODOS ABAIXO --- */

    /**
     * Carrega a página de edição de um turno.
     * Rota: GET /turnos/editar/{id}
     */
    public function editar($id) {
        // 1. Busca os dados do turno no Model
        $turno = $this->turnoModel->getById($id);
        
        if (!$turno) {
            header("Location: " . BASE_URL . "turnos");
            exit;
        }
        
        // 2. Prepara os dados para a View
        $data = [
            'turno' => $turno
        ];
        
        // 3. Chama a nova View 'turnos_editar'
        $this->view('turnos_editar', $data);
    }

    /**
     * Processa o formulário de atualização de um turno.
     * Rota: POST /turnos/atualizar/{id}
     */
     
    // --- AQUI ESTÁ A CORREÇÃO ---
    public function atualizar($id) {
    // --- FIM DA CORREÇÃO ---
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Pega os dados do POST
            $dadosForm = $_POST;
            
            // 2. Manda o Model atualizar
            if ($this->turnoModel->update($id, $dadosForm)) {
                // 3. Redireciona em caso de sucesso
                header("Location: " . BASE_URL . "turnos");
            } else {
                // 4. Trata o erro
                die("Erro ao atualizar o tipo de turno.");
            }
        } else {
            header("Location: " . BASE_URL . "turnos");
        }
    }

    /**
     * Processa a exclusão de um turno.
     * Rota: GET /turnos/excluir/{id}
     */
    public function excluir($id) {
        if ($this->turnoModel->delete($id)) {
            // Redireciona de volta para a lista
            header("Location: " . BASE_URL . "turnos");
        } else {
            die("Erro ao excluir o tipo de turno.");
        }
    }
}
?>