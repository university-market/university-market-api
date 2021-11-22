/*
 * Criacao tabela Tipos_Denuncias
 */
DROP TABLE IF EXISTS `Tipos_Denuncias`;
CREATE TABLE `Tipos_Denuncias` (
    `id` INT NOT NULL,
    `descricao` VARCHAR(150) NOT NULL
    
    PRIMARY KEY (`id`)
);