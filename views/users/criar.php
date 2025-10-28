<style>
    /* Limita a largura do formulário para melhor legibilidade */
    .form-cadastro-user {
        max-width: 500px;
        margin: 20px auto; /* Centraliza o formulário */
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 25px;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    /* Garante que os inputs ocupem 100% da largura do form */
    .form-cadastro-user .form-group input[type="text"],
    .form-cadastro-user .form-group input[type="email"],
    .form-cadastro-user .form-group input[type="password"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Importante para o padding não estourar */
    }

    /* Estilo do botão Salvar */
    .btn-salvar {
        background-color: #004a91;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
    }
    .btn-salvar:hover {
        background-color: #003a71;
    }

    /* Estilo do botão Cancelar */
    .btn-cancelar {
        display: inline-block;
        padding: 12px 20px;
        margin-left: 10px;
        background-color: #f4f4f4;
        color: #555;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        font-size: 16px;
    }
    .btn-cancelar:hover {
        background-color: #e9e9e9;
    }
</style>

<h2>Gestão de Usuários</h2>
<h3>Criar Novo Usuário</h3>
        
<!-- Adicionada a nova classe CSS ao formulário -->
<form action="<?php echo BASE_URL; ?>users/salvar" method="POST" class="form-cadastro-user">

    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <button type="submit" class="btn-salvar">Salvar Usuário</button>
    <a href="<?php echo BASE_URL; ?>users" class="btn-cancelar">Cancelar</a>
</form>

