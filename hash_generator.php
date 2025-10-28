<?php
// hash_generator.php

$senha_para_hashear = '123456';

// Gera um novo hash usando o algoritmo padrão (bcrypt) da SUA instalação do PHP
$novo_hash = password_hash($senha_para_hashear, PASSWORD_DEFAULT);

echo "<h1>Seu Novo Hash</h1>";
echo "<p>Senha: " . $senha_para_hashear . "</p>";
echo "<p>Copie este hash completo (incluindo o $):</p>";
echo "<h3 style='background: #eee; padding: 10px; border: 1px solid #000;'>";
echo $novo_hash;
echo "</h3>";
?>