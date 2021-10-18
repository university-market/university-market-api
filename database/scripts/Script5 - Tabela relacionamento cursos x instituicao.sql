/*
 * Tabela de relacionamento instituicao x curso
 */
DROP TABLE IF EXISTS `Instituicao_Curso`;
CREATE TABLE IF NOT EXISTS `Instituicao_Curso` (
    `instituicaoId` INT NOT NULL,
    `cursoId` INT NOT NULL,

    CONSTRAINT `FK_Instituicao_Instituicao_Curso`
    FOREIGN KEY (`instituicaoId`) REFERENCES `Instituicao`(`instituicaoId`),

    CONSTRAINT `FK_Curso_Instituicao_Curso`
    FOREIGN KEY (`cursoId`) REFERENCES `Curso`(`cursoId`)
);

/*
 * Cursos na nova instituicao
 */
INSERT INTO `Instituicao_Curso` VALUES 
(1,1), (1,2), (1,3), (1,4), (1,5), (1,6), (1,7),
(1,8), (1,9), (1,10), (1,11), (1,12), (1,13),
(1,14), (1,15), (1,16), (1,17);