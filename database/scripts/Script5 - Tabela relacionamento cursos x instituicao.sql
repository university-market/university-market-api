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

insert instituicao_curso values(2,1),(2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),
(2,11),(2,12),(2,13),(2,14),(2,15),(2,16),(2,17);
