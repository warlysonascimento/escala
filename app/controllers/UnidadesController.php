<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * Gerencia o cadastro das Unidades (Tenants)
 * Somente 'admin' pode acessar.
 */
class UnidadesController extends Controller {

    private $unidadeModel;

    public function __construct() {
        // Protege o controller inteiro. 
        // Apenas 'admin' pode gerenciar unidades.
        $this->checkRole(['admin']); 
        
        // Carrega o novo model que vamos criar
        $this->unidadeModel = $this->model('Unidade');
    }

    /**
     * Exibe a lista de unidades e o formulário de cadastro
     * Rota: GET /unidades
     */
    public function index() {
        $data = [
            'unidades' => $this->unidadeModel->getAll()
        ];
        $this->view('unidades/index', $data);
    }

    /**
     * Salva uma nova unidade
     * Rota: POST /unidades/salvar
     */
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'nome_grupo' => $_POST['nome_grupo'] ?? '',
                'codigo_grupo' => $_POST['codigo_grupo'] ?? ''
            ];

            if (empty($dados['nome_grupo']) || empty($dados['codigo_grupo'])) {
                // Idealmente, usaríamos uma session flash de erro
                die("Erro: Nome e Código são obrigatórios.");
            }

            if ($this->unidadeModel->create($dados)) {
                header("Location: ". BASE_URL ."unidades");
                exit;
            } else {
                die("Erro ao salvar a unidade.");
            }
        }
    }

    /**
     * Exibe o formulário de edição de uma unidade
     * Rota: GET /unidades/editar/{id}
     */
    public function editar($id) {
        $unidade = $this->unidadeModel->getById($id);

        if (!$unidade) {
            header("Location: ". BASE_URL ."unidades");
            exit;
        }

        $data = [
            'unidade' => $unidade
        ];
        
        $this->view('unidades/editar', $data);
    }

    /**
     * Atualiza uma unidade
     * Rota: POST /unidades/atualizar/{id}
     */
    public function atualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'nome_grupo' => $_POST['nome_grupo'] ?? '',
                'codigo_grupo' => $_POST['codigo_grupo'] ?? ''
            ];

            if (empty($dados['nome_grupo']) || empty($dados['codigo_grupo'])) {
                die("Erro: Nome e Código são obrigatórios.");
            }

            if ($this->unidadeModel->update($id, $dados)) {
                header("Location: ". BASE_URL ."unidades");
                exit;
            } else {
                die("Erro ao atualizar a unidade.");
            }
        }
    }
    
    // Nota: A exclusão (delete) de tenants é perigosa (apaga dados em cascata)
    // Por segurança, não foi implementada.
}
