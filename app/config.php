<?php
/* --- Configuração do Banco de Dados --- */
// Estas variáveis serão lidas pelo app/core/Database.php
$db_host = 'localhost';      // Geralmente 'localhost'
$db_name = 'sistema_escala'; // O nome do banco que você criou
$db_user = 'root';           // Usuário padrão do XAMPP
$db_pass = '';               // Senha padrão do XAMPP (em branco)

/* --- Configurações do Sistema --- */
// Definir o fuso horário para garantir que as datas estejam corretas
date_default_timezone_set('America/Sao_Paulo');
?>