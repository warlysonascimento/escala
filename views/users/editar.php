<?php
/*
  Esta view recebe:
  $data['user'] - O usuário a ser editado
  $data['unidades'] - A lista de TODAS as unidades
  $data['user_unidades_map'] - O mapa [tenant_id => role] do usuário
*/
$user = $data['user'] ?? null;
$unidades = $data['unidades'] ?? [];
$user_unidades_map = $data['user_unidades_map'] ?? [];
if (!$user) die('Erro: Usuário não encontrado.');
?>

<style>
    /* Estilo para centralizar este form específico */
    .form-user-editar {
        max-width: 600px;
        margin: 20px auto;
    }
</style>

<h2>Gestão de Usuários</h2>
<h3>Editar Usuário: <?php echo htmlspecialchars($user['nome']); ?></h3>
        
<!-- 'form-cadastro' alterado para 'form-padrao' e classe de centralização -->
<form action="<?php echo BASE_URL; ?>users/atualizar/<?php echo $user['id']; ?>" method="POST" class="form-padrao form-user-editar">

    <h4>1. Dados Pessoais</h4>
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" 
               value="<?php echo htmlspecialchars($user['nome']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" 
               value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Nova Senha:</label>
        <input type="password" id="password" name="password" 
               placeholder="Deixe em branco para não alterar a senha">
    </div>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">
    
    <h4>2. Vínculos com Unidades e Permissões</h4>
    <p>Defina qual o nível de acesso deste usuário para cada unidade:</p>
    
    <table class="tabela-dados">
        <thead>
            <tr>
                <th>Unidade</th>
                <th>Permissão de Acesso</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($unidades as $unidade): 
                $unidade_id = $unidade['id'];
                // Verifica qual a role atual do usuário para esta unidade
                $role_atual = $user_unidades_map[$unidade_id] ?? 'nenhum';
            ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($unidade['nome_grupo']); ?></strong>
                    </td>
                    
                    <!-- === CORREÇÃO AQUI === -->
                    <!-- Adicionada a classe "form-group" ao <td> -->
                    <td class="form-group">
                        <!-- O style="width: 100%" foi removido, pois o CSS global já cuida disso -->
                        <select name="unidades[<?php echo $unidade_id; ?>]">
                    <!-- === FIM DA CORREÇÃO === -->
                    
                            <option value="nenhum" <?php echo ($role_atual == 'nenhum') ? 'selected' : ''; ?>>
                                Sem Acesso
                            </option>
                            <option value="leitor" <?php echo ($role_atual == 'leitor') ? 'selected' : ''; ?>>
                                Leitor (Pode ver relatórios)
                            </option>
                            <option value="gestor" <?php echo ($role_atual == 'gestor') ? 'selected' : ''; ?>>
                                Gestor (Pode editar escalas e militares)
                            </option>
                            <option value="admin" <?php echo ($role_atual == 'admin') ? 'selected' : ''; ?>>
                                Admin (Pode gerenciar usuários e unidades)
                            </option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        <button type="submit" class="btn-submit">Atualizar Usuário</button>
        <a href="<?php echo BASE_URL; ?>users" class="btn-cancelar">Cancelar</a>
    </div>
</form>

