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

INSERT INTO Curso (`cursoId`,`nome`,`pathImagem`) VALUES (1,"Gastronomia",NULL),(2,"Direito",NULL),
(3,"Administração",NULL),(4,"Pedagogia",NULL),(5,"Engenharia Civil",NULL),(6,"Ciências Contábeis",NULL),
(7,"Enfermagem",NULL),(8,"Psicologia",NULL),(9,"Educação Física",NULL),(10,"Arquitetura e Urbanismo",NULL),
(11,"Engenharia de Produção",NULL),(12,"Analise e desenvolvimento de Sistemas",NULL),(13,"Engenharia de Software",NULL),
(14,"Ciência da Computação",NULL),(15,"Engenharia da Computação",NULL),(16,"Medicina veterinária",NULL),(17,"Medicina",NULL);

