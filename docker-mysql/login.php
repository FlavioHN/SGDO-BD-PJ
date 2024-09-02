<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sgdo";

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$user = $_POST['username'];
$pass = $_POST['password'];

// Divide o usuário em nome e sobrenome
list($nome, $sobrenome) = explode('.', $user);

// Prepara a consulta SQL
$sql = "SELECT * FROM usuario WHERE nome = ? AND sobrenome = ? AND cpf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $sobrenome, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Login bem-sucedido!";
} else {
    echo "Usuário ou senha inválidos.";
}

$stmt->close();
$conn->close();
?>
