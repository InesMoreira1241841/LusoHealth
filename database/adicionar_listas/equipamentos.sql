-- CÓDIGO GERADO POR IA

-- Ainda n foi adicionado à DB

START TRANSACTION;

-- Equipamento 1
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-00112-MOV', 'Incubadora de Transporte Ativa', 11, 'Dräger', 'TI500 Globe-Trotter', 'SN-TI50-88541', 2024, '2024-03-12', 18900.00, 'Compra', 'Ativo', 'Suporte de vida', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'PED-UCIN' LIMIT 1), 0, 'Bateria integrada de longa duração.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 13, 'Fabricante');

-- Equipamento 2
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-20202-LAB-002', 'Analisador Automático Bioquímica', 15, 'Roche', 'Cobas c 311', 'SN-C311-11245', 2023, '2023-09-15', 38500.00, 'Compra', 'Ativo', 'Alta', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'LAB-ANCL' LIMIT 1), 0, 'Processamento de até 300 testes por hora.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 26, 'Fabricante');

-- Equipamento 3
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-02953-EME', 'Monitor de Hemodiálise Hospitalar', 13, 'Baxter', 'AK 98', 'SN-AK98-99412', 2024, '2024-06-11', 19200.00, 'Compra', 'Ativo', 'Alta', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'HOS-HEMO' LIMIT 1), 0, 'Interface de utilizador tátil.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 29, 'Fabricante');

-- Equipamento 4
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-12304-IMG', 'Arco em C de Alta Resolução', 18, 'GE HealthCare', 'OEC One CFD', 'SN-OEC1-44214', 2024, '2024-01-10', 49500.00, 'Compra', 'Ativo', 'Alta', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'CIR-CBL1' LIMIT 1), 0, 'Detetor de painel plano CMOS.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 12, 'Fabricante');

-- Equipamento 5
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-17405-DIG', 'Tonómetro de Não-Contacto Digital', 17, 'IberBiomedica', 'CT-800', 'SN-CT8-77412', 2023, '2023-11-20', 5800.00, 'Compra', 'Ativo', 'Baixa', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'CEX-GAB02' LIMIT 1), 0, 'Sopro de ar suave com medição automática.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 16, 'Fabricante');

-- Equipamento 6
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-73926-MON', 'Oxímetro de Cabeceira Contínuo', 20, 'Nihon Kohden', 'OLV-4200', 'SN-OLV4-88541', 2024, '2024-08-01', 1250.00, 'Compra', 'Ativo', 'Média', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'HOS-MED2' LIMIT 1), 0, 'Alarme visual e sonoro configurável.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 44, 'Fabricante');

-- Equipamento 7
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-29407-VEN', 'Ventilador de Transporte Robusto', 19, 'Dräger', 'Oxylog 3000 plus', 'SN-OXY3-11245', 2024, '2024-04-14', 8700.00, 'Compra', 'Ativo', 'Suporte de vida', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'HOS-PUG' LIMIT 1), 0, 'Capacidade de ventilação não invasiva.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 13, 'Fabricante');

-- Equipamento 8
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-16008-IMG', 'Sistema Radiografia Digital Direta', 18, 'Siemens', 'Multix Impact', 'SN-MIMP-99854', 2023, '2023-10-18', 59000.00, 'Compra', 'Ativo', 'Alta', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'IMG-RX1' LIMIT 1), 0, 'Mesa flutuante motorizada.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 7, 'Fabricante');

-- Equipamento 9
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-87609-INJ', 'Estação de Acoplamento 4 Bombas', 21, 'B. Braun', 'SpaceStation', 'SN-SSTA-33412', 2024, '2024-07-20', 2100.00, 'Compra', 'Ativo', 'Alta', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'HOS-UCIP' LIMIT 1), 0, 'Gestão de cabos centralizada.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 28, 'Fabricante');

-- Equipamento 10
INSERT INTO `equipamentos` (`codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `arquivado`, `observacoes`, `criado_em`) VALUES 
('EQ-01076-MON', 'Monitor de ECG Holter Portátil', 20, 'Philips', 'DigiTrak XT', 'SN-DTXT-44512', 2024, '2024-02-14', 2800.00, 'Compra', 'Ativo', 'Média', (SELECT `id` FROM `localizacoes` WHERE `codigo` = 'HOS-CARD' LIMIT 1), 0, 'Gravador leve de 3 canais.', NOW());
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES (LAST_INSERT_ID(), 6, 'Fabricante');