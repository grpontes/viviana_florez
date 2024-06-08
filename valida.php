<!DOCTYPE html>
<html>
<head>
    <title>Consultar Valor no Banco de Dados</title>
</head>
<body>
    <h2>Consultar Valor no Banco de Dados</h2>
    
    <!-- Formulário HTML -->
    <form method="POST" action="">
        <label for="valor">Digite o código de validação:</label>
        <input type="text" id="valor" name="valor">
        <button type="submit">Consultar</button>
    </form>
    
    <!-- Script PHP para processar a consulta -->
    <?php
    // Parâmetros de conexão com o banco de dados
    $servername = "localhost"; // ou o endereço do servidor MySQL
    $username = "root";
    $password = "";
    $dbname = "test";

    // Cria a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Função para alternar o status
    function toggleStatus($conn, $id) {
        $sql = "UPDATE vouchers SET ind_uso = NOT ind_uso WHERE oid_vouchers = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Status atualizado com sucesso.";
        } else {
            echo "Erro ao atualizar status: " . $conn->error;
        }
    }

    // Verifica se o botão de validação foi clicado
    if (isset($_POST['toggle_status_id'])) {
        $id = $_POST['toggle_status_id'];
        toggleStatus($conn, $id);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['toggle_status_id'])) {
        // Obtém o valor digitado pelo usuário
        $valor = $_POST['valor'];
        
        // Protege contra SQL Injection
        $valor = $conn->real_escape_string($valor);
        
        // Consulta SQL
        $sql = "SELECT * FROM vouchers WHERE cod_validacao = '$valor'";
        $result = $conn->query($sql);
        
        // Exibe o resultado
        if ($result->num_rows > 0) {
            echo "<h3>Resultados da consulta:</h3>";
            echo "<table border='1'><tr>
                                        <th>oid_vouchers</th>
                                        <th>nom_comprador</th>
                                        <th>eml_comprador</th>
                                        <th>cpf_comprador</th>
                                        <th>dat_compra</th>
                                        <th>val_compra</th>
                                        <th>dat_validade</th>
                                        <th>dsc_campanha</th>
                                        <th>ind_uso</th>
                                        <th>dat_uso</th>
                                        <th>nom_paciente</th>
                                        <th>cpf_paciente</th>
                                        <th>cod_validacao</th>
                                        <th>dsc_observacao</th>
                                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>"
                        . "<td>" . $row["oid_vouchers"] . "</td>"
                        . "<td>" . $row["nom_comprador"] . "</td>"
                        . "<td>" . $row["eml_comprador"] . "</td>"
                        . "<td>" . $row["cpf_comprador"] . "</td>"
                        . "<td>" . $row["dat_compra"] . "</td>"
                        . "<td>" . $row["val_compra"] . "</td>"
                        . "<td>" . $row["dat_validade"] . "</td>"
                        . "<td>" . $row["dsc_campanha"] . "</td>"
                        . "<td>" . $row["ind_uso"] . "</td>"
                        . "<td>" . $row["dat_uso"] . "</td>"
                        . "<td>" . $row["nom_paciente"] . "</td>"
                        . "<td>" . $row["cpf_paciente"] . "</td>"
                        . "<td>" . $row["cod_validacao"] . "</td>"
                        . "<td>" . $row["dsc_observacao"] . "</td>"
                        . "<td>" . ($row["ind_uso"] ? 'Válido' : 'Inválido') . "</td>
                        <td>
                            <form method='POST' action=''>
                                <input type='hidden' name='toggle_status_id' value='" . $row["oid_vouchers"] . "'>
                                <button type='submit'>" . ($row["ind_uso"] ? 'Invalidar' : 'Validar') . "</button>
                            </form>
                        </td>
                </tr>"; // Adapte conforme necessário
            }
            echo "</table>";
        } else {
            echo "Nenhum resultado encontrado.";
        }
    }

    // Fecha a conexão
    $conn->close();
    ?>
</body>
</html>
