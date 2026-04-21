-- Fix: Remove a constraint UNIQUE que falha com múltiplos NULL na coluna code
-- Executar este arquivo no phpMyAdmin se houver erro ao adicionar danh mục

-- 1. Drop a constraint UNIQUE existente se ela existir
ALTER TABLE categories 
DROP INDEX IF EXISTS uq_categories_code;

-- 2. Adicionar um índice NORMAL (não UNIQUE) na coluna code
-- Isso permite múltiplos valores NULL
ALTER TABLE categories 
ADD INDEX idx_categories_code (code);
