<?php
namespace App\Core;

use PDO;
use PDOException;

/*
 * Classe de Banco de Dados (Singleton)
 */
class Database {
    
    private static $instance = null;
    
    private $dbh; // Database Handler (A conexão PDO)
    private $stmt; // Statement (A consulta preparada)
    private $error; // Para guardar erros

    /**
     * O Construtor é privado para impedir 'new Database()'
     */
    private function __construct() {
        
        // 1. Carrega o arquivo de configuração
        // Este arquivo define $db_host, $db_name, $db_user, $db_pass
        require_once dirname(dirname(__FILE__)) . '/config.php';
        
        // 2. --- CORREÇÃO APLICADA AQUI ---
        // Usa as VARIÁVEIS do config.php (em vez de constantes)
        $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4';
        
        $options = [
            PDO::ATTR_PERSISTENT => true, 
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
            PDO::ATTR_EMULATE_PREPARES => false, 
        ];

        try {
            // 3. Usa as VARIÁVEIS aqui também
            $this->dbh = new PDO($dsn, $db_user, $db_pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Erro de Conexão com o Banco de Dados: " . $this->error;
            die();
        }
    }

    /**
     * Método estático que pega a instância única (Padrão Singleton)
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Prepara a consulta SQL
     */
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Vincula (bind) os valores à consulta preparada
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Executa a consulta preparada
     */
    public function execute() {
        try {
            return $this->stmt->execute();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Erro ao executar consulta: " . $this->error;
            return false; 
        }
    }

    /**
     * Pega todos os resultados (array de arrays)
     */
    public function resultSet() {
        $this->execute(); 
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    /**
     * Pega um único resultado (um array)
     */
    public function single() {
        $this->execute(); 
        return $this->stmt->fetch(PDO::FETCH_ASSOC); 
    }

    /**
     * Pega a contagem de linhas afetadas
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
}
?>