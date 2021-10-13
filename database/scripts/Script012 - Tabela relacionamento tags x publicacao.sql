/*
 * Criação tabela de relacionamento tags x publicacao
 */
DROP TABLE IF EXISTS `Tag_Publicacao`;
CREATE TABLE IF NOT EXISTS `Tag_Publicacao` (
    `tagId` INT NOT NULL,
    `publicacaoId` INT NOT NULL,

    CONSTRAINT `FK_Tag_Tag_Publicacao`
    FOREIGN KEY (`tagId`) REFERENCES `Tag`(`tagId`),

    CONSTRAINT `FK_Publicacao_Tag_Publicacao`
    FOREIGN KEY (`publicacaoId`) REFERENCES `Publicacao`(`publicacaoId`)
);
