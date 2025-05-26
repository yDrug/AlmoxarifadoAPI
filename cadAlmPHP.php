<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define que vamos retornar JSON e usar UTF-8
header('Content-Type: application/json; charset=utf-8');

// Conexão com o banco (arquivo conexao.php deve definir $con)
require_once 'conexao.php';
$con->set_charset("utf8");

// Lê o corpo da requisição e decodifica o JSON
$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!$jsonParam) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados JSON inválidos ou ausentes.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Extrai e saneia os campos do JSON
$idProduto  = intval($jsonParam['idProduto']  ?? 0);
$nmProduto  = trim($jsonParam['nmProduto']   ?? '');
$deProduto  = isset($jsonParam['deProduto']) 
                ? trim($jsonParam['deProduto']) 
                : null;
$idMarca    = intval($jsonParam['idMarca']    ?? 0);
$cdProduto  = trim($jsonParam['cdProduto']   ?? '');

// Validação dos campos obrigatórios
if ($idProduto <= 0 
    || empty($nmProduto) 
    || empty($cdProduto) 
    || $idMarca <= 0
) {
    echo json_encode([
        'success' => false,
        'message' => 'Campos obrigatórios ausentes ou inválidos: idProduto, nmProduto, cdProduto e idMarca são necessários.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Prepara a instrução SQL
$sql = "
    INSERT INTO almoxarifado.almProduto 
        (idProduto, nmProduto, deProduto, idMarca, cdProduto)
    VALUES (?, ?, ?, ?, ?)
";

$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar a consulta: ' . $con->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Vincula parâmetros: i = integer, s = string
// Ordem: idProduto (i), nmProduto (s), deProduto (s|null), idMarca (i), cdProduto (s)
$stmt->bind_param(
    "issis",
    $idProduto,
    $nmProduto,
    $deProduto,
    $idMarca,
    $cdProduto
);

// Executa e retorna resultado
if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Produto inserido com sucesso!'
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro no registro do produto: ' . $stmt->error
    ], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$con->close();
