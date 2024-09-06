<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['cpf'])) {
    header("Location: login.php");
    exit();
}

// Função para gerar PDF com FPDF
function gerar_pdf($dados) {
    require('fpdf186/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Relatório de Óbito e Laudo', 0, 1, 'B');
    $pdf->SetFont('Arial', '', 12);

    foreach ($dados as $row) {
        $pdf->Cell(0, 10, "ID Obito: " . (isset($row['id_obito']) ? $row['id_obito'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "Nome do Obito: " . (isset($row['nome_obito']) ? $row['nome_obito'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "CPF do Obito: " . (isset($row['cpf_obito']) ? $row['cpf_obito'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "Data do Obito: " . (isset($row['data_obito']) ? $row['data_obito'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "Horario do Obito: " . (isset($row['horario_obito']) ? $row['horario_obito'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "ID Laudo: " . (isset($row['id_laudo']) ? $row['id_laudo'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "CRM Medico: ". (isset($row['id_medico']) ? $row['id_medico'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "Data do Exame: " . (isset($row['data_exame']) ? $row['data_exame'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "Horario do Exame: " . (isset($row['horario_exame']) ? $row['horario_exame'] : 'N/A'), 0, 1);
        $pdf->Cell(0, 10, "Laudo do Exame: " . (isset($row['laudo_exame']) ? $row['laudo_exame'] : 'N/A'), 0, 1);
        $pdf->Ln();
    }

    $pdf->Output('I', 'relatorio.pdf'); // Envia o PDF para download
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf_obito = $_POST['cpf_obito'];

    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "SGDO");
    $conn->set_charset("utf8"); // Configurar o charset para UTF-8

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Chamar a procedure de consulta de óbito
    $stmt = $conn->prepare("CALL consultarObito(?)");
    $stmt->bind_param("s", $cpf_obito);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $dados = [];
            while ($row = $result->fetch_assoc()) {
                $dados[] = $row;
            }

            if (isset($_POST['gerar_pdf'])) {
                gerar_pdf($dados);
            } else {
                // Exibir os dados na tela
                echo "<h2>Resultados da Consulta:</h2>";
                echo "<table border='1'>
                        <tr>
                            <th>ID Obito</th>
                            <th>Nome do Obito</th>
                            <th>CPF do Obito</th>
                            <th>Data do Obito</th>
                            <th>Horario do Obito</th>
                            <th>ID Laudo</th>                            
                            <th>CRM Medico</th>
                            <th>Data do Exame</th>
                            <th>Horario do Exame</th>
                            <th>Laudo do Exame</th>
                        </tr>";

                foreach ($dados as $row) {
                    echo "<tr>
                            <td>" . (isset($row['id_obito']) ? $row['id_obito'] : 'N/A') . "</td>
                            <td>" . (isset($row['nome_obito']) ? $row['nome_obito'] : 'N/A') . "</td>
                            <td>" . (isset($row['cpf_obito']) ? $row['cpf_obito'] : 'N/A') . "</td>
                            <td>" . (isset($row['data_obito']) ? $row['data_obito'] : 'N/A') . "</td>
                            <td>" . (isset($row['horario_obito']) ? $row['horario_obito'] : 'N/A') . "</td>
                            <td>" . (isset($row['id_laudo']) ? $row['id_laudo'] : 'N/A') . "</td>
                            <td>" . (isset($row['id_medico']) ? $row['id_medico'] : 'N/A') . "</td>
                            <td>" . (isset($row['data_exame']) ? $row['data_exame'] : 'N/A') . "</td>
                            <td>" . (isset($row['horario_exame']) ? $row['horario_exame'] : 'N/A') . "</td>
                            <td>" . (isset($row['laudo_exame']) ? $row['laudo_exame'] : 'N/A') . "</td>
                        </tr>";
                }

                echo "</table>";
                echo '<form method="post" action="">
                        <input type="hidden" name="cpf_obito" value="' . htmlspecialchars($cpf_obito) . '">
                        <button type="submit" name="gerar_pdf">Ver em PDF</button>
                      </form>';
            }
        } else {
            echo "<p>Nenhum registro encontrado para o CPF informado.</p>";
        }

        $result->free();
    } else {
        echo "<p>Erro ao realizar a consulta: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    
    <title>Consultar Obito</title>
    <a href="index.php">HOME</a><br>
    <h2>Dados do Obito</h2>
</head>
<body>
    <h1>Consultar Dados do Obito</h1>
    <form method="post" action="">
        <label for="cpf_obito">CPF do Obito:</label>
        <input type="text" id="cpf_obito" name="cpf_obito" required>
        <br>
        <button type="submit">Consultar</button>
    </form>
</body>
</html>
