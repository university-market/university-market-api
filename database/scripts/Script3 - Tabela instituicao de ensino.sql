/*
 * Tabela de instituições
 */
DROP TABLE IF EXISTS `Instituicao`;
CREATE TABLE IF NOT EXISTS `Instituicao` (
    `instituicaoId` INT NOT NULL AUTO_INCREMENT,
    `nomeFantasia` VARCHAR(250) NULL,
    `razaoSocial` VARCHAR(250) NOT NULL,
    `nomeReduzido` VARCHAR(250) NULL,
    `cnpj` VARCHAR(25) NOT NULL UNIQUE,
    `email` VARCHAR(150) NULL,
    `telefone` VARCHAR(50) NULL,
    `dataHoraCadastro` DATETIME NOT NULL,
    `aprovada` BOOLEAN NOT NULL,
    `ativa` BOOLEAN NOT NULL,
    `planoId` INT NULL,

    PRIMARY KEY (`instituicaoId`),

    CONSTRAINT `FK_Plano_Instituicao`
    FOREIGN KEY (`planoId`) REFERENCES `Plano`(`planoId`)
);

INSERT INTO instituicao (`instituicaoId`,`nomeFantasia`,`razaoSocial`,`nomeReduzido`,`cnpj`,`email`,`telefone`,`dataHoraCadastro`,`aprovada`,`ativa`,`planoId`) 
VALUES (2,"UNICESUMAR","CENTRO DE ENSINO SUPERIOR DE MARINGA LTDA",null,79265617000199,"leonardo@senhadigital.com.br","30276360","2021-10-13 21:18:51",0,1,1);
