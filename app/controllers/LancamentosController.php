<?php
namespace App\Controllers;

use App\Core\Controller;

class LancamentosController extends Controller {
    
    private $lancamentoModel;
    private $militarModel;

    public function __construct() {
        // --- Adicionado: Proteção para garantir que o usuário está logado ---
        $this->checkRole(['admin', 'gestor', 'leitor']); // Permite todos os logados
        // --- Fim da Adição ---
        
        $this->lancamentoModel = $this->model('Lancamento');
        $this->militarModel = $this->model('Militar');
    }

    /**
     * Exibe o formulário e a lista de lançamentos do mês
     * Rota: GET /lancamentos
     */
    public function index() {
        // Define o mês (pode vir da URL ou ser o atual)
        $mes_selecionado = $_GET['mes'] ?? date('Y-m');
        
        // 1. Busca os lançamentos existentes para este mês
        $lancamentos = $this->lancamentoModel->getByMes($mes_selecionado);
        
        // --- CORREÇÃO APLICADA AQUI (Linha 28) ---
        // 2. Busca todos os militares ATIVOS para preencher o <select>
        // Alterado de getAll() para getAllMilitares()
        $militares = $this->militarModel->getAllMilitares(); 
        // --- FIM DA CORREÇÃO ---
        
        $data = [
            'mes_selecionado' => $mes_selecionado,
            'lancamentos_do_mes' => $lancamentos,
            'lista_militares' => $militares
        ];
        
        $this->view('lancamentos', $data);
    }

    /**
     * Salva um novo lançamento
     * Rota: POST /lancamentos/salvar
     */
    public function salvar() {
        // --- Adicionado: Proteção para garantir que só gestor/admin pode salvar ---
        $this->checkRole(['admin', 'gestor']);
        // --- Fim da Adição ---

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Pega o mês do formulário para redirecionar de volta
            $mes_selecionado = date('Y-m', strtotime($_POST['data_lancamento']));
            
            // Passa os dados do POST para o Model
            if ($this->lancamentoModel->create($_POST)) {
                // Sucesso
            } else {
                // Erro
                die("Erro ao salvar lançamento.");
            }
            
            // Redireciona de volta para a página de lançamentos daquele mês
            header("Location: " . BASE_URL . "lancamentos?mes=" . $mes_selecionado);
            exit; // Adicionado exit após header
        } else {
            header("Location: " . BASE_URL . "lancamentos");
            exit; // Adicionado exit após header
        }
    }
    
    /**
     * Exclui um lançamento
     * Rota: GET /lancamentos/excluir/{id}
     */
    public function excluir($id) {
        // --- Adicionado: Proteção para garantir que só gestor/admin pode excluir ---
        $this->checkRole(['admin', 'gestor']);
        // --- Fim da Adição ---

        // Pega o mês da URL para redirecionar de volta (melhoria)
        $mes_selecionado = $_GET['mes'] ?? date('Y-m');

        if ($this->lancamentoModel->delete($id)) {
            // Sucesso
        } else {
            // Erro
            die("Erro ao excluir lançamento.");
        }
        // Redireciona de volta para a página de lançamentos daquele mês
        header("Location: " . BASE_URL . "lancamentos?mes=" . $mes_selecionado);
        exit; // Adicionado exit após header
    }
}
