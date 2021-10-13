/*
 * Tabela de planos
 */
DROP TABLE IF EXISTS `Plano`;
CREATE TABLE IF NOT EXISTS `Plano` (
    `planoId` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,

    PRIMARY KEY (`planoId`)
);

INSERT INTO `Plano` (`planoId`, `nome`)
VALUES (1, 'Básico'), (2, 'Avançado');