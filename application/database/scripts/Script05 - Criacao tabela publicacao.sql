USE `university_market_db`;
CREATE TABLE `Publicacao` (
    `publicacaoId` INT NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(150) NOT NULL,
    `descricao` VARCHAR(500) NOT NULL,
    `tags` VARCHAR(250) NULL,
    `valor` NUMERIC(5,2) NOT NULL,
    `pathImagem` VARCHAR(250) NULL,
    `dataHoraCriacao` DATE NOT NULL,
    `dataHoraFinalizacao` DATE NULL,
    `dataHoraExclusao` DATE NULL,
    PRIMARY KEY (`publicacaoId`)
);