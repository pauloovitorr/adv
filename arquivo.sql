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
    tipo_pessoa varchar(50) NOT NULL,
    tipo_parte varchar(50) NOT NULL,
    usuario_config_id_usuario_config INT NOT NULL,
    
    CONSTRAINT pk_id_pessoa PRIMARY KEY (id_pessoa),

    CONSTRAINT fk_usuario_config FOREIGN KEY (usuario_config_id_usuario_config) REFERENCES usuario_config(id_usuario_config)
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


CREATE TABLE processo (
    id_processo INT AUTO_INCREMENT NOT NULL,
    cliente_id INT NOT NULL,
    contrario_id INT,
    grupo_acao VARCHAR(80) NOT NULL,
    tipo_acao_id VARCHAR(80) NOT NULL,
    referencia VARCHAR(8) NOT NULL,
    num_processo VARCHAR(50),
    num_protocolo VARCHAR(50),
    processo_originario VARCHAR(50),
    valor_causa VARCHAR(16),
    valor_honorarios VARCHAR(16),
    etapa_kanban VARCHAR(50),
    contingenciamento VARCHAR(50),
    data_requerimento DATE,
    resultado_processo VARCHAR(100),
    observacao TEXT,
    dt_cadastro_processo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT pk_processo PRIMARY KEY (id_processo),
    CONSTRAINT fk_processo FOREIGN KEY (cliente_id) REFERENCES pessoas(id_pessoa)
    
);


CREATE TABLE etapas_crm (

    id_etapas_crm INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    usuario_config_id_usuario_config INT NOT NULL,
    
    CONSTRAINT pk_etapas_crm PRIMARY KEY (id_etapas_crm),
    CONSTRAINT fk_etapas_crm FOREIGN KEY (usuario_config_id_usuario_config) REFERENCES usuario_config(id_usuario_config)
)

CREATE TABLE anotacoes_crm (
    id_anotacao_crm INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(60) NOT NULL,
    descricao TEXT NOT NULL,
    dt_cadastro_anotacoes DATETIME DEFAULT CURRENT_TIMESTAMP, 
    processo_id_processo INT NOT NULL,
        
    CONSTRAINT pk_anotacoes PRIMARY KEY (id_anotacao_crm),    
    CONSTRAINT fk_anotacoes_crm FOREIGN KEY (processo_id_processo) 
    REFERENCES processo(id_processo)
);


CREATE TABLE eventos_crm (

    id_evento_crm INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    all_day TINYINT(1) DEFAULT 0,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME NOT NULL,
    cor VARCHAR(20) DEFAULT '#007bff',
    usuario_config_id_usuario_config INT NOT NULL,

    CONSTRAINT pk_eventos_crm PRIMARY KEY (id_evento_crm),
    CONSTRAINT fk_eventos_crm FOREIGN KEY (usuario_config_id_usuario_config)
    REFERENCES usuario_config(id_usuario_config)
);



CREATE TABLE configuracao_modelo (
    id_configuracao_modelo INT NOT NULL AUTO_INCREMENT,
    fonte1 VARCHAR(100),
    fonte2 VARCHAR(100),
    area_atuacao_principal VARCHAR(50),
    banner VARCHAR(200),
    frase_inicial VARCHAR(150),
    frase_secundaria VARCHAR(150),
    telefone_whatsapp VARCHAR(14) NOT NULL,
    email VARCHAR(80) NOT NULL,
    sobre VARCHAR(200) NOT NULL,
    foto_adv VARCHAR(200) NOT NULL,
    areas_atuacao VARCHAR(200) NOT NULL,
    frase_chamada_cta VARCHAR(150) NOT NULL,
    frase_chamada_cta_secundaria VARCHAR(150) NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    estilizacao TEXT,
    dt_cadastro_modelo DATETIME DEFAULT CURRENT_TIMESTAMP,
    dt_atualizacao_modelo DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_id_configuracao_modelo PRIMARY KEY (id_configuracao_modelo)
);

CREATE TABLE depoimentos (
    id_depoimento INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    texto TEXT NOT NULL,
    dt_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_id_depoimento PRIMARY KEY (id_depoimento)
);

