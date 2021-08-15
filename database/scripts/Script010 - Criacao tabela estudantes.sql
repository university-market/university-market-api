USE `university_market_db`;
CREATE TABLE `Estudante` (
  `estudanteId` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(150) NOT NULL,
  `ra` VARCHAR(50) NOT NULL,
  `email` VARCHAR(250) NULL,
  `telefone` VARCHAR(50) NULL,
  `dataNascimento` DATE NOT NULL,
  `hashSenha` VARCHAR(280) NOT NULL,
  `pathFotoPerfil` VARCHAR(500) NULL,
  `ativo` BIT NOT NULL,
  `blocked` BIT NULL,
  `dataHoraFimBlock` DATETIME NULL,
  `dataHoraCadastro` DATETIME NOT NULL,
  `dataHoraExclusao` DATETIME NULL,
  `cursoId` INT NOT NULL,
  `instituicaoId` INT NOT NULL,
  PRIMARY KEY (`estudanteId`)
);

ALTER TABLE `Estudante`
ADD CONSTRAINT `FK_EstudanteIntituicao`
FOREIGN KEY (`instituicaoId`) REFERENCES `Instituicao`(`instituicaoId`);