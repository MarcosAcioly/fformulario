<?php
require_once 'limpar.php';

$dados = $_POST;
$resultado = validar_e_processar($dados);

if ($resultado['erro']) {
    echo "<h2 style='color:red;'>Erros encontrados:</h2>";
    echo "<ul>";
    foreach ($resultado['erro'] as $e) {
        echo "<li>" . htmlspecialchars($e) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<h2>Dados Cadastrados</h2>";
    echo "<ul>";
    foreach ($resultado['dados'] as $chave => $valor) {
        echo "<li><strong>" . ucfirst($chave) . ":</strong> " . htmlspecialchars($valor) . "</li>";
    }
    echo "</ul>";
}
?>