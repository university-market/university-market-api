/*
 * Criação tabela de bloqueios
 */
DROP TABLE IF EXISTS `Block`;
CREATE TABLE IF NOT EXISTS `Block` (
    `blockId` INT NOT NULL AUTO_INCREMENT,
    `ativo` BOOLEAN NOT NULL,
    `motivo` VARCHAR(250) NOT NULL,
    `dataHoraInicio` DATETIME NOT NULL,
    `dataHoraFim` DATETIME NOT NULL,
    `estudanteId` INT NOT NULL,

    PRIMARY KEY (`blockId`),

    CONSTRAINT `FK_Estudante_Block`
    FOREIGN KEY (`estudanteId`) REFERENCES `Estudante`(`estudanteId`)
);