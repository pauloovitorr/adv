-- Usuário usuario_config
CREATE TABLE usuario_config (
    id_usuario_config INT NOT NULL AUTO_INCREMENT,
    tk VARCHAR(32) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    tell VARCHAR(14) NOT NULL,
    senha VARCHAR(250) NOT NULL,
    primeiro_acesso VARCHAR(3) NOT NULL DEFAULT 'sim',
    cpf VARCHAR(14) NULL,
    rg VARCHAR(14) NULL,
    oab_uf VARCHAR(45) NULL,
    dt_nascimento DATE NULL,
    dt_cadastro_usuario DATETIME NOT NULL,
    dt_atualizacao_usuario DATETIME NOT NULL,

    CONSTRAINT pk_usuario_config PRIMARY KEY (id_usuario_config)
) 


-- Criação da tabela de log
CREATE TABLE log (

    id_log INT NOT NULL AUTO_INCREMENT,
    acao_log VARCHAR(150) NOT NULL,
    ip_log VARCHAR(45) NOT NULL,
    dt_acao_log DATETIME NOT NULL,
    usuario_config_id_usuario_config INT NOT NULL,
    
    CONSTRAINT pk_log PRIMARY KEY (id_log),
    CONSTRAINT fk_log FOREIGN KEY (usuario_config_id_usuario_config) REFERENCES usuario_config (id_usuario_config)

)

-- Tabela para pessoa PF ou PJ
CREATE TABLE tipo_pessoa (

	id_tipo_pessoa INT NULL AUTO_INCREMENT,
	tipo varchar(2) NOT NULL,
    
    CONSTRAINT pk_tipo_pessoa PRIMARY KEY (id_tipo_pessoa)
)


-- Tabela para cadastro de pessoa
CREATE TABLE pessoas (
    id_pessoa INT NULL AUTO_INCREMENT,
    tk varchar(32) NOT NULL,
    nome varchar(220) NOT NULL,
    origem varchar(100) NOT NULL,
    dt_cadastro_pessoa DATETIME NOT NULL,
    dt_atualizacao_pessoa DATETIME NOT NULL,
    foto_pessoa varchar(220),
    num_documento varchar(220),
    rg varchar(25),
    anotacoes_pessoa varchar(250),
    dt_nascimento DATETIME,
    estado_civil varchar(45),
    profissao varchar(100),
    pis varchar(15),
    ctps varchar(100),
    sexo varchar(45),
    telefone_principal varchar(15),
    telefone_secundario varchar(15),
    celular varchar(15),
    email varchar(100),
    cep varchar(9),
    estado varchar(45),
    cidade varchar(150),
    bairro varchar(150),
    logradouro varchar(220),
    numero int,
    complemento varchar(150),
    nome_mae varchar(220),
    tipo_pessoa_id_tipo_pessoa INT NOT NULL,
    usuario_config_id_usuario_config INT NOT NULL,
    
    CONSTRAINT pk_id_pessoa PRIMARY KEY (id_pessoa),
    CONSTRAINT fk_tipo_pessoa FOREIGN KEY (tipo_pessoa_id_tipo_pessoa) REFERENCES tipo_pessoa(id_tipo_pessoa),
    CONSTRAINT fk_usuario_config FOREIGN KEY (usuario_config_id_usuario_config) REFERENCES usuario_config(id_usuario_config)
)


CREATE TABLE tipo_parte (

	id_tipo_parte INT NULL AUTO_INCREMENT,
	tipo varchar(10) NOT NULL,
    
    CONSTRAINT pk_tipo_parte PRIMARY KEY (id_tipo_parte)
)


CREATE TABLE documento (
    id_documento INT NOT NULL AUTO_INCREMENT,
    nome_original VARCHAR(255) NOT NULL,
    caminho_arquivo VARCHAR(500) NOT NULL,
    dt_criacao DATETIME NOT NULL,
    usuario_config_id_usuario_config INT NOT NULL,
    
    CONSTRAINT pk_documento PRIMARY KEY (id_documento),
    CONSTRAINT fk_documento_usuario FOREIGN KEY (usuario_config_id_usuario_config) REFERENCES usuario_config (id_usuario_config)
);
