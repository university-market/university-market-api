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