CREATE TABLE `Situacao_Denuncias` (
    `id` INT NOT NULL,
    `situacao` VARCHAR(250) NOT NULL,
    `descricao` VARCHAR(250) NOT NULL,

    PRIMARY KEY (`id`)
);

INSERT INTO `Situacao_Denuncias` (`id`, `situacao`, `descricao`)
VALUES (1, 'Pendente', 'A denúncia foi enviada e será analisada pelos administradores');

INSERT INTO `Situacao_Denuncias` (`id`, `situacao`, `descricao`)
VALUES (2, 'Concluída', 'A denúncia foi apurada e as medidas cabíveis foram tomadas');

INSERT INTO `Situacao_Denuncias` (`id`, `situacao`, `descricao`)
VALUES (3, 'Arquivada', 'A denúncia foi arquivada pois a publicação foi reformulada pelo autor');

INSERT INTO `Situacao_Denuncias` (`id`, `situacao`, `descricao`)
VALUES (4, 'Excluída', 'A denúncia foi excluída pois não apresentou coerência nas informações');