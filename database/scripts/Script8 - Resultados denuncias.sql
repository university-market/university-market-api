CREATE TABLE `Resultados_Denuncias` (
    `id` INT NOT NULL,
    `resultado` VARCHAR(250) NOT NULL,

    PRIMARY KEY (`id`)
);

INSERT INTO `Resultados_Denuncias` (`id`, `resultado`)
VALUES (1, 'A publicação denunciada foi excluída pelo administrador');

INSERT INTO `Resultados_Denuncias` (`id`, `resultado`)
VALUES (2, 'A publicação denunciada foi excluída pelo proprietário (autor)');

INSERT INTO `Resultados_Denuncias` (`id`, `resultado`)
VALUES (3, 'O autor da publicação foi notificado pelo administrador');

INSERT INTO `Resultados_Denuncias` (`id`, `resultado`)
VALUES (4, 'O autor da publicação teve a conta temporariamente bloqueada pelo administrador');

INSERT INTO `Resultados_Denuncias` (`id`, `resultado`)
VALUES (5, 'O autor da publicação teve a conta definitivamente bloqueada pelo administrador');

INSERT INTO `Resultados_Denuncias` (`id`, `resultado`)
VALUES (6, 'A denúncia foi arquivada pelo administrador pois a denúncia é incoerente em suas informações');

INSERT INTO `Resultados_Denuncias` (`id`, `resultado`)
VALUES (7, 'O autor da denúncia foi notificado pelo administrador por realizar uma denúncia incoerente');

INSERT INTO `Resultados_Denuncias` (`id`, `resultado`)
VALUES (8, 'O autor da denúncia foi bloqueado pelo administrador');