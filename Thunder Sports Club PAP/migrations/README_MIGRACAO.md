# 🔧 Script de Migração da Base de Dados

## 📝 O que muda

Este script (`001_database_improvements.sql`) implementa as seguintes melhorias:

1. ✅ **Índices** - Melhora performance de queries
2. ✅ **Expandir limite de modalidades** - De 99 para 2+ milhões
3. ✅ **Segurança de tipos** - ENUM para cargo, NOT NULL para campos obrigatórios
4. ✅ **Timestamps** - created_at, updated_at para auditoria
5. ✅ **Normalizar pagamentos** - Consolidar valores inconsistentes (0/1/"Sim"/"Não" → ENUM)
6. ✅ **Soft deletes** - Recuperar dados deletados se necessário
7. ✅ **Tabela de logs** - Registar ações admin (aprovações, mudanças)

---

## 🚀 Como Executar

### Opção 1: Via phpMyAdmin (Recomendado)

1. Abra **phpMyAdmin** → `http://localhost/phpmyadmin`
2. Selecione base de dados `clube_desp`
3. Clique em **SQL**
4. Copie o conteúdo de `001_database_improvements.sql`
5. Cole na caixa de SQL
6. Clique **Go/Executar**

### Opção 2: Linha de Comando

```bash
mysql -u root -p12345 clube_desp < c:\xampp\htdocs\expap\migrations\001_database_improvements.sql
```

### Opção 3: Via PHP

```php
$arquivo = file_get_contents('migrations/001_database_improvements.sql');
$queries = explode(';', $arquivo);
foreach($queries as $query) {
    if(trim($query)) {
        mysqli_query($conexao, $query);
    }
}
```

---

## ⚠️ Antes de Executar

1. **FAÇA BACKUP** da base de dados!
   ```bash
   mysqldump -u root -p12345 clube_desp > backup_$(date +%Y%m%d_%H%M%S).sql
   ```

2. **Teste em ambiente de desenvolvimento** primeiro

3. **Verifique o passo 9** - Se quer consolidar `pedidos_treino` e `propostas_treino`, descomente essas linhas

---

## 🔄 Próximas Ações (Código PHP)

Após executar o script, você precisa atualizar o PHP em alguns ficheiros:

### 1. **Usar Logs de Auditoria**

Adicione em ficheiros críticos (admin_painel.php, processa_pagamento.php, etc.):

```php
// Registar quando aprova um treinador
$log_query = "INSERT INTO admin_logs (admin_id, action, target_table, target_id, new_value, created_at) 
              VALUES ('$admin_id', 'approve_trainer', 'propostas_treino', '$id_proposta', 
              JSON_OBJECT('trainer_id', '$user_id', 'modality', '$id_mod'), NOW())";
mysqli_query($conexao, $log_query);
```

### 2. **Verificar Status de Pagamento**

O campo `pago` é agora ENUM('pendente', 'pago', 'cancelado'):

```php
// Antes (ainda funciona):
if($row['pago'] == 1) { ... }

// Melhor (após migração):
if($row['pago'] == 'pago') { ... }
```

### 3. **Usar Soft Deletes**

Em vez de DELETE, usar:

```php
UPDATE users SET deleted_at = NOW() WHERE id_user = '$user_id';
// Recuperar normalmente com: WHERE deleted_at IS NULL
```

---

## ✅ Validação Após Execução

Para confirmar que tudo correu bem, execute estas queries no phpMyAdmin:

```sql
-- Verificar índices criados
SHOW INDEXES FROM users;
SHOW INDEXES FROM socios;

-- Verificar estrutura normalizada
DESCRIBE inscricao_mod_socio;  -- pago deve ser ENUM

-- Verificar tabela de logs
SELECT * FROM admin_logs LIMIT 1;

-- Contar registos (não deve perder nada)
SELECT COUNT(*) FROM socios;
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM inscricao_mod_socio;
```

---

## 🆘 Se der erro

- **"UNIQUE constraint"**: Já existe o índice (normal em execuções repetidas)
- **"Foreign key"**: Descomente o passo 8 com cuidado
- **"Dados inconsistentes"**: Verifique antes de executar

---

## 📞 Suporte

Se algo correr mal:
1. Restaure do backup
2. Verifique o log do MySQL
3. Execute o script em partes (comentando/descomentando sections)
