/*
 * Criação tabela de usuário
 */
DROP TABLE IF EXISTS `Usuario`;
CREATE TABLE IF NOT EXISTS `Usuario` (
    `usuarioId` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `cpf` VARCHAR(50) NOT NULL UNIQUE,
    `hashSenha` VARCHAR(250) NOT NULL,
    `dataNascimento` DATE NOT NULL,
    `dataHoraCadastro` DATETIME NOT NULL,
    `ativo` BOOLEAN NOT NULL,
    `instituicaoId` INT NOT NULL,

    PRIMARY KEY (`usuarioId`),

    CONSTRAINT `FK_Instituicao_Usuario`
    FOREIGN KEY (`instituicaoId`) REFERENCES `Instituicao`(`instituicaoId`)
);