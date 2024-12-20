
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