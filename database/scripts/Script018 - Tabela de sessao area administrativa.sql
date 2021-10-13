/*
 * Criação tabela de sessão de usuários
 */
DROP TABLE IF EXISTS `AdminSession`;
CREATE TABLE IF NOT EXISTS `AdminSession` (
    `sessionId` INT NOT NULL AUTO_INCREMENT,
    `sessionToken` VARCHAR(250) NOT NULL,
    `expirationTime` INT NULL,
    `usuarioId` INT NOT NULL,

    PRIMARY KEY (`sessionId`),

    CONSTRAINT `FK_Usuario_AdminSession`
    FOREIGN KEY (`usuarioId`) REFERENCES `Usuario`(`usuarioId`)
);
