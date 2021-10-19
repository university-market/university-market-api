/*
 * Tabela de cursos
 */
DROP TABLE IF EXISTS `Curso`;
CREATE TABLE IF NOT EXISTS `Curso` (
    `cursoId` INT NOT NULL,
    `nome` VARCHAR(100) NOT NULL,
    `pathImagem` VARCHAR(250) NULL,

    PRIMARY KEY (`cursoId`)
);

/*
 * Cursos padrao
 */
INSERT INTO `Curso` (`cursoId`, `nome`, `pathImagem`) 
VALUES 
(1, "Administração", NULL),
(2, "Analise e desenvolvimento de Sistemas", NULL),
(3, "Arquitetura e Urbanismo", NULL),
(4, "Ciências Contábeis", NULL),
(5, "Ciência da Computação", NULL),
(6, "Direito", NULL),
(7, "Educação Física", NULL),
(8, "Enfermagem", NULL),
(9, "Engenharia Civil", NULL),
(10, "Engenharia da Computação", NULL),
(11, "Engenharia de Produção", NULL),
(12, "Engenharia de Software", NULL),
(13, "Gastronomia", NULL),
(14, "Medicina", NULL),
(15, "Medicina veterinária", NULL),
(16, "Pedagogia", NULL),
(17, "Psicologia", NULL);