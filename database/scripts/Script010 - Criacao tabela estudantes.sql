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
  `blocked` BIT NOT NULL,
  `dataHoraFimBlock` DATE NOT NULL,
  `dataHoraCadastro` DATE NOT NULL,
  `dataHoraExclusao` DATE NULL,
  `cursoId` INT NOT NULL,
  `instituicaoId` INT NOT NULL,
  PRIMARY KEY (`estudanteId`)
);