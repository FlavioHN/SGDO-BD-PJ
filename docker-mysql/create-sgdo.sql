-- Criação do banco sgdo
create database sgdo;

USE sgdo;

-- Criação da tabela de usuarios
create table if not exists usuario (
    id int auto_increment primary key,
    nome varchar(50) not null,
    sobrenome varchar(50) not null,
    cpf varchar(11) unique not null
);

-- Criação da tabela dados_obito
create table if not exists dados_obito (
	id int auto_increment primary key,
	nome_obito varchar(100) not null,
    cpf_obito varchar(11) unique not null,
    data_obito date not null,
    horario_obito time not null
);

-- Criação da tabela medicos
create table if not exists medicos (
	id int auto_increment primary key,
    nome_medico varchar(100) not null,
    cpf_medico varchar(11) unique not null,
    crm varchar(7) unique not null
);

-- Criação da tabela laudos
create table if not exists laudo (
	id int auto_increment primary key,
    id_obito int,
    id_medico int,
    data_exame date not null,
    horario_exame time not null,
    laudo_exame text,
	constraint fk_id_obito foreign key (id_obito) references dados_obito(id),
    constraint fk_id_medico foreign key (id_medico) references medicos(id)
);