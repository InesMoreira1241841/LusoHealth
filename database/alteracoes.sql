-- ---------- NOTA DE OTIMIZAÇÃO ARQUITETURAL ----------

--  Após analisar a modelação da base de dados, conclui que a manutenção de um 
-- atributo textual livre para o fabricante na tabela principal gerava redundância 
-- e violava a Terceira Forma Normal (3FN). Ao estruturar uma relação de Muitos para
-- Muitos (N:M) com a entidade fornecedores e a remoção desta coluna elimina potenciais
-- elimina potenciais inconsistências de dados.

ALTER TABLE equipamentos DROP COLUMN 'fabricante';

ALTER TABLE equipamentos DROP COLUMN 'arquivado';


ALTER TABLE `garantias`
    DROP COLUMN `entidade_responsavel`,
    DROP COLUMN `clausulas`,
    ADD COLUMN `ficheiro_path` VARCHAR(255) DEFAULT NULL COMMENT 'Caminho do PDF local (Forma 1)' AFTER `periodicidade`,
    ADD COLUMN `url_externo` VARCHAR(255) DEFAULT NULL COMMENT 'Link para a Cloud/OneDrive (Forma 2)' AFTER `ficheiro_path`;

ALTER TABLE `garantias` 
    ADD COLUMN `arquivado` TINYINT(1) NOT NULL DEFAULT 0 AFTER observacoes;

ALTER TABLE `garantias`
    DROP COLUMN `tem_contrato_manutencao`;


ALTER TABLE `localizacoes`
    ADD COLUMN `arquivado` TINYINT(1) NOT NULL DEFAULT 0 AFTER observacoes;

ALTER TABLE `fornecedores`
    ADD COLUMN `arquivado` TINYINT(1) NOT NULL DEFAULT 0 AFTER observacoes;