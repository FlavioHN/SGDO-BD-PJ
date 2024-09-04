<?php
session_start();

// Dados de conexão
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'SGDO';

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
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
        $_SESSION['crm'] = $result['crm']; // Armazena o CRM na sessão se for um médico
        header("Location: index.php");
        exit();
    } else {
        $error = "CPF ou senha incorretos!";
    }
}

// Fechando a conexão
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
