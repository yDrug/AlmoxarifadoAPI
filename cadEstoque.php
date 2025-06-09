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
$dtEntrada = trim($jsonParam['dtEntrada'] ?? '');
$qtEntrada = floatval($jsonParam['qtEntrada'] ?? 0);
$vlEntrada = isset($jsonParam['vlEntrada']) ? floatval($jsonParam['vlEntrada']) : null;
$idProduto = intval($jsonParam['idProduto'] ?? 0);

// Validações básicas
if (!$dtEntrada || !$qtEntrada || !$idProduto) {
    echo json_encode([
        'success' => false,
        'message' => 'Campos obrigatórios ausentes ou inválidos.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Prepara a instrução SQL
$sql = "
    INSERT INTO almoxarifado.almEntradaEstoque 
        (dtEntrada, qtEntrada, vlEntrada, idProduto)
    VALUES (?, ?, ?, ?)
";

$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar a consulta: ' . $con->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Vincula parâmetros: s = string (data), d = double (decimal), i = integer
$stmt->bind_param(
    "ssdi",
    $dtEntrada,
    $qtEntrada,
    $vlEntrada,
    $idProduto
);

// Executa e retorna resultado
if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Entrada de estoque registrada com sucesso!'
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao registrar entrada de estoque: ' . $stmt->error
    ], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$con->close();
