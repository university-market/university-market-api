/*
 * Criação tabela de movimentação
 */
DROP TABLE IF EXISTS `Movimentacao`;
CREATE TABLE IF NOT EXISTS `Movimentacao` (
    `movimentacaoId` INT NOT NULL AUTO_INCREMENT,
    `valor` FLOAT NULL,
    `dataHora` DATETIME NOT NULL,
    `tipoMovimentacaoId` INT NOT NULL,
    `publicacaoId` INT NOT NULL,
    `estudanteId` INT NOT NULL,

    PRIMARY KEY (`movimentacaoId`),

    CONSTRAINT `FK_TipoMovimentacao_Movimentacao`
    FOREIGN KEY (`tipoMovimentacaoId`) REFERENCES `TipoMovimentacao`(`tipoMovimentacaoId`),

    CONSTRAINT `FK_Publicacao_Movimentacao`
    FOREIGN KEY (`publicacaoId`) REFERENCES `Publicacao`(`publicacaoId`),

    CONSTRAINT `FK_Estudante_Movimentacao`
    FOREIGN KEY (`estudanteId`) REFERENCES `Estudante`(`estudanteId`)
);