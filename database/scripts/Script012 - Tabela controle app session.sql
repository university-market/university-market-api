USE `university_market_db`;

CREATE TABLE `AppSession` (
  `sessionId` INT NOT NULL AUTO_INCREMENT,
  `usuarioId` VARCHAR(150) NOT NULL,
  `sessionToken` VARCHAR(500),
  `dataHoraExpiracao` DATETIME,
  PRIMARY KEY (`sessionId`)
);