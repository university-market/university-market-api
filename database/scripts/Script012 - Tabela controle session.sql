USE `university_market_db`;

CREATE TABLE `SessionTable` (
  `sessionId` INT NOT NULL AUTO_INCREMENT,
  `usuarioId` VARCHAR(150) NOT NULL,
  `sessionTipoId` INT NOT NULL,
  `sessionToken` VARCHAR(500),
  `dataHoraExpiracao` DATETIME,
  PRIMARY KEY (`sessionId`)
);