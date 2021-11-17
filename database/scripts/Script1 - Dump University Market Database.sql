/*
 * Script Dump - University Market Database
 */
DROP DATABASE IF EXISTS `UniversityMarket_Db`;
CREATE DATABASE `UniversityMarket_Db`;

USE `UniversityMarket_Db`;

/*
 * Criacao tabela Planos
 */
DROP TABLE IF EXISTS `Planos`;
CREATE TABLE `Planos` (
    `id` INT NOT NULL,
    `nome` VARCHAR(100) NOT NULL,

    PRIMARY KEY (`id`)
);

INSERT INTO `Planos` (`id`, `nome`)
VALUES (1, 'Básico'), (2, 'Avançado');

/*
 * Criacao tabela Instituicoes
 */
DROP TABLE IF EXISTS `Instituicoes`;
CREATE TABLE `Instituicoes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nome_fantasia` VARCHAR(150),
    `razao_social` VARCHAR(150),
    `cnpj` CHAR(14),
    `email` VARCHAR(100),
    `ativa` BOOLEAN NOT NULL DEFAULT 0,
    `approved_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `plano_id` INT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Planos_Instituicoes`
    FOREIGN KEY (`plano_id`) REFERENCES `Planos`(`id`)
);

/*
 * Instituicao de ensino padrao
 */
INSERT INTO `Instituicoes` 
(`nome_fantasia`, `razao_social`, `cnpj`, `email`, `ativa`, 
`approved_at`, `created_at`, `updated_at`, `plano_id`) 
VALUES
("UNICESUMAR", 
"CENTRO DE ENSINO SUPERIOR DE MARINGA LTDA", 
"79265617000199", 
"instituicao@unicesumar.edu.br", 
1, 
NOW(), 
NOW(), 
NULL, 
1);

/*
 * Criacao tabela Cursos
 */
DROP TABLE IF EXISTS `Cursos`;
CREATE TABLE `Cursos` (
    `id` INT NOT NULL,
    `nome` VARCHAR(100) NOT NULL,
    `caminho_imagem` VARCHAR(250) NULL,

    PRIMARY KEY (`id`)
);

/*
 * Cursos padrao
 */
INSERT INTO `Cursos` (`id`, `nome`, `caminho_imagem`) 
VALUES 
(1, "Administração", NULL),
(2, "Analise e desenvolvimento de Sistemas", NULL),
(3, "Arquitetura e Urbanismo", NULL),
(4, "Ciências Contábeis", NULL),
(5, "Ciência da Computação", NULL),
(6, "Direito", NULL),
(7, "Educação Física", NULL),
(8, "Enfermagem", NULL),
(9, "Engenharia Civil", NULL),
(10, "Engenharia da Computação", NULL),
(11, "Engenharia de Produção", NULL),
(12, "Engenharia de Software", NULL),
(13, "Gastronomia", NULL),
(14, "Medicina", NULL),
(15, "Medicina veterinária", NULL),
(16, "Pedagogia", NULL),
(17, "Psicologia", NULL);

/*
 * Criacao tabela relacionamento Instituicoes_Cursos
 */
DROP TABLE IF EXISTS `Instituicoes_Cursos`;
CREATE TABLE `Instituicoes_Cursos` (
    `instituicao_id` INT NOT NULL,
    `curso_id` INT NOT NULL,

    CONSTRAINT `FK_Instituicoes_Instituicoes_Cursos`
    FOREIGN KEY (`instituicao_id`) REFERENCES `Instituicoes`(`id`),

    CONSTRAINT `FK_Cursos_Instituicoes_Cursos`
    FOREIGN KEY (`curso_id`) REFERENCES `Cursos`(`id`)
);

/*
 * Cursos na nova instituicao
 */
INSERT INTO `Instituicoes_Cursos` VALUES 
(1,1), (1,2), (1,3), (1,4), (1,5), (1,6), (1,7),
(1,8), (1,9), (1,10), (1,11), (1,12), (1,13),
(1,14), (1,15), (1,16), (1,17);

/*
 * Criacao tabela de Estudantes
 */
