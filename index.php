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

    // Define variáveis padrão
    $dat_compra = '2024-06-07';
    $val_compra = 100;
    $dat_validade = '2024-10-31';
    $dsc_campanha = 'Dia dos Namorados';
    $ind_uso = 'N';
    $dat_uso = null;
    $nom_paciente = null;
    $cpf_paciente = null;
    $cod_validacao = null;
    $dsc_observacao = 'Inserido pelo PHP';

    // Conecta ao banco de dados
    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepara e executa o comando de inserção
        $stmt = $conn->prepare("
            INSERT INTO vivi.vouchers (
                nom_comprador,
                eml_comprador,
                dat_compra,
                val_compra,
                dat_validade,
                dsc_campanha,
                ind_uso,
                dat_uso,
                nom_paciente,
                cpf_paciente,
                cod_validacao,
                dsc_observacao
            ) VALUES (
                :nom_comprador,
                :eml_comprador,
                :dat_compra,
                :val_compra,
                :dat_validade,
                :dsc_campanha,
                :ind_uso,
                :dat_uso,
                :nom_paciente,
                :cpf_paciente,
                :cod_validacao,
                :dsc_observacao
            )
        ");
        $stmt->bindParam(':nom_comprador', $nome);
        $stmt->bindParam(':eml_comprador', $email);
        $stmt->bindParam(':dat_compra', $dat_compra);
        $stmt->bindParam(':val_compra', $val_compra);
        $stmt->bindParam(':dat_validade', $dat_validade);
        $stmt->bindParam(':dsc_campanha', $dsc_campanha);
        $stmt->bindParam(':ind_uso', $ind_uso);
        $stmt->bindParam(':dat_uso', $dat_uso);
        $stmt->bindParam(':nom_paciente', $nom_paciente);
        $stmt->bindParam(':cpf_paciente', $cpf_paciente);
        $stmt->bindParam(':cod_validacao', $cod_validacao);
        $stmt->bindParam(':dsc_observacao', $dsc_observacao);
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