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
    nivel_acesso VARCHAR(1) NOT null,
    date date NOT null,
    bloqued bit,
    ultimo_login date ,
    token VARCHAR(50),
    excluido bit,
    PRIMARY KEY (id)
);