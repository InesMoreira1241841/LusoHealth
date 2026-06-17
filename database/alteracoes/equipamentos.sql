-- ---------- NOTA DE OTIMIZAÇÂO ARQUITETURAL ----------

--  Após analisar a modelação da base de dados, conclui que a manutenção de um 
-- atributo textual livre para o fabricante na tabela principal gerava redundância 
-- e violava a Terceira Forma Normal (3FN). Ao estruturar uma relação de Muitos para
-- Muitos (N:M) com a entidade fornecedores e a remoção desta coluna elimina potenciais
-- elimina potenciais inconsistências de dados.

ALTER TABLE equipamentos DROP COLUMN fabricante;