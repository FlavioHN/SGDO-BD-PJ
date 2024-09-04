<?php
session_start();

// Verifica se o usu�rio est� autenticado e tem o perfil adequado
if (!isset($_SESSION['cpf']) || $_SESSION['perfil'] != 'medico') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_obito = $_POST['nome_obito'];
    $cpf_obito = $_POST['cpf_obito'];
    $data_obito = $_POST['data_obito'];
    $horario_obito = $_POST['horario_obito'];

    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "SGDO");

    // Verifica a conex�o
    if ($conn->connect_error) {
        die("Conex�o falhou: " . $conn->connect_error);
    }


    // Chamar a procedure de cadastro de �bito
    $stmt = $conn->prepare("CALL RegisterObito(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome_obito, $cpf_obito, $data_obito, $horario_obito);
    
    if ($stmt->execute()) {
        echo "Obito cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar �bito: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastrar �bito</title>
</head>
<body>
    <h1>Cadastrar �bito</h1>
    <form method="post" action="">
        <label for="nome_obito">Nome:</label>
        <input type="text" id="nome_obito" name="nome_obito" required>
        <br>
        <label for="cpf_obito">CPF:</label>
        <input type="text" id="cpf_obito" name="cpf_obito" required>
        <br>
        <label for="data_obito">Data do Obito:</label>
        <input type="date" id="data_obito" name="data_obito" required>
        <br>
        <label for="horario_obito">Horario do Obito:</label>
        <input type="time" id="horario_obito" name="horario_obito" required>
        <br>
        <button type="submit">Cadastrar Obito</button>
    </form>
</body>
</html>
