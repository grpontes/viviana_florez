<?php
// Configurações do banco de dados
$servername = "localhost"; // ou o endereço do servidor MySQL
$username = "root";
$password = "";
$dbname = "test";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Variável para armazenar a mensagem de sucesso
$successMessage = "";

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    // Preparar a declaração SQL para inserção
    $insert = "INSERT INTO vouchers (nom_comprador, eml_comprador, dat_compra, val_compra, ind_uso) VALUES (?, ?, now(), 100, 1)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("ss", $nome, $email);

    // Executar a inserção
    if ($stmt->execute()) {
        $successMessage = "Cadastro realizado com sucesso. Você receberá um voucher em seu e-mail em breve.";
    } else {
        $successMessage = "Erro ao realizar o cadastro: " . $conn->error;
    }

    // Define variável com id da linha inserida
    $last_id = $conn->insert_id;

    // Preparar a declaração SQL para atualização
    $update = "UPDATE vouchers set cod_validacao = crc32(concat(oid_vouchers, nom_comprador, ifnull(cpf_comprador, '000.000.000-00'), val_compra * 100)) where oid_vouchers = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("i", $last_id);

    // Executar a inserção
    if ($stmt->execute()) {
        $successMessage = "Atualização realizada com sucesso. Você receberá um voucher em seu e-mail em breve.";
    } else {
        $successMessage = "Erro ao realizar a atualização: " . $conn->error;
    }

    // Fechar a declaração
    $stmt->close();
}

// Fechar a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form method="post" action="">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>
        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Enviar</button>
    </form>
    <?php
    if ($successMessage != "") {
        echo "<p>$successMessage</p>";
    }
    ?>
</body>
</html>
