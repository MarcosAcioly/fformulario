<?php
function validar_e_processar(array $dados): array
{
    $saida = [];
    $erros = [];

    // Limpeza de entrada
    $saida['nome'] = trim(strip_tags($dados['nome'] ?? ''));
    $saida['sobrenome'] = trim(strip_tags($dados['sobrenome'] ?? ''));
    $saida['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $saida['senha'] = trim(strip_tags($dados['senha'] ?? ''));
    $saida['data'] = trim(strip_tags($dados['data'] ?? ''));
    $saida['telefone'] = preg_replace('/[^0-9]/', '', $dados['telefone'] ?? '');
    $saida['cep'] = preg_replace('/[^0-9]/', '', $dados['cep'] ?? '');
    $saida['sexo'] = strtolower(substr($dados['sexo'] ?? '', 0, 1));
    $saida['newsletter'] = !empty($dados['newsletter']) ? 'Sim' : 'Não';

    // Valida campos obrigatórios
    if (empty($saida['nome'])) $erros[] = "Nome é obrigatório.";
    if (empty($saida['sobrenome'])) $erros[] = "Sobrenome é obrigatório.";
    if (empty($saida['email'])) $erros[] = "E-mail é obrigatório.";
    if (!filter_var($saida['email'], FILTER_VALIDATE_EMAIL)) $erros[] = "E-mail inválido.";
    if (empty($saida['senha'])) $erros[] = "Senha é obrigatória.";

    // Valida data
    if (empty($saida['data'])) {
        $erros[] = "Data de nascimento é obrigatória.";
    } else {
        $data_valida = DateTime::createFromFormat('Y-m-d', $saida['data']);
        if (!$data_valida || $data_valida->format('Y-m-d') !== $saida['data']) {
            $erros[] = "Data de nascimento inválida.";
        }
    }

    // Mapeia sexo
    $sexo_texto = ['m' => 'Masculino', 'f' => 'Feminino'];
    $saida['sexo_legivel'] = $sexo_texto[$saida['sexo']] ?? 'Não informado';

    // Gera hash da senha
    $saida['senha_hash'] = password_hash($saida['senha'], PASSWORD_DEFAULT);

    return [
        'erro' => $erros,
        'dados' => [
            'nome' => $saida['nome'],
            'sobrenome' => $saida['sobrenome'],
            'email' => $saida['email'],
            'data' => $saida['data'],
            'telefone' => $saida['telefone'],
            'cep' => $saida['cep'],
            'sexo' => $saida['sexo_legivel'],
            'newsletter' => $saida['newsletter'],
            'senha_hash' => $saida['senha_hash']
        ]
    ];
}