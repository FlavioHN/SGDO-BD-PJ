<?php
// Dados de conexão (substitua pelos seus)
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'SGDO';

// Criar a conexão
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar a conexão
if (!$conn) {
    die("Conexao falhou: " . mysqli_connect_error());
} else {
    echo "Conexao bem-sucedida!";
}

///Funcao para receber dados e enviar para o banco de dados
function login($connect){
        ///Aqui usamos uma serie de filtros para evitar conexoes desnecessarias com o BD,
        ///Como por exemplo, o usuario não pode deixar campos vazios e deve digitar email valido
        if (isset($_POST['acessar']) AND !empty($_POST['cpf']) AND !empty($_POST['senha'])) {
                ///Filtro para email
                $email = filter_input(INPUT_POST, "cpf", FILTER_VALIDATE_EMAIL);
                ///Recebendo a senha
                $senha = ($_POST['senha']);
                ///enviando os dados ao BD e recebendo resposta
                $query = "SELECT * FROM ftpusers WHERE email = '$cpf' AND senha = '$senha' ";
                $executar = mysqli_query ($connect, $query);
                $return = mysqli_fetch_assoc($executar);
                ///Verificando resposta do BD
                if (!empty($return['cpf'])) {
//                      ///Se usuario e senha OK inicia-se a sessao
                        session_start();
                        ///Pedindo nome associado no BD
                        $_SESSION['nome'] = $return['nome'];
                        ///Criando a variavel "ativa" e setando para que a index.php seja exibida
                        $_SESSION['ativa'] = TRUE;
                        ///Direcionando para a pagina index.php
                        header("location: index.php");
                }
                else {
                        echo '
                        <div class="alert alert-danger d-flex align-items-center" role="alert">    
                          <div>
                          Email ou senha Invalidos
    
                          </div>
 
                        </div>';
                }

        }
}
///Uma função para matar a sessao e direcionar para a pagina de login
function logout(){

        session_start();
        session_unset();
        session_destroy();
        header("location: login.php");
}

?>