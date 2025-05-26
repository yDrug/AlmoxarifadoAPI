<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o script de conexão
require_once 'conexao.php';
$con->set_charset("utf8");

// Consulta SQL sem filtro
$sql = "SELECT idProduto, nmProduto, deProduto, idMarca, cdProduto
        FROM almoxarifado.almProduto";

$stmt = $con->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$response = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = array_map(fn($val) => mb_convert_encoding($val, 'UTF-8', 'ISO-8859-1'), $row);
    }
} else {
    $response[] = [
        "idProduto" => 0,
        "nmProduto" => "",
        "deProduto" => "",
        "idMarca" => 0,
        "cdProduto" => ""
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

$stmt->close();
$con->close();

?>
