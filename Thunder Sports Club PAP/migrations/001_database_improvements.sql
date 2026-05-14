-- ============================================================
-- SCRIPT DE MIGRAÇÃO: Melhorias Base de Dados Thunder Sports
-- ============================================================
-- Este script melhora a estrutura da BD para robustez,
-- performance e auditoria. Execute com cuidado!
-- ============================================================

-- ============================================================
-- PASSO 1: Adicionar Índices (SEM risco, melhora performance)
-- ============================================================

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

-- ============================================================
-- PASSO 2: Expandir Limites de IDs
-- ============================================================

-- Modalidades: de int(2) para int(11) - permite mais de 99
ALTER TABLE modalidade MODIFY COLUMN id_mod int(11) NOT NULL AUTO_INCREMENT;

-- ============================================================
-- PASSO 3: Melhorar Tipos de Dados
-- ============================================================

-- Mudar cargo de int para ENUM (mais seguro e legível)
-- Primeiro, verificar dados existentes
-- Se houver valores além de 0,1,2,3, precisam ser mapeados manualmente

ALTER TABLE users MODIFY COLUMN cargo ENUM('socio', 'treinador', 'admin') DEFAULT 'socio';

-- Garantir que email é NOT NULL (obrigatório)
ALTER TABLE users MODIFY COLUMN email varchar(50) NOT NULL;

-- Tornar telefone obrigatório em socios
ALTER TABLE socios MODIFY COLUMN telefone_socio int(9) NOT NULL;

-- Tornar email obrigatório em socios
ALTER TABLE socios MODIFY COLUMN email_socio varchar(100) NOT NULL;

-- ============================================================
-- PASSO 4: Adicionar Timestamps para Auditoria
-- ============================================================

ALTER TABLE socios ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE socios ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE inscricao_mod_socio ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ============================================================
-- PASSO 5: Consolidar Status de Pagamento
-- ============================================================

-- Normalizar campo 'pago' em inscricao_mod_socio para ENUM
-- (0/1 e texto "Sim"/"Não" para ENUM consistente)
-- Primeiro, cria coluna temporária
ALTER TABLE inscricao_mod_socio ADD COLUMN pago_novo ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente';

-- Migrar dados (1/'Sim'/true -> 'pago', resto -> 'pendente')
UPDATE inscricao_mod_socio 
SET pago_novo = CASE 
    WHEN pago = 1 OR LOWER(pago) = 'sim' OR LOWER(pago) = 'true' OR LOWER(pago) = 'yes' THEN 'pago'
    ELSE 'pendente'
END;

-- Remover coluna antiga e renomear nova
ALTER TABLE inscricao_mod_socio DROP COLUMN pago;
ALTER TABLE inscricao_mod_socio CHANGE COLUMN pago_novo pago ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente';

-- ============================================================
-- PASSO 6: Adicionar Soft Deletes
-- ============================================================

ALTER TABLE socios ADD COLUMN deleted_at TIMESTAMP NULL;
ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL;

-- ============================================================
-- PASSO 7: Criar Tabela de Logs de Auditoria
-- ============================================================

CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(255) NOT NULL COMMENT 'Tipo de ação: approve_trainer, reject_trainer, mark_paid, etc.',
    target_table VARCHAR(100) COMMENT 'Tabela afetada',
    target_id INT COMMENT 'ID do registro afetado',
    old_value LONGTEXT COMMENT 'Valor anterior (JSON)',
    new_value LONGTEXT COMMENT 'Novo valor (JSON)',
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id_user) ON DELETE CASCADE,
    INDEX idx_admin_id (admin_id),
    INDEX idx_created_at (created_at),
    INDEX idx_action (action)
);

-- ============================================================
-- PASSO 8: Adicionar Foreign Keys Explícitas
-- ============================================================

-- Verificar se já existem (podem dar erro se já existirem)
-- Descomente se necessário:

-- ALTER TABLE users ADD CONSTRAINT fk_users_num_socio 
--     FOREIGN KEY (num_socio) REFERENCES socios(num_socio) ON DELETE SET NULL;

-- ALTER TABLE inscricao_mod_socio ADD CONSTRAINT fk_insc_mod 
--     FOREIGN KEY (id_mod) REFERENCES modalidade(id_mod) ON DELETE CASCADE;

-- ALTER TABLE inscricao_mod_socio ADD CONSTRAINT fk_insc_socio 
--     FOREIGN KEY (num_socio) REFERENCES socios(num_socio) ON DELETE CASCADE;

-- ALTER TABLE pagamentos ADD CONSTRAINT fk_pag_user 
--     FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE;

-- ALTER TABLE pagamentos ADD CONSTRAINT fk_pag_mod 
--     FOREIGN KEY (id_mod) REFERENCES modalidade(id_mod) ON DELETE CASCADE;

-- ALTER TABLE propostas_treino ADD CONSTRAINT fk_prop_user 
--     FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE;

-- ALTER TABLE propostas_treino ADD CONSTRAINT fk_prop_mod 
--     FOREIGN KEY (id_mod) REFERENCES modalidade(id_mod) ON DELETE CASCADE;

-- ALTER TABLE pedidos_treino ADD CONSTRAINT fk_ped_user 
--     FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE;

-- ALTER TABLE pedidos_treino ADD CONSTRAINT fk_ped_mod 
--     FOREIGN KEY (id_mod) REFERENCES modalidade(id_mod) ON DELETE CASCADE;

-- ============================================================
-- PASSO 9: Consolidar Tabelas Redundantes (OPCIONAL)
-- ============================================================

-- Se `pedidos_treino` e `propostas_treino` são duplicados, 
-- você pode consolidar em uma única tabela.
-- Descomente apenas se tiver certeza que pode remover uma:

-- -- Mover dados de pedidos_treino para propostas_treino (se tiverem estrutura similar)
-- -- Isso precisa análise manual!
-- -- INSERT INTO propostas_treino (id_user, id_mod, aprovado, data_pedido) 
-- -- SELECT id_user, id_mod, CASE WHEN estado = 'aprovado' THEN 1 ELSE 0 END, data_pedido 
-- -- FROM pedidos_treino WHERE id_user NOT IN (SELECT id_user FROM propostas_treino);
-- -- DROP TABLE pedidos_treino;

-- ============================================================
-- PASSO 10: Limpeza e Validação
-- ============================================================

-- Verificar integridade dos dados após migração
-- (Descomente para validar)

-- SELECT COUNT(*) as socios_sem_email FROM socios WHERE email_socio IS NULL OR email_socio = '';
-- SELECT COUNT(*) as socios_sem_telefone FROM socios WHERE telefone_socio IS NULL OR telefone_socio = 0;
-- SELECT COUNT(*) as users_sem_email FROM users WHERE email IS NULL OR email = '';
-- SELECT COUNT(*) as duplicate_emails FROM (
--     SELECT email FROM users GROUP BY email HAVING COUNT(*) > 1
-- ) as dupes;

-- ============================================================
-- FIM DO SCRIPT
-- ============================================================

-- Executar com sucesso! 
-- Próximas ações:
-- 1. Testar a aplicação (meus_dados.php, treinador_dados.php, etc.)
-- 2. Atualizar código PHP se necessário (ex: check de `pago` agora é ENUM)
-- 3. Implementar logging de auditoria nos ficheiros PHP críticos
-- 4. Fazer backup antes de executar em produção!
