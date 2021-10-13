/*
 * Criação tabela de sessão de estudantes
 */
DROP TABLE IF EXISTS `AppSession`;
CREATE TABLE IF NOT EXISTS `AppSession` (
    `sessionId` INT NOT NULL AUTO_INCREMENT,
    `sessionToken` VARCHAR(250) NOT NULL,
    `expirationTime` INT NULL,
    `estudanteId` INT NOT NULL,

    PRIMARY KEY (`sessionId`),

    CONSTRAINT `FK_Estudante_AppSession`
    FOREIGN KEY (`estudanteId`) REFERENCES `Estudante`(`estudanteId`)
);
