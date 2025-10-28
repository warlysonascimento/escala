<?php 
// Incluir seu header padrão
// (Assumindo que você tem um `header.php` que carrega CSS, etc.)
?>
<style>
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .login-container { width: 350px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; }
    .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
    .btn-login { width: 100%; padding: 10px; background-color: #004a91; color: white; border: none; cursor: pointer; }
    .error-msg { color: red; background: #ffe0e0; border: 1px solid red; padding: 10px; margin-bottom: 15px; }
</style>

<div class="login-container">
    <h2>Login do Sistema</h2>
    <p>Acesse sua conta para gerenciar as escalas.</p>
    
    <?php 
        // Exibe mensagens de erro vindas do controller (se houver)
        if (isset($_SESSION['login_error'])) {
            echo '<div class="error-msg">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']); // Limpa o erro
        }
    ?>

    <form action="<?php echo BASE_URL; ?>login/processar" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-login">Entrar</button>
    </form>
</div>

<?php 
// Incluir seu footer padrão
?>