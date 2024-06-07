<?php
// Configurações do banco de dados
$host = "gpinnovations-postgresql-aws.csikcewytfzw.sa-east-1.rds.amazonaws.com";
$dbname = "viviana_florez";
$user = "vivi";
$password = "V!vi";

// Mensagem de sucesso
$mensagem = "";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];

    // Conecta ao banco de dados
    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepara e executa o comando de inserção
        $stmt = $conn->prepare("
            INSERT INTO vivi.vouchers (
                nom_comprador, eml_comprador, dat_compra, val_compra
            ) VALUES (
                :nome, :email, '2024-06-07', '650'
            )
        ");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Define a mensagem de sucesso
        $mensagem = "Cadastro realizado com sucesso. Você receberá um voucher em seu e-mail em breve.";

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }

    // Fecha a conexão
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
</head>
<body>
    <h1>Formulário de Cadastro</h1>
    <form method="post" action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        <button type="submit">Enviar</button>
    </form>

    <?php
    if ($mensagem) {
        echo "<p>$mensagem</p>";
    }
    ?>
</body>
</html>
