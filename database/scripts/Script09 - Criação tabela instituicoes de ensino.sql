USE `university_market_db`;
CREATE TABLE `Instituicao` (
    `instituicaoId` INT NOT NULL AUTO_INCREMENT,
    `nomeFantasia` VARCHAR(500) NULL,
    `razaoSocial` VARCHAR(500) NOT NULL,
    `nomeReduzido` VARCHAR(250) NULL,
    `cnpj` VARCHAR(50) NOT NULL,
    `cpfRepresentante` VARCHAR(50) NOT NULL,
    `emailContato` VARCHAR(150) NOT NULL,
    `dataHoraCriacao` DATE NOT NULL,
    `ativa` BIT NOT NULL,
    PRIMARY KEY (`instituicaoId`)
);