<?php
session_start();

// Dados de conexao
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'SGDO';

// Criando a conexao
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexao
if ($conn->connect_error) {
    die("Conex�o falhou: " . $conn->connect_error);
}

// Verifica se o formulario foi enviado
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
        $_SESSION['crm'] = $result['crm']; // Armazena o CRM na sessao se for um medico
        header("Location: index.php");
        exit();
    } else {
        $error = "CPF ou senha incorretos!";
    }
}

// Fechando a conexao
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/src/login.css">
    <title>Login</title>
</head>
<body>
    <div class="form-logo">
        <div class="form-box">
            <div class="form-detalhes">
                <p>Welcome Back!</p>
                <h2>SGDO</h2>
                <p>Sistema de Gerenciamento de Documento de Óbito</p>
            </div>
            <div class="form-login">
                <h2>LOGIN</h2>
                <form method="POST" action="">
                    <div class="input-login">
                        <input type="text" id="cpf" name="cpf" required><br><br>
                        <label for="cpf">CPF</label>
                    </div>
                    <div class="input-login">
                        <input type="password" id="senha" name="senha" required><br><br>
                        <label for="senha">Senha</label>
                    </div>
                    <button type="submit">Log In</button>
                </form>
                <?php if (isset($error)) echo "<p>$error</p>"; ?>
            </div>
        </div>
    </div>
</body>
</html>
