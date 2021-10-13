/*
 * Criação tabela de pontuação
 */
DROP TABLE IF EXISTS `Score`;
CREATE TABLE IF NOT EXISTS `Score` (
    `scoreId` INT NOT NULL AUTO_INCREMENT,
    `pontuacao` FLOAT NULL,
    `comprador` BOOLEAN NOT NULL,
    `vendedor` BOOLEAN NOT NULL,
    `estudanteId` INT NOT NULL,

    PRIMARY KEY (`scoreId`),

    CONSTRAINT `FK_Estudante_Score`
    FOREIGN KEY (`estudanteId`) REFERENCES `Estudante`(`estudanteId`)
);