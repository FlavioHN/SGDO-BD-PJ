-- inserir dados na tabela dados_obito
INSERT INTO dados_obito (nome_obito, cpf_obito, data_obito, horario_obito)
	VALUES ('Jubileu da Silva Noia', '12345678900', '2021-05-03', '13:45:00');
  
INSERT INTO dados_obito (nome_obito, cpf_obito, data_obito, horario_obito)
	VALUES ('Leraen Galadhrien Melrien', '71371988056', '2018-05-22', '19:30:15');  
  
INSERT INTO dados_obito (nome_obito, cpf_obito, data_obito, horario_obito)
	VALUES ('Xurion Nardasu Behaual', '19636077088', '2012-03-05', '08:15:30');

INSERT INTO dados_obito (nome_obito, cpf_obito, data_obito, horario_obito)
	VALUES ('Weasi Reius', '82526241030', '2015-11-17', '23:59:45');
  
-- inserir dados na tabela medicos
INSERT INTO medicos (nome_medico, cpf_medico, crm)
	VALUES ('Arya Souza', '12345678900', '2345-SP');
  
INSERT INTO medicos (nome_medico, cpf_medico, crm)
	VALUES ('Beatriz Thauriel', '32165498700', '4321-MG');
 
INSERT INTO medicos (nome_medico, cpf_medico, crm)
	VALUES ('Felipe Beru', '65432198700', '8765-DF');

-----------------
select * from dados_obito, medicos, laudo