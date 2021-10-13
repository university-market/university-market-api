/*
 * Criação tabela de denuncias
 */
DROP TABLE IF EXISTS `Denuncia`;
CREATE TABLE IF NOT EXISTS `Denuncia` (
    `denunciaId` INT NOT NULL,
    `descricao` VARCHAR(150) NULL,
    `dataHoraCriacao` DATETIME NOT NULL,
    `apurada` BOOLEAN NOT NULL,
    `estudanteId_autor` INT NOT NULL,
    `estudanteId_denunciado` INT NOT NULL,
    `movimentacaoId` INT NULL,

    PRIMARY KEY (`denunciaId`),

    CONSTRAINT `FK_Estudante_Autor_Denuncia`
    FOREIGN KEY (`estudanteId_autor`) REFERENCES `Estudante`(`estudanteId`),

    CONSTRAINT `FK_Estudante_Denunciado_Denuncia`
    FOREIGN KEY (`estudanteId_denunciado`) REFERENCES `Estudante`(`estudanteId`),

    CONSTRAINT `FK_Movimentacao_Denuncia`
    FOREIGN KEY (`movimentacaoId`) REFERENCES `Movimentacao`(`movimentacaoId`)
);
