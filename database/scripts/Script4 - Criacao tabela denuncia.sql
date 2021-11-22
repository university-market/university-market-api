/*
 * Criacao tabela Denuncias
 */
DROP TABLE IF EXISTS `Denuncias`;
CREATE TABLE `Denuncias` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `descricao` VARCHAR(150) NOT NULL,
    `apurada` BOOLEAN NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    `estudante_id_autor` INT NOT NULL,
    `estudante_id_denunciado` INT NOT NULL,
    `publicacao_id` INT NULL,
    `tipo_denuncia_id` INT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `FK_Denuncias_Estudantes_Autor`
    FOREIGN KEY (`estudante_id_autor`) REFERENCES `Estudantes`(`id`),

    CONSTRAINT `FK_Denuncias_Estudantes_Denunciado`
    FOREIGN KEY (`estudante_id_denunciado`) REFERENCES `Estudantes`(`id`),

    CONSTRAINT `FK_Denuncias_Publicacoes`
    FOREIGN KEY (`publicacao_id`) REFERENCES `Publicacoes`(`id`),
    
    CONSTRAINT `FK_Tipos_denuncias`
    FOREIGN KEY (`tipo_denuncia_id`) REFERENCES `Tipos_Denuncias`(`id`)
);
