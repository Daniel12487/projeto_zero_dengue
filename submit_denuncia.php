<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "username", "password", "zero_dengue");
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Recebe e escapa as entradas
    $cep = $conn->real_escape_string($_POST['cep']);
    $logradouro = $conn->real_escape_string($_POST['logradouro']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $macro_regiao = $conn->real_escape_string($_POST['macro_regiao']);
    $imagem = $conn->real_escape_string($_FILES['imagem']['name']);

    // Configura o diretório e o arquivo de destino para o upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["imagem"]["name"]);

    //  mover o arquivo enviado para o diretório de destino
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
        echo "Arquivo " . htmlspecialchars(basename($_FILES["imagem"]["name"])) . " foi enviado.";
    } else {
        echo "Erro ao enviar arquivo.";
    }

    // SQL para inserir os dados no banco
    $sql = "INSERT INTO denuncias (cep, logradouro, descricao, macro_regiao, imagem_url) VALUES ('$cep', '$logradouro', '$descricao', '$macro_regiao', '$imagem')";

    // Executa a query
    if ($conn->query($sql) === TRUE) {
        echo "Nova denúncia registrada com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    // Atualiza as estatísticas após cada inserção
    $updateStats = "INSERT INTO estatisticas (macro_regiao, quantidade) VALUES ('$macro_regiao', 1) ON DUPLICATE KEY UPDATE quantidade = quantidade + 1";
    $conn->query($updateStats);
    
    // Fecha a conexão
    $conn->close();
}
?>
