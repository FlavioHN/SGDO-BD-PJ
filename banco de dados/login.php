<?php
session_start();

// Dados de conex�o
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'SGDO';

// Criando a conex�o
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conex�o
if ($conn->connect_error) {
    die("Conex�o falhou: " . $conn->connect_error);
}

// Verifica se o formul�rio foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    
    // Preparando para chamar a stored procedure
    $stmt = $conn->prepare("CALL AuthenticateUser(?, ?, @result, @perfil, @crm)");
    $stmt->bind_param("ss", $cpf, $senha);
    $stmt->execute();
    
    // Obtendo o resultado da stored procedure
    $result = $conn->query("SELECT @result AS result, @perfil AS perfil, @crm AS crm")->fetch_assoc();
    
    if ($result['result'] == 1) {
        $_SESSION['ativa'] = true;
        $_SESSION['cpf'] = $cpf;
        $_SESSION['perfil'] = $result['perfil'];
        $_SESSION['crm'] = $result['crm']; // Armazena o CRM na sess�o se for um m�dico
        header("Location: index.php");
        exit();
    } else {
        $error = "CPF ou senha incorretos!";
    }
}

// Fechando a conex�o
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="">
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required><br><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>
        <button type="submit">Login</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
