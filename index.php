<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['ativa']) || $_SESSION['ativa'] !== true) {
    header("Location: login.php");
    exit();
}

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


// Obtendo o perfil do usuário e CRM
$perfil = $_SESSION['perfil'];
$crm = isset($_SESSION['crm']) ? $_SESSION['crm'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    
    <title>Painel Principal</title>
</head>
<body>
    <h1>Painel Principal</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['cpf']); ?>.</p>
    <p>Perfil: <?php echo htmlspecialchars($perfil); ?></p>
    <?php if ($perfil == 'medico' && $crm) { ?>
        <p>CRM: <?php echo htmlspecialchars($crm); ?></p>
    <?php } ?>

    <?php if ($perfil == 'atendimento') { ?>        
        <a href="consultar_obito.php">Consultar Obito</a><br>
        <a href="cadastro_medico.php">Cadastrar Medico</a><br>
        <!--<a href="cadastro_atendente.php">Cadastrar Atendente</a><br>-->
    <?php } elseif ($perfil == 'medico') { ?>
        <a href="cadastro_obito.php">Cadastrar Obito</a><br>
        <a href="consultar_obito.php">Consultar Obito</a><br>
        <!--<a href="cadastro_atendente.php">Cadastrar Atendente</a><br>-->
        <a href="cadastro_medico.php"  >Cadastrar Medico</a><br>
        
    <?php } ?>

    <a href="logout.php">Sair</a>
</body>
</html>
