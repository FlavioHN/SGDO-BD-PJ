
-- Criação do banco sgdo
CREATE DATABASE IF NOT EXISTS SGDO;

USE SGDO;

-- Alterar a tabela de usuarios para incluir crm
CREATE TABLE IF NOT EXISTS usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  perfil ENUM('atendimento', 'medico') NOT NULL,
  nome VARCHAR(50) NOT NULL,
  sobrenome VARCHAR(50) NOT NULL,
  cpf VARCHAR(11) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL, -- Campo para armazenar a senha
  crm VARCHAR(7) NULL -- Campo para armazenar o CRM dos médicos
);

-- Criação da tabela dados_obito
CREATE TABLE IF NOT EXISTS dados_obito (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome_obito VARCHAR(100) NOT NULL,
  cpf_obito VARCHAR(11) UNIQUE NOT NULL,
  data_obito DATE NOT NULL,
  horario_obito TIME NOT NULL
);

-- Criação da tabela de laudos
CREATE TABLE IF NOT EXISTS laudo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_obito INT,
  id_medico INT,
  data_exame DATE NOT NULL,
  horario_exame TIME NOT NULL,
  laudo_exame TEXT,
  CONSTRAINT fk_id_obito FOREIGN KEY (id_obito) REFERENCES dados_obito(id),
  CONSTRAINT fk_id_medico FOREIGN KEY (id_medico) REFERENCES usuario(id) -- Alterado para referenciar a tabela de usuários
);


 -- ----------------------------------------------------------------------------------------------------------------------

	-- Procedure de Autenticação

DELIMITER //

CREATE PROCEDURE AuthenticateUser(
    IN p_cpf VARCHAR(11),
    IN p_senha VARCHAR(255),
    OUT p_result INT,
    OUT p_perfil ENUM('atendimento', 'medico'),
    OUT p_crm VARCHAR(7)
)
BEGIN
    DECLARE stored_senha VARCHAR(255);
    DECLARE user_perfil ENUM('atendimento', 'medico');
    DECLARE user_crm VARCHAR(7);

    -- Inicializa o resultado como 0 (usuário não encontrado)
    SET p_result = 0;

    -- Seleciona a senha, perfil e CRM armazenados para o CPF fornecido
    SELECT senha, perfil, crm INTO stored_senha, user_perfil, user_crm
    FROM usuario
    WHERE cpf = p_cpf;
    
    -- Verifica se a senha fornecida corresponde à senha armazenada
    IF stored_senha = p_senha THEN
        -- Senha corresponde, retorna 1 (sucesso) e o perfil do usuário
        SET p_result = 1;
        SET p_perfil = user_perfil;
        SET p_crm = user_crm;
    END IF;
END //

DELIMITER ;


-- Procedure de Cadastro de Óbitos

DELIMITER //

CREATE PROCEDURE RegisterObito(
    IN p_nome_obito VARCHAR(100),
    IN p_cpf_obito VARCHAR(11),
    IN p_data_obito DATE,
    IN p_horario_obito TIME
)
BEGIN
    INSERT INTO dados_obito (nome_obito, cpf_obito, data_obito, horario_obito)
    VALUES (p_nome_obito, p_cpf_obito, p_data_obito, p_horario_obito);
END //

DELIMITER ;

-- Procedure de Cadastro de Médicos

DELIMITER //

CREATE PROCEDURE RegisterMedico(
    IN p_nome_medico VARCHAR(100),
    IN p_cpf_medico VARCHAR(11),
    IN p_crm VARCHAR(7),
    IN p_senha VARCHAR(255), -- Campo para a senha
    OUT p_result INT -- Parâmetro para indicar o resultado
)
BEGIN
    DECLARE existing_user INT;

    -- Inicializa o resultado como 0 (sucesso)
    SET p_result = 0;

    -- Verificar se o médico já existe pelo CPF
    SELECT COUNT(*) INTO existing_user
    FROM usuario
    WHERE cpf = p_cpf_medico;

    IF existing_user = 0 THEN
        -- Inserir o médico na tabela de usuários
        INSERT INTO usuario (perfil, nome, sobrenome, cpf, senha, crm)
        VALUES ('medico', p_nome_medico, '', p_cpf_medico, p_senha, p_crm);
    ELSE
        -- Médico já cadastrado, definir p_result como 1 para erro
        SET p_result = 1;
    END IF;
END //

DELIMITER ;

-- Procedure de Cadastro de Laudo

DELIMITER //

CREATE PROCEDURE RegisterLaudo(
    IN p_id_obito INT,
    IN p_id_medico INT,
    IN p_data_exame DATE,
    IN p_horario_exame TIME,
    IN p_laudo_exame TEXT
)
BEGIN
    -- Verifica se a data e hora do exame são válidas
    IF p_data_exame > p_data_obito OR
       (p_data_exame = p_data_obito AND p_horario_exame <= p_horario_obito) THEN
        -- Insere um registro na tabela de erros
        INSERT INTO erro_log (mensagem) VALUES ('Data ou horário do exame inválidos.');
    END IF;
END //

DELIMITER ;

-- ----------------------------------------------------------------------------------------------------------------------


INSERT INTO usuario (nome, sobrenome, cpf, senha)
VALUES ('user1', 'Santos', '12345678901', '1234');


SELECT * FROM usuario WHERE nome = 'user1';




SET @result = 0;
SET @perfil = NULL;

-- Chamar a procedure com valores de exemplo
CALL AuthenticateUser('12345678901', '1234', @result, @perfil);

-- Verificar os resultados
SELECT @result AS result, @perfil AS perfil;