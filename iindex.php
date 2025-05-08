<?php

// Função para limpar entrada de texto (contra XSS)
function limpar_entrada($dados) {
    return trim(strip_tags($dados));
}

// Função para escapar saída HTML (contra XSS)
function escapar_saida($dados) {
    return htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
}

// Inicializa variáveis
$nome = $sobrenome = $email = $senha = $data = $telefone = $cep = $sexo = '';
$erro = [];

// Validação apenas se for método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe e sanitiza os dados via POST
    $nome      = limpar_entrada($_POST['nome'] ?? '');
    $sobrenome = limpar_entrada($_POST['sobrenome'] ?? '');
    $email     = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha     = limpar_entrada($_POST['senha'] ?? '');
    $data      = limpar_entrada($_POST['data'] ?? '');
    $telefone  = preg_replace('/[^0-9]/', '', $_POST['telefone'] ?? '');
    $cep       = preg_replace('/[^0-9]/', '', $_POST['cep'] ?? '');
    $sexo      = strtolower(substr($_POST['sexo'] ?? '', 0, 1));
    $newsletter= filter_var($_POST['newsletter'] ?? false, FILTER_VALIDATE_BOOLEAN);

    // Valida campos obrigatórios
    if (empty($nome)) $erro[] = "Nome é obrigatório.";
    if (empty($sobrenome)) $erro[] = "Sobrenome é obrigatório.";
    if (empty($email)) $erro[] = "E-mail é obrigatório.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erro[] = "E-mail inválido.";
    if (empty($senha)) $erro[] = "Senha é obrigatória.";
    if (empty($data)) $erro[] = "Data de nascimento é obrigatória.";

    // Valida data com checkdate()
    $data_parts = explode('-', $data);
    if (count($data_parts) === 3) {
        list($ano, $mes, $dia) = $data_parts;
        if (!checkdate((int)$mes, (int)$dia, (int)$ano)) {
            $erro[] = "Data de nascimento inválida.";
        }
    } else {
        $erro[] = "Formato de data inválido.";
    }

    // Se não houver erros, prossegue
    if (empty($erro)) {
        // Exemplo: gerar hash seguro da senha (não salva, só demonstra)
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Mapeia o sexo para texto legível
        $sexo_texto = [
            'm' => 'Masculino',
            'f' => 'Feminino',
        ];
        $sexo_legivel = $sexo_texto[$sexo] ?? 'Não informado';

        // Escapa os valores antes de exibir na página
        $nome_exibir      = escapar_saida($nome);
        $sobrenome_exibir = escapar_saida($sobrenome);
        $email_exibir     = escapar_saida($email);
        $data_exibir      = escapar_saida($data);
        $telefone_exibir  = escapar_saida($telefone);
        $cep_exibir       = escapar_saida($cep);
        $sexo_exibir      = escapar_saida($sexo_legivel);
        $newsletter_exibir= $newsletter ? 'Sim' : 'Não';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
</head>
<body>

<h1>Cadastro de Usuário</h1>

<?php if (!empty($erro)): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($erro as $e): ?>
                <li><?= escapar_saida($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="">
    <label>Nome: <input type="text" name="nome" value="<?= escapar_saida($nome) ?>"></label><br><br>

    <label>Sobrenome: <input type="text" name="sobrenome" value="<?= escapar_saida($sobrenome) ?>"></label><br><br>

    <label>Email: <input type="email" name="email" value="<?= escapar_saida($email) ?>"></label><br><br>

    <label>Senha: <input type="password" name="senha"></label><br><br>

    <label>Data de Nascimento: <input type="date" name="data" value="<?= escapar_saida($data) ?>"></label><br><br>

    <label>Telefone: <input type="text" name="telefone" value="<?= escapar_saida($telefone) ?>"></label><br><br>

    <label>CEP: <input type="text" name="cep" value="<?= escapar_saida($cep) ?>"></label><br><br>

    <label>Sexo:
        <select name="sexo">
            <option value="m" <?= $sexo === 'm' ? 'selected' : '' ?>>Masculino</option>
            <option value="f" <?= $sexo === 'f' ? 'selected' : '' ?>>Feminino</option>
        </select>
    </label><br><br>

    <label><input type="checkbox" name="newsletter" <?= $newsletter ? 'checked' : '' ?>> Desejo receber newsletter</label><br><br>

    <button type="submit">Enviar</button>
</form>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erro)): ?>
    <h2>Dados Cadastrados</h2>
    <ul>
        <li><strong>Nome:</strong> <?= $nome_exibir ?></li>
        <li><strong>Sobrenome:</strong> <?= $sobrenome_exibir ?></li>
        <li><strong>Email:</strong> <?= $email_exibir ?></li>
        <li><strong>Senha (hash):</strong> <?= $senha_hash ?></li>
        <li><strong>Data de Nascimento:</strong> <?= $data_exibir ?></li>
        <li><strong>Telefone:</strong> <?= $telefone_exibir ?></li>
        <li><strong>CEP:</strong> <?= $cep_exibir ?></li>
        <li><strong>Sexo:</strong> <?= $sexo_exibir ?></li>
        <li><strong>Newsletter:</strong> <?= $newsletter_exibir ?></li>
    </ul>
<?php endif; ?>

</body>
</html>