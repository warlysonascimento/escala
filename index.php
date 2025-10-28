<?php
// Inicia a sessão (se necessário)
session_start();

// Carrega o autoloader do Composer (se você o utiliza)
// require_once 'vendor/autoload.php';

// Define a BASE_URL (importante para seus links e CSS)
define('BASE_URL', '/sistema_escala/'); // *** Verifique se esta linha está correta para você ***

// Autoloader simples para as classes do App
spl_autoload_register(function ($className) {
    
    // --- ESTA É A CORREÇÃO DEFINITIVA ---

    // 1. Converte o namespace (ex: 'App\Controllers\EscalaController')
    //    em um caminho de arquivo (ex: 'App/Controllers/EscalaController.php')
    $file_path = str_replace('\\', '/', $className) . '.php';

    // 2. Separa os diretórios (pastas) do nome do arquivo
    $parts = explode('/', $file_path);
    
    // 3. Pega o nome do arquivo (ex: 'EscalaController.php')
    $filename = array_pop($parts); 
    
    // 4. Converte todos os nomes dos diretórios para minúsculas
    //    (ex: 'App/Controllers' vira ['app', 'controllers'])
    $path_lowercase_dirs = array_map('strtolower', $parts);
    
    // 5. Remonta o caminho final correto
    //    (ex: 'app/controllers/EscalaController.php')
    $file = implode('/', $path_lowercase_dirs) . '/' . $filename;

    if (file_exists($file)) {
        require_once $file;
    }
    // --- FIM DA CORREÇÃO ---
});

// A linha 'require_once 'app/config.php';' deve ser carregada
// pelo Database.php (o que está correto).

// Inicia o Roteador
// Esta linha (32) agora deve funcionar, pois o autoloader
// conseguirá carregar todas as dependências.
$router = new App\Core\Router();
?>

