<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "username", "password", "zero_dengue");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$query = "SELECT macro_regiao, COUNT(*) as total FROM denuncias GROUP BY macro_regiao";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
