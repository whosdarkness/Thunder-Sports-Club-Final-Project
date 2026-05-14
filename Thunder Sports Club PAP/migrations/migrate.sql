USE clube_desp;

-- Índices
ALTER TABLE socios ADD UNIQUE INDEX idx_cont_socio (cont_socio);
ALTER TABLE users ADD UNIQUE INDEX idx_email (email);
ALTER TABLE users ADD INDEX idx_cargo (cargo);
ALTER TABLE users ADD INDEX idx_num_socio (num_socio);
ALTER TABLE inscricao_mod_socio ADD INDEX idx_num_socio (num_socio);
ALTER TABLE inscricao_mod_socio ADD INDEX idx_id_mod (id_mod);
ALTER TABLE propostas_treino ADD INDEX idx_id_user (id_user);
ALTER TABLE propostas_treino ADD INDEX idx_aprovado (aprovado);
ALTER TABLE pagamentos ADD INDEX idx_id_user (id_user);
ALTER TABLE pagamentos ADD INDEX idx_id_mod (id_mod);
ALTER TABLE pedidos_treino ADD INDEX idx_id_user (id_user);
ALTER TABLE pedidos_treino ADD INDEX idx_estado (estado);

-- Expandir limites
ALTER TABLE modalidade MODIFY COLUMN id_mod int(11) NOT NULL AUTO_INCREMENT;

-- Tipos de dados
ALTER TABLE users MODIFY COLUMN cargo ENUM('socio', 'treinador', 'admin') DEFAULT 'socio';
ALTER TABLE users MODIFY COLUMN email varchar(50) NOT NULL;
ALTER TABLE socios MODIFY COLUMN telefone_socio int(9) NOT NULL;
ALTER TABLE socios MODIFY COLUMN email_socio varchar(100) NOT NULL;

-- Timestamps
ALTER TABLE socios ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE socios ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE inscricao_mod_socio ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Normalizar pago
ALTER TABLE inscricao_mod_socio ADD COLUMN pago_novo ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente';
UPDATE inscricao_mod_socio SET pago_novo = CASE WHEN pago = 1 OR LOWER(pago) = 'sim' OR LOWER(pago) = 'true' OR LOWER(pago) = 'yes' THEN 'pago' ELSE 'pendente' END;
ALTER TABLE inscricao_mod_socio DROP COLUMN pago;
ALTER TABLE inscricao_mod_socio CHANGE COLUMN pago_novo pago ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente';

-- Soft deletes
ALTER TABLE socios ADD COLUMN deleted_at TIMESTAMP NULL;
ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL;

-- Tabela de logs
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    target_table VARCHAR(100),
    target_id INT,
    old_value LONGTEXT,
    new_value LONGTEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id_user) ON DELETE CASCADE,
    INDEX idx_admin_id (admin_id),
    INDEX idx_created_at (created_at),
    INDEX idx_action (action)
);
