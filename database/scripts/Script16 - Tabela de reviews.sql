/*
 * Criação tabela de review
 */
DROP TABLE IF EXISTS `Review`;
CREATE TABLE IF NOT EXISTS `Review` (
    `reviewId` INT NOT NULL AUTO_INCREMENT,
    `nota` INT NOT NULL,
    `observacao` VARCHAR(150) NULL,
    `movimentacaoId` INT NOT NULL,

    PRIMARY KEY (`reviewId`),

    CONSTRAINT `FK_Movimentacao_Review`
    FOREIGN KEY (`movimentacaoId`) REFERENCES `Movimentacao`(`movimentacaoId`)
);
