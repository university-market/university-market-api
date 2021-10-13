/*
 * Criação tabela de estudantes
 */
DROP TABLE IF EXISTS `Estudante`;
CREATE TABLE IF NOT EXISTS `Estudante` (
    `estudanteId` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(75) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `telefone` VARCHAR(25) NULL,
    `dataNascimento` DATE NOT NULL,
    `hashSenha` VARCHAR(256) NOT NULL,
    `pathFotoPerfil` VARCHAR(250) NULL,
    `ativo` BOOLEAN NOT NULL,
    `dataHoraCadastro` DATETIME NOT NULL, 
    `cursoId` INT NOT NULL,
    `instituicaoId` INT NULL,

    PRIMARY KEY (`estudanteId`),

    CONSTRAINT `FK_Curso_Estudante`
    FOREIGN KEY (`cursoId`) REFERENCES `Curso`(`cursoId`),

    CONSTRAINT `FK_Instituicao_Estudante`
    FOREIGN KEY (`instituicaoId`) REFERENCES `Instituicao`(`instituicaoId`)
);