DROP TABLE IF EXISTS `Estudantes`;
CREATE TABLE `Estudantes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(75) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `senha` VARCHAR(256) NOT NULL,
    `ativo` BOOLEAN NOT NULL DEFAULT 1,
    `caminho_foto_perfil` VARCHAR(250) NULL,
    `data_nascimento` DATE NOT NULL, 
    `deleted_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL, 
    `curso_id` INT NOT NULL,
    `instituicao_id` INT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Cursos_Estudantes`
    FOREIGN KEY (`curso_id`) REFERENCES `Cursos`(`id`),

    CONSTRAINT `FK_Instituicoes_Estudantes`
    FOREIGN KEY (`instituicao_id`) REFERENCES `Instituicoes`(`id`)
);

/*
 * Criacao tabela de Usuarios
 */
DROP TABLE IF EXISTS `Usuarios`;
CREATE TABLE `Usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(75) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `cpf` CHAR(11) NOT NULL UNIQUE,
    `senha` VARCHAR(256) NOT NULL,
    `data_nascimento` DATE NOT NULL,
    `ativo` BOOLEAN NOT NULL DEFAULT 1,
    `deleted_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `instituicao_id` INT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Usuarios_Instituicoes`
    FOREIGN KEY (`instituicao_id`) REFERENCES `Instituicoes`(`id`)
);

/*
 * Criacao tabela de Recuperacao_Senhas
 */
DROP TABLE IF EXISTS `Recuperacoes_Senhas`;
CREATE TABLE `Recuperacoes_Senhas` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `token` VARCHAR(256) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `completa` BOOLEAN NOT NULL DEFAULT 0,
    `expirada` BOOLEAN NOT NULL DEFAULT 0,
    `expiration_at` INT NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `estudante_id` INT NULL,
    `usuario_id` INT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Recuperacoes_Senhas_Estudantes`
    FOREIGN KEY (`estudante_id`) REFERENCES `Estudantes`(`id`),

    CONSTRAINT `FK_Recuperacoes_Senhas_Usuarios`
    FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios`(`id`)
);

/*
 * Criacao tabela de Enderecos
 */
DROP TABLE IF EXISTS `Enderecos`;
CREATE TABLE `Enderecos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `cep` CHAR(8) NULL,
    `logradouro` VARCHAR(150) NULL,
    `numero` VARCHAR(10) NULL,
    `complemento` VARCHAR(25),
    `ponto_referencia` VARCHAR(50),
    `atual` BOOLEAN NOT NULL DEFAULT 1,
    `deleted_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME,
    `estudante_id` INT NULL,
    `instituicao_id` INT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Enderecos_Estudantes`
    FOREIGN KEY (`estudante_id`) REFERENCES `Estudantes`(`id`),

    CONSTRAINT `FK_Enderecos_Instituicoes`
    FOREIGN KEY (`instituicao_id`) REFERENCES `Instituicoes`(`id`)
);

/*
 * Criacao tabela de Session
 */
DROP TABLE IF EXISTS `App_Sessions`;
CREATE TABLE `App_Sessions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `token` VARCHAR(256) NOT NULL,
    `expiration_time` INT NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `estudante_id` INT NULL,
    `usuario_id` INT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_App_Sessions_Estudantes`
    FOREIGN KEY (`estudante_id`) REFERENCES `Estudantes`(`id`),

    CONSTRAINT `FK_App_Sessions_Usuarios`
    FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios`(`id`)
);

/*
 * Criacao tabela de Tipos_Contatos
 */
DROP TABLE IF EXISTS `Tipos_Contatos`;
CREATE TABLE `Tipos_Contatos` (
    `id` INT NOT NULL,
    `descricao` VARCHAR(50) NOT NULL,

    PRIMARY KEY (`id`)
);

/*
 * Criacao Tipos_Contatos padrao
 */
INSERT INTO `Tipos_Contatos` (`id`, `descricao`)
VALUES (1, 'Telefone'), (2, 'Whatsapp'), (3, 'Telegram'), (4, 'E-mail');

/*
 * Criacao tabela de Contatos
 */
DROP TABLE IF EXISTS `Contatos`;
CREATE TABLE `Contatos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `conteudo` VARCHAR(100) NOT NULL,
    `deleted` BOOLEAN NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `tipo_contato_id` INT NOT NULL,
    `estudante_id` INT NOT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Contatos_Estudantes`
    FOREIGN KEY (`estudante_id`) REFERENCES `Estudantes`(`id`)
);

/*
 * Criacao tabela de Bloqueios
 */
DROP TABLE IF EXISTS `Bloqueios`;
CREATE TABLE `Bloqueios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `motivo` VARCHAR(250) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `finished_at` DATETIME NULL,
    `estudante_id` INT NOT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Bloqueios_Estudantes`
    FOREIGN KEY (`estudante_id`) REFERENCES `Estudantes`(`id`)
);

