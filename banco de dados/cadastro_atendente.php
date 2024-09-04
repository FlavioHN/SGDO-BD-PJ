<?php
session_start();

// Verifica se o usu�rio est� autenticado e � um atendimento
if (!isset($_SESSION['ativa']) || $_SESSION['perfil'] != 'atendimento') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_atendente = $_POST['nome_atendente'];
    $cpf_atendente = $_POST['cpf_atendente'];
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

    // Preparando para chamar a stored procedure
    $stmt = $conn->prepare("CALL RegisterAtendente(?, ?, ?)");
    $stmt->bind_param("sss", $nome_atendente, $cpf_atendente, $senha);

    if ($stmt->execute()) {
        echo "Atendente cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar atendente: " . $conn->error;
    }

    // Fechando a conex�o
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Atendente</title>
</head>
<body>
    <h1>Cadastrar Atendente</h1>
    <form method="POST" action="">
        <label for="nome_atendente">Nome do Atendente:</label>
        <input type="text" id="nome_atendente
