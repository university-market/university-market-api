/*
 * Criação tabela de tipo de movimentação
 */
DROP TABLE IF EXISTS `TipoMovimentacao`;
CREATE TABLE IF NOT EXISTS `TipoMovimentacao` (
    `tipoMovimentacaoId` INT NOT NULL,
    `identificacao` VARCHAR(100) NOT NULL,

    PRIMARY KEY (`tipoMovimentacaoId`) 
);

INSERT INTO `TipoMovimentacao` (`tipoMovimentacaoId`, `identificacao`)
VALUES (1, 'Compra'), (2, 'Venda');