/*
 * Criacao tabela de Publicacoes
 */
DROP TABLE IF EXISTS `Publicacoes`;
CREATE TABLE `Publicacoes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(100) NOT NULL,
    `descricao` VARCHAR(250) NOT NULL,
    `especificacao_tecnica` VARCHAR(250) NULL,
    `valor` FLOAT NULL,
    `caminho_imagem` VARCHAR(250) NULL,
    `data_hora_finalizacao` DATETIME NULL,
    `deleted` BOOLEAN NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `curso_id` INT NULL,
    `estudante_id` INT NOT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Publicacoes_Cursos`
    FOREIGN KEY (`curso_id`) REFERENCES `Cursos`(`id`),

    CONSTRAINT `FK_Publicacoes_Estudantes`
    FOREIGN KEY (`estudante_id`) REFERENCES `Estudantes`(`id`)
);

/*
 * Criacao tabela de Tags
 */
DROP TABLE IF EXISTS `Tags`;
CREATE TABLE `Tags` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `conteudo` VARCHAR(50),

    PRIMARY KEY (`id`)
);

/*
 * Criacao tabela de relacionamento Publicacoes_Tags
 */
DROP TABLE IF EXISTS `Publicacoes_Tags`;
CREATE TABLE `Publicacoes_Tags` (
    `publicacao_id` INT NOT NULL, 
    `tag_id` INT NOT NULL,

    CONSTRAINT `FK_Publicacoes_Tags_Publicacoes`
    FOREIGN KEY (`publicacao_id`) REFERENCES `Publicacoes`(`id`),

    CONSTRAINT `FK_Publicacoes_Tags_Tags`
    FOREIGN KEY (`tag_id`) REFERENCES `Tags`(`id`)
);

/*
 * Criacao tabela Tipos_Movimentacoes
 */
DROP TABLE IF EXISTS `Tipos_Movimentacoes`;
CREATE TABLE `Tipos_Movimentacoes` (
    `id` INT NOT NULL,
    `descricao` VARCHAR(100) NOT NULL,

    PRIMARY KEY (`id`)
);

INSERT INTO `Tipos_Movimentacoes`
VALUES (1, 'Compra'), (2, 'Venda');

/*
 * Criacao tabela Movimentacoes
 */
DROP TABLE IF EXISTS `Movimentacoes`;
CREATE TABLE `Movimentacoes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `valor` FLOAT NULL,
    `created_at` DATETIME NOT NULL,
    `tipo_movimentacao_id` INT NOT NULL,
    `publicacao_id` INT NOT NULL,
    `estudante_id` INT NOT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Movimentacoes_Tipos_Movimentacoes`
    FOREIGN KEY (`tipo_movimentacao_id`) REFERENCES `Tipos_Movimentacoes`(`id`),

    CONSTRAINT `FK_Movimentacoes_Publicacoes`
    FOREIGN KEY (`publicacao_id`) REFERENCES `Publicacoes`(`id`),

    CONSTRAINT `FK_Movimentacoes_Estudantes`
    FOREIGN KEY (`estudante_id`) REFERENCES `Estudantes`(`id`)
);

/*
 * Criacao tabela Avaliacoes
 */
DROP TABLE IF EXISTS `Avaliacoes`;
CREATE TABLE `Avaliacoes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nota` INT NOT NULL,
    `observacao` VARCHAR(100) NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `movimentacao_id` INT NOT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Avaliacoes_Movimentacoes`
    FOREIGN KEY (`movimentacao_id`) REFERENCES `Movimentacoes`(`id`)
);

/*
 * Criacao tabela Denuncias
 */
DROP TABLE IF EXISTS `Denuncias`;
CREATE TABLE `Denuncias` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `descricao` VARCHAR(150) NOT NULL,
    `apurada` BOOLEAN NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `estudante_id_autor` INT NOT NULL,
    `estudante_id_denunciado` INT NOT NULL,
    `publicacao_id` INT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Denuncias_Estudantes_Autor`
    FOREIGN KEY (`estudante_id_autor`) REFERENCES `Estudantes`(`id`),

    CONSTRAINT `FK_Denuncias_Estudantes_Denunciado`
    FOREIGN KEY (`estudante_id_denunciado`) REFERENCES `Estudantes`(`id`),

    CONSTRAINT `FK_Denuncias_Publicacoes`
    FOREIGN KEY (`publicacao_id`) REFERENCES `Publicacoes`(`id`)
);