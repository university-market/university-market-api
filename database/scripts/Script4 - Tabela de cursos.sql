/*
 * Tabela de cursos
 */
DROP TABLE IF EXISTS `Curso`;
CREATE TABLE IF NOT EXISTS `Curso` (
    `cursoId` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `pathImagem` VARCHAR(250) NULL,

    PRIMARY KEY (`cursoId`)
);
