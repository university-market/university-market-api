/*
 * Criação tabela de publicação
 */
DROP TABLE IF EXISTS `Publicacao`;
CREATE TABLE IF NOT EXISTS `Publicacao` (
    `publicacaoId` INT NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(100) NOT NULL,
    `descricao` VARCHAR(250) NULL,
    `especificacoesTecnicas` VARCHAR(250) NULL,
    `valor` FLOAT NULL,
    `pathImagem` VARCHAR(250) NULL,
    `dataHoraCriacao` DATETIME NOT NULL,
    `dataHoraFinalizacao` DATETIME NULL,
    `excluida` BOOLEAN NOT NULL DEFAULT 0,
    `cursoId` INT NULL,
    `estudanteId` INT NOT NULL,

    PRIMARY KEY (`publicacaoId`),

    CONSTRAINT `FK_Curso_Publicacao`
    FOREIGN KEY (`cursoId`) REFERENCES `Curso`(`cursoId`),

    CONSTRAINT `FK_Estudante_Publicacao`
    FOREIGN KEY (`estudanteId`) REFERENCES `Estudante`(`estudanteId`)
);