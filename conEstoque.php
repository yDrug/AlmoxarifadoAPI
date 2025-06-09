<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o script de conexão
require_once 'conexao.php';
$con->set_charset("utf8");

// Consulta SQL para almEntradaEstoque
$sql = "SELECT dtEntrada, qtEntrada, vlEntrada, idProduto
        FROM almoxarifado.almEntradaEstoque";

$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$response = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Converte todos os valores para UTF-8 se necessário
        $response[] = array_map(fn($val) => mb_convert_encoding($val, 'UTF-8', 'ISO-8859-1'), $row);
    }
} else {
    $response[] = [
        "dtEntrada" => "",
        "qtEntrada" => 0,
        "vlEntrada" => 0.00,
        "idProduto" => 0
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

$stmt->close();
$con->close();

?>
