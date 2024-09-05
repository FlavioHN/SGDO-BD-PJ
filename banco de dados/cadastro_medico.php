<?php
session_start();

// Verifica se o usu�rio est� autenticado e � um atendente
if (!isset($_SESSION['ativa'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_medico = $_POST['nome_medico'];
    $cpf_medico = $_POST['cpf_medico'];
    $crm = $_POST['crm'];
    $senha = $_POST['senha'];

    // Dados de conex�o
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'SGDO';

    // Criar a conex�o
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conex�o
    if ($conn->connect_error) {
        die("Conex�o falhou: " . $conn->connect_error);
    }

    // Preparar chamada da stored procedure
    $stmt = $conn->prepare("CALL RegisterMedico(?, ?, ?, ?, @result)");
    $stmt->bind_param("ssss", $nome_medico, $cpf_medico, $crm, $senha);
    $stmt->execute();

    // Obter o valor do par�metro de resultado
    $result = $conn->query("SELECT @result AS result")->fetch_assoc();
    
    if ($result['result'] == 0) {
        echo "Medico cadastrado com sucesso!";
    } else {
        echo "Erro: Medico ja cadastrado.";
    }

    // Fechar a conex�o
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Medico</title>
</head>
<body>
    <h1>Cadastrar Medico</h1>
    <form method="POST" action="">
        <label for="nome_medico">Nome do Medico:</label>
        <input type="text" id="nome_medico" name="nome_medico" required><br><br>
        <label for="cpf_medico">CPF do Medico:</label>
        <input type="text" id="cpf_medico" name="cpf_medico" required><br><br>
        <label for="crm">CRM:</label>
        <input type="text" id="crm" name="crm" required><br><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
