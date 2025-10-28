<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {

    // Adicionado para buscar dados
    private $militarModel;

    public function __construct() {
        // Protege o controller - só acessa quem está logado
        $this->checkRole(['admin', 'gestor', 'leitor']); 
        
        // Carrega o model
        $this->militarModel = $this->model('Militar');
    }

    public function index() {
        // Busca o número de militares ativos
        $militaresAtivos = $this->militarModel->getAllMilitares();
        $totalMilitaresAtivos = count($militaresAtivos);
        
        // Pega o mês/ano atual para link direto da escala
        $mesAtual = date('Y-m');

        // Prepara os dados para a view
        $data = [
            'totalMilitaresAtivos' => $totalMilitaresAtivos,
            'mesAtual' => $mesAtual,
            'userNome' => $_SESSION['user_nome'] ?? 'Usuário', // Nome do usuário logado
            'unidadeNome' => $_SESSION['tenant_nome'] ?? 'Unidade' // Nome da unidade
        ];
        
        // Carrega a nova view do dashboard
        $this->view('dashboard/index', $data); 
    }
}

