/*
 * Criacao Publicacaoes_favoritas
 */
DROP TABLE IF EXISTS `Publicacaoes_favoritas`;
CREATE TABLE `Publicacaoes_favoritas` (
    `publicacao_id` INT NOT NULL, 
    `estudante_id` INT NOT NULL,

    CONSTRAINT `FK_Publicacoes_Favoritas_Publicacoes`
    FOREIGN KEY (`publicacao_id`) REFERENCES `Publicacoes`(`id`),

    CONSTRAINT `FK_Publicacoes_Favoritas_Estudantes`
    FOREIGN KEY (`estudante_id`) REFERENCES `Estudantes`(`id`)
);