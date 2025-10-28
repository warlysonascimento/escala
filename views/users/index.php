<?php
/*
  Esta view recebe $data['users'] do UsersController
*/
?>

<h2>Gestão de Usuários</h2>
<p>
    <a href="<?php echo BASE_URL; ?>users/criar" style="background-color: #004a91; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">
        + Criar Novo Usuário
    </a>
</p>

<table class="tabela-dados" style="margin-top: 20px;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['users'] as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['nome']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>users/editar/<?php echo $user['id']; ?>">
                        Editar
                    </a> | 
                    <a href="<?php echo BASE_URL; ?>users/excluir/<?php echo $user['id']; ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir <?php echo htmlspecialchars($user['nome']); ?>?');">
                        Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        
        <?php if (empty($data['users'])): ?>
            <tr>
                <td colspan="4">Nenhum usuário cadastrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
