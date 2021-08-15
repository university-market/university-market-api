USE `university_market_db`;
CREATE TABLE `SessionTipo` (
  `sessionTipoId` INT NOT NULL,
  `descricao` VARCHAR(150) NOT NULL
);

INSERT INTO `SessionTipo` (`sessionTipoId`, `descricao`)
VALUES 
(1, 'Administrador'), (2, 'Estudante');