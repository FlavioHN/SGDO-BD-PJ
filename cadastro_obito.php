<?php
session_start();

// Verifica se o usuário está autenticado e tem o perfil de médico
if (!isset($_SESSION['cpf']) || $_SESSION['perfil'] != 'medico') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_obito = $_POST['nome_obito'];
    $cpf_obito = $_POST['cpf_obito'];
    $data_obito = $_POST['data_obito'];
    $horario_obito = $_POST['horario_obito'];
    $data_exame = $_POST['data_exame'];
    $horario_exame = $_POST['horario_exame'];
    $laudo_exame = $_POST['laudo_exame'];

    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "SGDO");

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Obtém o CRM do médico logado
    $stmt = $conn->prepare("SELECT crm FROM usuario WHERE cpf = ?");
    $stmt->bind_param("s", $_SESSION['cpf']);
    $stmt->execute();
    $stmt->bind_result($crm_medico);
    $stmt->fetch();
    $stmt->close();

    // Verifica se o CRM foi encontrado
    if (empty($crm_medico)) {
        echo "<p>Erro: CRM do medico não encontrado.</p>";
    } else {
        // Verifica se a data e hora do exame são válidas
        if ($data_exame > $data_obito || ($data_exame == $data_obito && $horario_exame <= $horario_obito)) {
            echo "<p>Erro: Data ou horário do exame inválidos.</p>";
        } else {
            // Inicia uma transação
            $conn->begin_transaction();

            try {
                // Chamar a procedure de cadastro de Obito
                $stmt = $conn->prepare("CALL RegisterObito(?, ?, ?, ?, @id_obito)");
                $stmt->bind_param("ssss", $nome_obito, $cpf_obito, $data_obito, $horario_obito);
                if (!$stmt->execute()) {
                    throw new Exception("Erro ao cadastrar Obito: " . $stmt->error);
                }

                // Recupera o ID do Obito
                $stmt = $conn->prepare("SELECT @id_obito");
                $stmt->execute();
                $stmt->bind_result($id_obito);
                $stmt->fetch();
                $stmt->close();

                // Verifica se o ID do Obito foi recuperado
                if (empty($id_obito)) {
                    throw new Exception("Erro ao recuperar ID do Obito.");
                }

                // Chamar a procedure de cadastro de laudo usando o ID do Obito e CRM do médico
                $stmt = $conn->prepare("CALL RegisterLaudo(?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $id_obito, $crm_medico, $data_exame, $horario_exame, $laudo_exame);
                if (!$stmt->execute()) {
                    throw new Exception("Erro ao cadastrar laudo: " . $stmt->error);
                }

                // Confirma a transação
                $conn->commit();
                echo "<p>Obito e laudo cadastrados com sucesso!</p>";
            } catch (Exception $e) {
                // Desfaz a transação em caso de erro
                $conn->rollback();
                echo "<p>Falha ao cadastrar: " . $e->getMessage() . "</p>";
            }

            $stmt->close();
            $conn->close();
        }
    }
}





?>




    
    
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Obito e Laudo</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>
<body>
    <h1>Cadastrar Obito e Laudo</h1>
    <form method="post" action="">
        <a href="index.php">HOME</a><br>
        
        <h2>Dados do Obito</h2>
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
        
        <h2>Dados do Laudo</h2>
        <label for="data_exame">Data do Exame:</label>
        <input type="date" id="data_exame" name="data_exame" required>
        <br>
        
        <label for="horario_exame">Horario do Exame:</label>
        <input type="time" id="horario_exame" name="horario_exame" required>
        <br>
        <label for="laudo_exame">Laudo do Exame:</label>
        <textarea id="laudo_exame" name="laudo_exame" required></textarea>
        <br>
        

  
  

        
        
        <button type="submit">Cadastrar Obito e Laudo</button>
    </form>
</body>
</html>
