USE university_market_db;
CREATE TABLE Users (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    ra VARCHAR(10) NOT NULL,
    cpf VARCHAR(11) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    email VARCHAR(50) NOT NULL,
    curso VARCHAR(50) NOT null,
    telefone VARCHAR(12) NOT null,
    nivel_acesso VARCHAR(1) null,
    dataNasc date null,
    bloqued bit null,
    ultimo_login date null,
    token VARCHAR(50) null,
    excluido bit null,
    PRIMARY KEY (id)
);