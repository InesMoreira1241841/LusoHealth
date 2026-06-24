-- --------------------------------------------------------
-- Anfitrião:                    vsgate-s1.dei.isep.ipp.pt
-- Versão do servidor:           8.0.45 - MySQL Community Server - GPL
-- SO do servidor:               Linux
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- A despejar estrutura para tabela db1241841.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL COMMENT 'Ex: Monitorização, Suporte de vida',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_categorias_nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.categorias: ~11 rows (aproximadamente)
INSERT INTO `categorias` (`id`, `nome`) VALUES
	(9, 'Consumíveis Médicos Descartáveis'),
	(18, 'Equipamento De Diagnóstico Por Imagem'),
	(17, 'Equipamentos De Diagnóstico Funcional'),
	(13, 'Equipamentos De Emergência / Reanimação'),
	(12, 'Equipamentos De Esterilização'),
	(15, 'Equipamentos De Laboratório'),
	(11, 'Equipamentos De Mobilidade / Apoio Ao Doente'),
	(20, 'Equipamentos De Monitorização'),
	(19, 'Equipamentos De Ventilação / Suporte Respiratório'),
	(16, 'Instrumentos Cirúrgicos'),
	(14, 'Material De Curativos / Tratamento De Feridas'),
	(21, 'Material De Injeção / Prefusão'),
	(10, 'Próteses E Ortóteses');

-- A despejar estrutura para tabela db1241841.conteudos_publicos
CREATE TABLE IF NOT EXISTS `conteudos_publicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chave` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT 'Ex: nome_hospital, telefone, email, texto_home',
  `valor` text COLLATE utf8mb4_bin NOT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_conteudos_publicos_chave` (`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.conteudos_publicos: ~12 rows (aproximadamente)
INSERT INTO `conteudos_publicos` (`id`, `chave`, `valor`, `atualizado_em`) VALUES
	(1, 'titulo_home', 'Inovação e Eficiência na Gestão de Equipamentos Médicos', '2026-06-21 09:44:59'),
	(2, 'texto_home', 'A <strong class="text-success-custom">LusoHealth</strong> impulsiona a transformação digital no setor da saúde através de soluções inteligentes para a gestão hospitalar. O nosso sistema de inventário clínico foi desenvolvido para responder às exigências da Engenharia Biomédica, garantindo segurança, rastreabilidade e maior eficiência na gestão de equipamentos médicos.', '2026-06-21 09:42:54'),
	(3, 'texto_sobre_projeto_1', 'O <strong>LusoHealth</strong> é um sistema web desenvolvido no âmbito da unidade curricular de <strong>Sistemas de Informação e Bases de Dados Aplicados à Saúde (SIBDAS)</strong>, integrada no curso de Licenciatura em <strong>Engenharia Biomédica</strong> do <a href="https://www.isep.ipp.pt" target="_blank" class="text-success-custom fw-semibold text-decoration-none">Instituto Superior de Engenharia do Porto (ISEP)</a>.', '2026-06-21 09:42:54'),
	(4, 'texto_sobre_projeto_2', 'Este projeto tem como objetivo principal o desenvolvimento de uma plataforma inteligente para o inventário clínico hospitalar, otimizando a rastreabilidade, gestão de fornecedores e controlo de criticidade de equipamentos médicos essenciais ao ecossistema de saúde.', '2026-06-21 09:42:54'),
	(5, 'texto_servicos', 'A <strong>LusoHealth</strong> disponibiliza uma plataforma integrada que otimiza a gestão, rastreabilidade e controlo de equipamentos médicos, promovendo maior eficiência e segurança no ambiente hospitalar.', '2026-06-21 09:42:54'),
	(6, 'texto_servico_equipamentos', 'Consulta e gestão do inventário clínico, com monitorização de criticidade, localização e histórico de manutenção preventiva.', '2026-06-21 09:42:54'),
	(7, 'texto_servico_localizacoes', 'Visualização da distribuição dos ativos por serviços hospitalares, garantindo resposta rápida e maior eficiência operacional.', '2026-06-21 09:42:54'),
	(8, 'texto_servico_fornecedores', 'Centralização de contactos, contratos de manutenção e garantias equipamentos médicos essenciais.', '2026-06-21 09:42:54'),
	(9, 'telefone', '+35196XXXXXXX', '2026-06-21 09:42:54'),
	(10, 'telefone_display', '96X XXX XXX', '2026-06-21 09:42:54'),
	(11, 'email', 'contacto@lusohealth.pt', '2026-06-21 09:42:54'),
	(12, 'texto_fale_connosco', 'Simplifique a gestão de inventário e a rastreabilidade de dispositivos médicos. Desenvolvido para responder às reais exigências da Engenharia Biomédica, o LusoHealth é o parceiro ideal para monitorizar a criticidade e conformidade das suas tecnologias de saúde.', '2026-06-21 09:42:54');

-- A despejar estrutura para tabela db1241841.documentos
CREATE TABLE IF NOT EXISTS `documentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT 'Manual do Utilizador / Manual de Serviço / Certificado de Calibração / etc.',
  `nome_documento` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `data_documento` date DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `notas` text COLLATE utf8mb4_bin,
  `ficheiro_path` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Caminho do PDF local (Forma 1)',
  `url_externo` varchar(2000) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Link para a Cloud/OneDrive (Forma 2)',
  `arquivado` tinyint(1) NOT NULL DEFAULT '0',
  `criado_em` datetime NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id`),
  KEY `FK_documentos_equipamento` (`equipamento_id`),
  KEY `FK_documentos_fornecedor` (`fornecedor_id`),
  CONSTRAINT `FK_documentos_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_documentos_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.documentos: ~0 rows (aproximadamente)
INSERT INTO `documentos` (`id`, `equipamento_id`, `fornecedor_id`, `tipo`, `nome_documento`, `data_documento`, `data_validade`, `notas`, `ficheiro_path`, `url_externo`, `arquivado`, `criado_em`) VALUES
	(1, 153, 6, 'Manual', 'Product Brochure Philips IntelliVue Portable patient monitor MPr', '2026-04-07', NULL, '', NULL, 'https://www.documents.philips.com/assets/20170523/bff818aacaea49339049a77c014f219d.pdf', 0, '2026-06-20 22:03:06');

-- A despejar estrutura para tabela db1241841.equipamentos
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo_inventario` varchar(30) COLLATE utf8mb4_bin NOT NULL COMMENT 'Ex: INV-0091',
  `designacao` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `marca` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `modelo` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `num_serie` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `ano_fabrico` year DEFAULT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `custo_aquisicao` decimal(10,2) DEFAULT NULL,
  `tipo_entrada` enum('Compra','Doação','Aluguer','Empréstimo') COLLATE utf8mb4_bin NOT NULL,
  `estado` enum('Ativo','Em manutenção','Em calibração','Em quarentena','Abatido') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `criticidade` enum('Baixa','Média','Alta','Suporte de vida') COLLATE utf8mb4_bin NOT NULL,
  `localizacao_id` int DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `criado_em` datetime NOT NULL DEFAULT (now()),
  `atualizado_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_equipamentos_codigo` (`codigo_inventario`),
  UNIQUE KEY `UQ_equipamentos_num_serie` (`num_serie`),
  KEY `FK_equipamentos_categoria` (`categoria_id`),
  KEY `FK_equipamentos_localizacao` (`localizacao_id`),
  CONSTRAINT `FK_equipamentos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_equipamentos_localizacao` FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `CK_equipamentos_custo` CHECK ((`custo_aquisicao` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=1388 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.equipamentos: ~152 rows (aproximadamente)
INSERT INTO `equipamentos` (`id`, `codigo_inventario`, `designacao`, `categoria_id`, `marca`, `modelo`, `num_serie`, `ano_fabrico`, `data_aquisicao`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
	(2, 'EQ-12345-EST', 'Destilador De Água De Laboratório', 12, 'Steris', 'Aquastat 5l', 'SN-AQ5L-11542', '2023', '2023-04-01', 950.00, 'Compra', 'Abatido', 'Baixa', 64, 'Produção autónoma de água destilada de alta pureza para autoclaves.', '2026-06-17 23:34:38', NULL),
	(153, 'EQ-00145-MON', 'Monitor De Sinais Vitais', 20, 'Philips', 'Intellivue Mp5', 'SN-MP5-99812', '2024', '2024-03-15', 4250.00, 'Compra', 'Ativo', 'Alta', 23, 'Ecrã tátil com módulo de capnografia ativo.', '2026-06-17 23:57:19', '2026-06-20 19:31:59'),
	(1238, 'EQ-00001-AAA', 'Monitor Multiparamétrico de Sinais Vitais', 20, 'Philips', 'IntelliVue MX450', 'MP-2022-45873', '2022', '2022-03-15', 18500.00, 'Compra', 'Ativo', 'Suporte de vida', 23, 'UCI Principal - 24h monitorização contínua', '2026-06-24 00:08:48', NULL),
	(1239, 'EQ-00002-BBB', 'Monitor Multiparamétrico Portátil', 20, 'GE Healthcare', 'Carescape B450', 'GE-2021-33021', '2021', '2021-06-10', 14200.00, 'Compra', 'Ativo', 'Alta', 22, 'Urgência - triagem e transporte', '2026-06-24 00:08:48', NULL),
	(1240, 'EQ-00003-CCC', 'Monitor de Transporte Neonatal', 20, 'Philips', 'IntelliVue MP5', 'MP5-2023-88120', '2023', '2023-01-20', 9800.00, 'Compra', 'Ativo', 'Suporte de vida', 35, 'Neonatologia - incubadora 3', '2026-06-24 00:08:48', NULL),
	(1241, 'EQ-00004-DDD', 'Monitor de Telemetria Cardíaca', 20, 'Mindray', 'T1', 'MR-2020-55110', '2020', '2020-09-05', 6500.00, 'Compra', 'Ativo', 'Alta', 24, 'Coronários - monitorização remota', '2026-06-24 00:08:48', NULL),
	(1242, 'EQ-00005-QWE', 'Monitor Portátil de SpO2 e FC', 20, 'Nonin', 'Avant 4000', 'NON-2022-77432', '2022', '2022-11-30', 1850.00, 'Compra', 'Ativo', 'Média', 25, 'Medicina Interna A - rounds noturnos', '2026-06-24 00:08:48', NULL),
	(1243, 'EQ-00006-GTF', 'Monitor de ECG de 12 Derivações', 20, 'Mortara', 'ELI 250', 'ELI-2021-44321', '2021', '2021-04-18', 3200.00, 'Compra', 'Ativo', 'Alta', 22, 'Urgência - sala de emergência', '2026-06-24 00:08:48', NULL),
	(1244, 'EQ-00007-PQG', 'Monitor Central de Enfermaria', 20, 'Philips', 'IntelliVue G5', 'PH-G5-2023-11223', '2023', '2023-05-12', 22000.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - central de enfermagem', '2026-06-24 00:08:48', NULL),
	(1245, 'EQ-00008-QWH', 'Monitor de Pressão Arterial Não Invasiva', 20, 'Welch Allyn', 'Connex ProBP 3400', 'WA-2020-66543', '2020', '2020-07-01', 1200.00, 'Compra', 'Em calibração', 'Média', 26, 'Em calibração anual - retorno previsto 5 dias', '2026-06-24 00:08:48', NULL),
	(1246, 'EQ-00009-PRI', 'Monitor Fetal Cardiotocógrafo', 20, 'GE Healthcare', 'Corometrics 250cx', 'GE-CTG-2022-98765', '2022', '2022-02-28', 7800.00, 'Compra', 'Ativo', 'Alta', 40, 'Bloco de Partos - sala 2', '2026-06-24 00:08:48', NULL),
	(1247, 'EQ-00010-QAJ', 'Oxímetro de Pulso de Mesa', 20, 'Masimo', 'Radical-7', 'MAS-2021-22334', '2021', '2021-08-14', 2100.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - posto 7', '2026-06-24 00:08:48', NULL),
	(1248, 'EQ-00011-GTK', 'Ventilador Pulmonar De Uci', 19, 'Dräger', 'Evita V500', 'EV500-2021-9934', '2021', '2021-01-10', 45000.00, 'Compra', 'Ativo', 'Suporte de vida', 23, 'UCI - box 1, ventilação invasiva', '2026-06-24 00:08:48', '2026-06-24 12:53:14'),
	(1249, 'EQ-00012-PEL', 'Ventilador de Transporte Portátil', 19, 'Hamilton Medical', 'T1', 'HAM-2022-31245', '2022', '2022-05-20', 18000.00, 'Compra', 'Ativo', 'Suporte de vida', 22, 'Urgência - transporte intra-hospitalar', '2026-06-24 00:08:48', NULL),
	(1250, 'EQ-00013-BIM', 'Ventilador Neonatal', 19, 'Dräger', 'VN500', 'VN-2023-44556', '2023', '2023-03-01', 38000.00, 'Compra', 'Ativo', 'Suporte de vida', 35, 'UCIN - incubadora 1', '2026-06-24 00:08:48', NULL),
	(1251, 'EQ-00014-QRN', 'CPAP de Alto Fluxo', 19, 'Fisher & Paykel', 'Airvo 2', 'FP-AIR-2022-55667', '2022', '2022-09-15', 4500.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - pós-extubação', '2026-06-24 00:08:48', NULL),
	(1252, 'EQ-00015-ONN', 'BiPAP / VNI Domiciliária', 19, 'Philips', 'DreamStation BiPAP', 'PH-DS-2021-33445', '2021', '2021-12-10', 2800.00, 'Compra', 'Ativo', 'Alta', 46, 'Pneumologia - internamento', '2026-06-24 00:08:48', NULL),
	(1253, 'EQ-00016-PJK', 'Ventilador de Bloco Operatório', 19, 'GE Healthcare', 'Aisys CS2', 'GE-AIS-2020-77889', '2020', '2020-04-22', 55000.00, 'Compra', 'Ativo', 'Suporte de vida', 37, 'Bloco Operatório - sala 3', '2026-06-24 00:08:48', NULL),
	(1254, 'EQ-00017-QSA', 'Aspirador de Secreções Portátil', 19, 'Laerdal', 'Suction Unit 7', 'LAE-2022-11223', '2022', '2022-07-08', 950.00, 'Compra', 'Ativo', 'Alta', 22, 'Urgência - carro de emergência', '2026-06-24 00:08:48', NULL),
	(1255, 'EQ-00018-RDF', 'Concentrador de Oxigénio', 19, 'Philips', 'EverFlo', 'PH-EF-2021-44556', '2021', '2021-10-30', 1400.00, 'Compra', 'Abatido', 'Média', 46, 'Reserva - armazém Pneumologia', '2026-06-24 00:08:48', NULL),
	(1256, 'EQ-00019-SFT', 'Humidificador Aquatermo Neonatal', 19, 'Fisher & Paykel', 'MR850', 'FP-MR-2023-66778', '2023', '2023-02-14', 3200.00, 'Compra', 'Ativo', 'Alta', 35, 'UCIN - incubadora 2', '2026-06-24 00:08:48', NULL),
	(1257, 'EQ-00020-TDE', 'Oxigenador de Membrana Extracorporal (ECMO)', 19, 'Maquet', 'Cardiohelp', 'MAQ-2022-99001', '2022', '2022-06-01', 95000.00, 'Compra', 'Ativo', 'Suporte de vida', 23, 'UCI - reserva emergência cardíaca', '2026-06-24 00:08:48', NULL),
	(1258, 'EQ-00021-UOL', 'Desfibrilhador Bifásico DAE', 13, 'Zoll', 'AED 3', 'ZR-2021-7712', '2021', '2021-03-15', 3500.00, 'Compra', 'Ativo', 'Alta', 22, 'Urgência - sala de reanimação', '2026-06-24 00:08:48', NULL),
	(1259, 'EQ-00022-VUH', 'Desfibrilhador Monofásico Manual', 13, 'Philips', 'HeartStart XL+', 'PH-XL-2020-55443', '2020', '2020-08-20', 8500.00, 'Compra', 'Ativo', 'Suporte de vida', 23, 'UCI - posto central', '2026-06-24 00:08:48', NULL),
	(1260, 'EQ-00023-WCF', 'DAE Público - Corredor Principal', 13, 'Zoll', 'AED Plus', 'ZR-AED-2022-33210', '2022', '2022-01-05', 1800.00, 'Compra', 'Ativo', 'Suporte de vida', 22, 'Corredor Bloco A - sinalizado', '2026-06-24 00:08:48', NULL),
	(1261, 'EQ-00024-XRF', 'Desfibrilhador com Marcapasso Externo', 13, 'Zoll', 'R Series', 'ZR-RS-2023-88432', '2023', '2023-04-18', 15000.00, 'Compra', 'Ativo', 'Suporte de vida', 22, 'Urgência - carro de paragem', '2026-06-24 00:08:48', NULL),
	(1262, 'EQ-00025-YDR', 'DAE Portátil de Transporte', 13, 'Philips', 'HeartStart FRX', 'PH-FRX-2021-66789', '2021', '2021-09-25', 1350.00, 'Compra', 'Ativo', 'Alta', 22, 'Urgência - ambulância interna', '2026-06-24 00:08:48', NULL),
	(1263, 'EQ-00026-ZWS', 'Bomba de Seringa Volumétrica', 21, 'B. Braun', 'Perfusor Space', 'BB-PFS-2022-10023', '2022', '2022-04-10', 2800.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - box 2', '2026-06-24 00:08:48', NULL),
	(1264, 'EQ-00027-AQA', 'Bomba de Perfusão Volumétrica', 21, 'B. Braun', 'Infusomat Space', 'BB-INF-2020-88321', '2020', '2020-11-05', 1950.00, 'Compra', 'Ativo', 'Média', 25, 'Medicina Interna A - leito 12', '2026-06-24 00:08:48', NULL),
	(1265, 'EQ-00028-BSA', 'Sistema de Perfusão Multichannel', 21, 'Fresenius Kabi', 'Agilia', 'FK-AG-2023-55670', '2023', '2023-06-20', 12000.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - rack principal', '2026-06-24 00:08:48', NULL),
	(1266, 'EQ-00029-CNJ', 'Bomba de Nutrição Entérica', 21, 'Kangaroo', 'Joey', 'KAN-2021-22341', '2021', '2021-07-15', 1100.00, 'Compra', 'Ativo', 'Média', 25, 'Medicina Interna A - nutrição pós-cirúrgica', '2026-06-24 00:08:48', NULL),
	(1267, 'EQ-00030-DNM', 'Bomba de Seringa de Alta Precisão', 21, 'Medtronic', 'SynchroMed II', 'MED-SYN-2022-77650', '2022', '2022-10-01', 3600.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - box 4', '2026-06-24 00:08:48', NULL),
	(1268, 'EQ-00031-EVB', 'Analgesia Controlada Pelo Paciente (pca)', 21, 'Hospira', 'Plum A+', 'HOS-2021-44330', '2021', '2021-05-22', 2200.00, 'Compra', 'Ativo', 'Alta', 38, 'Cirurgia - pós-operatório', '2026-06-24 00:08:48', '2026-06-24 00:46:06'),
	(1269, 'EQ-00032-FCD', 'Bomba de Diálise Peritoneal', 21, 'Baxter', 'HomeChoice Claria', 'BAX-HC-2023-11220', '2023', '2023-01-30', 5800.00, 'Compra', 'Ativo', 'Alta', 34, 'Nefrologia - sala de diálise', '2026-06-24 00:08:48', NULL),
	(1270, 'EQ-00033-GXZ', 'Bomba de Perfusão Neonatal', 21, 'B. Braun', 'Perfusor Compact Plus', 'BB-CP-2022-66543', '2022', '2022-08-09', 3100.00, 'Compra', 'Ativo', 'Suporte de vida', 35, 'UCIN - incubadora 4', '2026-06-24 00:08:48', NULL),
	(1271, 'EQ-00034-HKM', 'Bomba Volumétrica para Nutrição Parentérica', 21, 'Fresenius Kabi', 'Volumat MC Agilia', 'FK-VM-2020-99871', '2020', '2020-12-15', 2500.00, 'Compra', 'Em manutenção', 'Alta', 23, 'Em manutenção preventiva - B.Braun tecnico', '2026-06-24 00:08:48', NULL),
	(1272, 'EQ-00035-IBN', 'Bomba de Epidural Obstétrica', 21, 'CADD', 'Solis', 'CAD-2022-33456', '2022', '2022-03-28', 4100.00, 'Compra', 'Ativo', 'Alta', 40, 'Bloco de Partos - sala 1', '2026-06-24 00:08:48', NULL),
	(1273, 'EQ-00036-JNH', 'Ecógrafo Portátil de Urgência', 18, 'GE Healthcare', 'Vscan Air', 'GE-VSC-2023-44532', '2023', '2023-07-10', 8500.00, 'Compra', 'Ativo', 'Alta', 22, 'Urgência - triagem rápida', '2026-06-24 00:08:48', NULL),
	(1274, 'EQ-00037-KVF', 'Ecógrafo de Cardiologia Avançado', 18, 'Philips', 'Epiq 7C', 'PH-EP-2021-77823', '2021', '2021-02-01', 120000.00, 'Compra', 'Ativo', 'Alta', 24, 'Cardiologia - ecocardiografia', '2026-06-24 00:08:48', NULL),
	(1275, 'EQ-00038-LDE', 'Arco Cirúrgico C-Arm', 18, 'Siemens', 'Cios Spin', 'SIE-CS-2022-55210', '2022', '2022-06-14', 185000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - sala 2', '2026-06-24 00:08:48', NULL),
	(1276, 'EQ-00039-MPL', 'Raio-X Digital Portátil', 18, 'Siemens', 'Mobilett Mira', 'SIE-MM-2021-33098', '2021', '2021-10-20', 75000.00, 'Compra', 'Ativo', 'Alta', 22, 'Urgência - sala de trauma', '2026-06-24 00:08:48', NULL),
	(1277, 'EQ-00040-NKO', 'Mamógrafo Digital', 18, 'GE Healthcare', 'Senographe Pristina', 'GE-SG-2022-88760', '2022', '2022-09-01', 220000.00, 'Compra', 'Ativo', 'Alta', 43, 'Consulta Externa - ginecologia', '2026-06-24 00:08:48', NULL),
	(1278, 'EQ-00041-OKI', 'Densitómetro Ósseo DXA', 18, 'Hologic', 'Horizon W', 'HOL-2020-55443', '2020', '2020-05-15', 85000.00, 'Compra', 'Ativo', 'Média', 43, 'Consulta Externa - reumatologia', '2026-06-24 00:08:48', NULL),
	(1279, 'EQ-00042-PNJ', 'Colposcópio Digital', 18, 'Leisegang', '3MV', 'LEI-3MV-2021-22310', '2021', '2021-11-18', 12000.00, 'Compra', 'Ativo', 'Alta', 40, 'Ginecologia - consulta', '2026-06-24 00:08:48', NULL),
	(1280, 'EQ-00043-QHY', 'Otoscópio/Dermatoscópio Digital', 18, 'Welch Allyn', 'MacroView Plus', 'WA-MV-2022-66432', '2022', '2022-04-05', 2800.00, 'Compra', 'Ativo', 'Média', 25, 'Medicina Interna A - enfermagem', '2026-06-24 00:08:48', NULL),
	(1281, 'EQ-00044-RVG', 'Angioscópio Fluorescente', 18, 'Olympus', 'Evis X1', 'OLY-X1-2023-99234', '2023', '2023-08-22', 95000.00, 'Compra', 'Em manutenção', 'Alta', 44, 'Endoscopia - sala 1', '2026-06-24 00:08:48', '2026-06-24 00:11:44'),
	(1282, 'EQ-00045-SDE', 'Endoscópio de Vídeo Digestivo', 18, 'Olympus', 'GIF-H290T', 'OLY-H290-2022-77651', '2022', '2022-12-01', 38000.00, 'Compra', 'Ativo', 'Alta', 44, 'Endoscopia - sala 2', '2026-06-24 00:08:48', NULL),
	(1283, 'EQ-00046-TLP', 'Autoclave de Vapor Hospitalar', 12, 'Getinge', 'GSS67H', 'GET-GSS-2021-44213', '2021', '2021-06-30', 35000.00, 'Compra', 'Ativo', 'Alta', 48, 'Central de Esterilização - linha 1', '2026-06-24 00:08:48', NULL),
	(1284, 'EQ-00047-UBG', 'Autoclave de Plasma de Peróxido de Hidrogénio', 12, 'Advanced Sterilization Products', 'Sterrad 100NX', 'ASP-2022-22109', '2022', '2022-02-15', 28000.00, 'Compra', 'Ativo', 'Alta', 48, 'Central de Esterilização - linha 2', '2026-06-24 00:08:48', NULL),
	(1285, 'EQ-00048-VKI', 'Lavadora Ultrassónica de Instrumentos', 12, 'Miele', 'PG 8582 AOS', 'MIE-2020-88654', '2020', '2020-10-10', 18000.00, 'Compra', 'Ativo', 'Média', 48, 'Central de Esterilização - pré-lavagem', '2026-06-24 00:08:48', NULL),
	(1286, 'EQ-00049-WGF', 'Seladora de Embalagens Esterilizáveis', 12, 'Hawo', 'HTI 600', 'HAW-2022-55321', '2022', '2022-07-20', 4500.00, 'Compra', 'Ativo', 'Média', 48, 'Central de Esterilização - embalagem', '2026-06-24 00:08:48', NULL),
	(1287, 'EQ-00050-XZA', 'Indicador Biológico de Esterilização (leitor)', 12, '3M', 'Attest 490', '3M-AT-2021-33120', '2021', '2021-09-01', 1800.00, 'Compra', 'Ativo', 'Média', 48, 'Central de Esterilização - controlo de qualidade', '2026-06-24 00:08:48', NULL),
	(1288, 'EQ-00051-YPL', 'Analisador Bioquímico Automático', 15, 'Roche', 'Cobas C 702', 'ROC-702-2022-66542', '2022', '2022-01-25', 280000.00, 'Compra', 'Ativo', 'Alta', 47, 'Laboratório - bancada principal', '2026-06-24 00:08:48', '2026-06-24 00:46:15'),
	(1289, 'EQ-00052-ZAQ', 'Analisador Hematológico 5-Diff', 15, 'Sysmex', 'XN-1000', 'SYS-XN-2021-44213', '2021', '2021-03-18', 75000.00, 'Compra', 'Ativo', 'Alta', 47, 'Laboratório - hematologia', '2026-06-24 00:08:48', NULL),
	(1290, 'EQ-00053-ADE', 'Centrífuga de Tubos', 15, 'Eppendorf', '5910 R', 'EPP-2020-22110', '2020', '2020-07-12', 8500.00, 'Compra', 'Ativo', 'Média', 47, 'Laboratório - preparação de amostras', '2026-06-24 00:08:48', NULL),
	(1291, 'EQ-00054-BJU', 'Gasómetro / Analisador de Gases', 15, 'Abbott', 'Architect STAT', 'ABB-2022-88654', '2022', '2022-10-30', 55000.00, 'Compra', 'Ativo', 'Alta', 47, 'Laboratório - urgência 24h', '2026-06-24 00:08:48', NULL),
	(1292, 'EQ-00055-CPB', 'Analisador De Coagulação', 15, 'Stago', 'Sta-r Max 3', 'STA-2021-55310', '2021', '2021-05-25', 65000.00, 'Compra', 'Ativo', 'Alta', 47, 'Laboratório - hemostase', '2026-06-24 00:08:48', '2026-06-24 00:47:40'),
	(1293, 'EQ-00056-DUJ', 'Microscópio Ótico de Fluorescência', 15, 'Leica', 'DM2000', 'LEI-DM-2020-33120', '2020', '2020-04-08', 18000.00, 'Compra', 'Ativo', 'Média', 47, 'Laboratório - microbiologia', '2026-06-24 00:08:48', NULL),
	(1294, 'EQ-00057-EBY', 'Agitador De Plaquetas', 15, 'Helmer', 'Pc100i', 'HEL-2022-11230', '2022', '2022-06-06', 5200.00, 'Compra', 'Em calibração', 'Alta', 47, 'Laboratório - banco de sangue', '2026-06-24 00:08:48', '2026-06-24 00:24:32'),
	(1295, 'EQ-00058-FED', 'Incubadora de Culturas Bacteriológicas', 15, 'Thermo Fisher', 'Heracell 150i', 'TF-2023-44567', '2023', '2023-02-22', 12000.00, 'Compra', 'Ativo', 'Média', 47, 'Laboratório - microbiologia', '2026-06-24 00:08:48', NULL),
	(1296, 'EQ-00059-GLP', 'Eletrocardiográfo de 12 Derivações', 17, 'Mortara', 'ELI 380', 'MOR-2021-66789', '2021', '2021-08-30', 4800.00, 'Compra', 'Ativo', 'Alta', 25, 'Medicina Interna A - consulta', '2026-06-24 00:08:48', NULL),
	(1297, 'EQ-00060-HJU', 'Espirômetro de Diagnóstico', 17, 'Jaeger', 'MasterScope', 'JAE-2020-44321', '2020', '2020-11-01', 9500.00, 'Compra', 'Ativo', 'Alta', 46, 'Pneumologia - função respiratória', '2026-06-24 00:08:48', NULL),
	(1298, 'EQ-00061-IKP', 'Holter ECG Ambulatório', 17, 'GE Healthcare', 'MARS Ambulatory', 'GE-MAR-2022-22456', '2022', '2022-03-14', 6200.00, 'Compra', 'Ativo', 'Alta', 24, 'Cardiologia - ambulatório', '2026-06-24 00:08:48', NULL),
	(1299, 'EQ-00062-JHU', 'MAPA - Monitor de Pressão Ambulatório', 17, 'Spacelabs', '90207', 'SPC-2021-88123', '2021', '2021-07-28', 3100.00, 'Compra', 'Ativo', 'Alta', 24, 'Cardiologia - ambulatório', '2026-06-24 00:08:48', NULL),
	(1300, 'EQ-00063-KDH', 'Audiômetro Clínico', 17, 'Interacoustics', 'AC40', 'INT-2022-55870', '2022', '2022-09-19', 8900.00, 'Compra', 'Ativo', 'Média', 42, 'ORL - audiologia', '2026-06-24 00:08:48', NULL),
	(1301, 'EQ-00064-LVT', 'Electroencefalógrafo Digital', 17, 'Nihon Kohden', 'Neurofax EEG-1200', 'NK-2021-33456', '2021', '2021-12-05', 45000.00, 'Compra', 'Ativo', 'Alta', 45, 'Neurologia - sala EEG', '2026-06-24 00:08:48', NULL),
	(1302, 'EQ-00065-MCE', 'Eletromiógrafo EMG/ENG', 17, 'Natus', 'Xltek EMG', 'NAT-2023-11234', '2023', '2023-05-08', 52000.00, 'Compra', 'Ativo', 'Alta', 45, 'Neurologia - neurofisiologia', '2026-06-24 00:08:48', NULL),
	(1303, 'EQ-00066-NAQ', 'Tilt Table Cardio-neurológico', 17, 'RMS Medical', 'TT-800', 'RMS-2020-66543', '2020', '2020-06-15', 14000.00, 'Compra', 'Ativo', 'Alta', 24, 'Cardiologia - síncope', '2026-06-24 00:08:48', NULL),
	(1304, 'EQ-00067-OPL', 'Bisturi Elétrico Monopolar/Bipolar', 16, 'Erbe', 'VIO 300 D', 'ERB-2022-44212', '2022', '2022-01-17', 22000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - sala 1', '2026-06-24 00:08:48', NULL),
	(1305, 'EQ-00068-PBY', 'Laparoscópio HD 4K', 16, 'Storz', 'IMAGE1 S', 'STZ-2023-77432', '2023', '2023-07-01', 85000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - sala 2', '2026-06-24 00:08:48', NULL),
	(1306, 'EQ-00069-QLI', 'Torniquete Pneumático Cirúrgico', 16, 'Zimmer', 'ATS 2000', 'ZIM-ATS-2021-55210', '2021', '2021-04-20', 8500.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - ortopedia', '2026-06-24 00:08:48', NULL),
	(1307, 'EQ-00070-RZW', 'Motor Cirúrgico Ortopédico', 16, 'Stryker', 'System 7', 'STR-S7-2022-33098', '2022', '2022-11-15', 35000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - ortopedia', '2026-06-24 00:08:48', NULL),
	(1308, 'EQ-00071-SJD', 'Artroscópio Portátil', 16, 'Smith & Nephew', 'Dyonics HD', 'SN-DY-2021-88543', '2021', '2021-08-25', 42000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - artroscopia', '2026-06-24 00:08:48', NULL),
	(1309, 'EQ-00072-TCR', 'Mesa Cirúrgica Hidráulica', 16, 'Maquet', 'Magnus', 'MAQ-MAG-2020-22106', '2020', '2020-03-10', 65000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - sala 3', '2026-06-24 00:08:48', NULL),
	(1310, 'EQ-00073-UMY', 'Foco Cirúrgico LED', 16, 'Dräger', 'Polaris 100', 'DRA-POL-2022-44321', '2022', '2022-06-28', 18000.00, 'Compra', 'Ativo', 'Média', 37, 'Bloco Operatório - sala 1', '2026-06-24 00:08:48', NULL),
	(1311, 'EQ-00074-VBY', 'Termorregulador de Soro Intraoperatório', 16, 'Smiths Medical', 'Level 1 H-1200', 'SM-L1-2023-66543', '2023', '2023-03-05', 3800.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - anestesia', '2026-06-24 00:08:48', NULL),
	(1312, 'EQ-00075-WUJ', 'Aspirador Cirúrgico De Alta Potência', 16, 'Stryker', 'Neptune 3', 'STR-N3-2021-88765', '2021', '2021-10-10', 12000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - sala 2', '2026-06-24 00:08:48', '2026-06-24 00:48:03'),
	(1313, 'EQ-00076-XDE', 'Incubadora Neonatal Fechada', 19, 'Dräger', 'Caleo', 'DRA-CAL-2023-55432', '2023', '2023-01-10', 28000.00, 'Compra', 'Ativo', 'Suporte de vida', 35, 'UCIN - incubadora 5', '2026-06-24 00:08:48', NULL),
	(1314, 'EQ-00077-YSE', 'Berço Aquecido Neonatal', 19, 'GE Healthcare', 'Giraffe OmniBed Carestation', 'GE-GIR-2022-33210', '2022', '2022-04-14', 32000.00, 'Compra', 'Ativo', 'Suporte de vida', 35, 'UCIN - incubadora 6', '2026-06-24 00:08:48', NULL),
	(1315, 'EQ-00078-ZTB', 'Fototerapia LED Neonatal', 19, 'Philips', 'BiliBlanket Plus', 'PH-BIL-2021-11230', '2021', '2021-07-22', 2800.00, 'Compra', 'Ativo', 'Alta', 35, 'UCIN - icterícia neonatal', '2026-06-24 00:08:48', NULL),
	(1316, 'EQ-00079-ABY', 'Monitor de Amplitude EEG Neonatal (aEEG)', 17, 'Nicolet', 'EEG Elite', 'NIC-2023-44567', '2023', '2023-06-15', 35000.00, 'Compra', 'Ativo', 'Suporte de vida', 35, 'UCIN - monitorização cerebral', '2026-06-24 00:08:48', NULL),
	(1317, 'EQ-00080-BYJ', 'Balança Pediátrica de Precisão', 17, 'Seca', '757', 'SEC-2022-22341', '2022', '2022-09-01', 650.00, 'Compra', 'Ativo', 'Baixa', 35, 'UCIN - avaliação diária', '2026-06-24 00:08:48', NULL),
	(1318, 'EQ-00081-CNC', 'Sistema de Balão de Contra-Pulsação Intra-Aórtica', 13, 'Maquet', 'CS300', 'MAQ-CS-2022-77654', '2022', '2022-08-30', 85000.00, 'Compra', 'Ativo', 'Suporte de vida', 24, 'Coronários - sala de hemodinâmica', '2026-06-24 00:08:48', NULL),
	(1319, 'EQ-00082-DBU', 'Marcapasso Externo Transcutâneo', 13, 'Medtronic', '5392', 'MED-5392-2021-55342', '2021', '2021-05-18', 12000.00, 'Compra', 'Ativo', 'Suporte de vida', 24, 'Coronários - bedside', '2026-06-24 00:08:48', NULL),
	(1320, 'EQ-00083-ENE', 'Cardioversor Sincronizado', 13, 'Philips', 'HeartStart MRx', 'PH-MRX-2020-33210', '2020', '2020-10-22', 22000.00, 'Compra', 'Ativo', 'Suporte de vida', 24, 'Coronários - sala de emergência', '2026-06-24 00:08:48', NULL),
	(1321, 'EQ-00084-FVU', 'Cicloergómetro de Reabilitação', 11, 'Biodex', 'Ergys 3', 'BIO-2022-44321', '2022', '2022-02-10', 9500.00, 'Compra', 'Ativo', 'Média', 41, 'Reabilitação - ginásio', '2026-06-24 00:08:48', NULL),
	(1322, 'EQ-00085-GSJ', 'Plataforma de Equilíbrio e Posturologia', 11, 'Biodex', 'Balance System SD', 'BIO-BS-2021-22104', '2021', '2021-06-05', 14000.00, 'Compra', 'Ativo', 'Média', 41, 'Reabilitação - fisioterapia', '2026-06-24 00:08:48', NULL),
	(1323, 'EQ-00086-HVT', 'Elevador de Transferência de Doentes', 11, 'Arjo', 'Maxi Sky 2', 'ARJ-2022-66543', '2022', '2022-10-18', 8500.00, 'Compra', 'Ativo', 'Média', 25, 'Medicina Interna A - transferências', '2026-06-24 00:08:48', NULL),
	(1324, 'EQ-00087-INE', 'Cama Articulada Elétrica', 11, 'Stryker', 'Secure II', 'STR-SEC-2020-88432', '2020', '2020-08-01', 5500.00, 'Compra', 'Ativo', 'Média', 26, 'Medicina Interna B - leito 8', '2026-06-24 00:08:48', NULL),
	(1325, 'EQ-00088-JQC', 'Cadeira de Rodas Elétrica', 11, 'Permobil', 'M3 Corpus', 'PER-2023-33120', '2023', '2023-04-22', 18000.00, 'Compra', 'Ativo', 'Média', 41, 'Reabilitação - empréstimo interno', '2026-06-24 00:08:48', NULL),
	(1326, 'EQ-00089-KNO', 'Ultrassom Terapêutico', 11, 'Enraf-Nonius', 'Sonopuls 490', 'EN-SON-2021-55210', '2021', '2021-09-15', 4200.00, 'Compra', 'Ativo', 'Média', 41, 'Reabilitação - eletroterapia', '2026-06-24 00:08:48', NULL),
	(1327, 'EQ-00090-LME', 'TENS / EMS Eletroterapia', 11, 'BTL', 'BTL-4000 Smart', 'BTL-2022-11230', '2022', '2022-12-20', 2800.00, 'Compra', 'Ativo', 'Média', 41, 'Reabilitação - eletroterapia', '2026-06-24 00:08:48', NULL),
	(1328, 'EQ-00091-MXE', 'Máquina de Hemodiálise', 21, 'Fresenius Medical', '5008 CorDiax', 'FMC-2022-44213', '2022', '2022-05-30', 55000.00, 'Compra', 'Ativo', 'Alta', 34, 'Nefrologia - posto 1', '2026-06-24 00:08:48', NULL),
	(1329, 'EQ-00092-NFE', 'Máquina de Hemodiálise', 21, 'Fresenius Medical', '5008 CorDiax', 'FMC-2022-44214', '2022', '2022-05-30', 55000.00, 'Compra', 'Ativo', 'Alta', 34, 'Nefrologia - posto 2', '2026-06-24 00:08:48', NULL),
	(1330, 'EQ-00093-OKU', 'Máquina de Hemodiálise', 21, 'Fresenius Medical', '5008 CorDiax', 'FMC-2022-44215', '2022', '2022-05-30', 55000.00, 'Compra', 'Ativo', 'Alta', 34, 'Nefrologia - posto 3', '2026-06-24 00:08:48', NULL),
	(1331, 'EQ-00094-PIS', 'Monitor de Tratamento de Hemodiálise', 20, 'Baxter', 'Theranova', 'BAX-TH-2023-66543', '2023', '2023-02-01', 8500.00, 'Compra', 'Ativo', 'Alta', 34, 'Nefrologia - posto 4', '2026-06-24 00:08:48', NULL),
	(1332, 'EQ-00095-QWH', 'Máquina CRRT (Terapia Renal Contínua)', 21, 'Prismaflex', 'Prismaflex System', 'PRI-2022-88432', '2022', '2022-07-15', 65000.00, 'Compra', 'Ativo', 'Suporte de vida', 23, 'UCI - insuficiência renal aguda', '2026-06-24 00:08:48', NULL),
	(1333, 'EQ-00096-ROW', 'Lâmpada de Fenda', 18, 'Haag-Streit', 'BM 900', 'HS-BM-2021-22341', '2021', '2021-04-10', 22000.00, 'Compra', 'Ativo', 'Alta', 50, 'Oftalmologia - consulta 1', '2026-06-24 00:08:48', NULL),
	(1334, 'EQ-00097-SJD', 'Tonómetro de Aplanação', 18, 'Haag-Streit', 'AT 900', 'HS-AT-2020-55210', '2020', '2020-09-08', 8500.00, 'Compra', 'Ativo', 'Alta', 50, 'Oftalmologia - consulta 1', '2026-06-24 00:08:48', NULL),
	(1335, 'EQ-00098-TDE', 'Retinógrafo Digital', 18, 'Topcon', 'TRC-50DX', 'TOP-2022-33120', '2022', '2022-11-25', 38000.00, 'Compra', 'Ativo', 'Alta', 50, 'Oftalmologia - imagiologia', '2026-06-24 00:08:48', NULL),
	(1336, 'EQ-00099-UUJ', 'OCT - Tomografia de Coerência Ótica', 18, 'Zeiss', 'Cirrus 6000', 'ZEI-2023-77654', '2023', '2023-06-01', 95000.00, 'Compra', 'Ativo', 'Alta', 50, 'Oftalmologia - imagiologia avançada', '2026-06-24 00:08:48', NULL),
	(1337, 'EQ-00100-VNJ', 'Estimulador Magnético Transcraniano (TMS)', 17, 'Magstim', 'Rapid²', 'MAG-2022-44321', '2022', '2022-08-15', 42000.00, 'Compra', 'Ativo', 'Alta', 45, 'Neurologia - neuromodulação', '2026-06-24 00:08:48', NULL),
	(1338, 'EQ-00101-WUK', 'Polígrafo de Sono', 17, 'Embla', 'Titanium', 'EMB-2021-22104', '2021', '2021-12-10', 18000.00, 'Compra', 'Ativo', 'Alta', 45, 'Neurologia - laboratório do sono', '2026-06-24 00:08:48', NULL),
	(1339, 'EQ-00102-XRS', 'Sistema de Neuronavegação Cirúrgica', 17, 'Stryker', 'Spine Map3D', 'STR-NM-2023-55342', '2023', '2023-03-20', 185000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - neurocirurgia', '2026-06-24 00:08:48', NULL),
	(1340, 'EQ-00103-YOF', 'Polígrafo Hemodinâmico', 17, 'GE Healthcare', 'Centricity CRM', 'GE-CRM-2022-88765', '2022', '2022-06-30', 220000.00, 'Compra', 'Ativo', 'Suporte de vida', 24, 'Cardiologia - sala de hemodinâmica', '2026-06-24 00:08:48', NULL),
	(1341, 'EQ-00104-ZFD', 'Ecocardiógrafo Transesofágico Intraoperatório', 18, 'Philips', 'IE33 xMatrix', 'PH-IE-2021-66543', '2021', '2021-10-15', 145000.00, 'Compra', 'Ativo', 'Suporte de vida', 37, 'Bloco Operatório - cardiotorácica', '2026-06-24 00:08:48', NULL),
	(1342, 'EQ-00105-AKU', 'Endoscópio Nasal / Laringoscópio', 18, 'Olympus', 'ENF-VH2', 'OLY-ENF-2022-33210', '2022', '2022-04-05', 28000.00, 'Compra', 'Ativo', 'Alta', 42, 'ORL - sala de procedimentos', '2026-06-24 00:08:48', NULL),
	(1343, 'EQ-00106-BDF', 'Audiômetro de Triagem Neonatal (DPOAE)', 17, 'Madsen', 'Accuscreen', 'MAD-2023-11234', '2023', '2023-07-12', 6500.00, 'Compra', 'Ativo', 'Alta', 42, 'ORL - rastreio neonatal', '2026-06-24 00:08:48', NULL),
	(1344, 'EQ-00107-CKI', 'Cistoscópio Flexível Vídeo', 18, 'Olympus', 'CYF-VH', 'OLY-CYF-2022-77543', '2022', '2022-05-22', 32000.00, 'Compra', 'Ativo', 'Alta', 49, 'Urologia - sala de endoscopia', '2026-06-24 00:08:48', NULL),
	(1345, 'EQ-00108-DWS', 'Litotriptor Extracorporal (ESWL)', 18, 'Dornier', 'Gemini', 'DOR-2020-55210', '2020', '2020-07-01', 280000.00, 'Compra', 'Ativo', 'Alta', 49, 'Urologia - sala de procedimentos', '2026-06-24 00:08:48', NULL),
	(1346, 'EQ-00109-EGD', 'Colposcópio Vídeo LED', 18, 'Karl Storz', 'Telecolposcope', 'KS-2021-33098', '2021', '2021-03-25', 22000.00, 'Compra', 'Ativo', 'Alta', 40, 'Ginecologia - colposcopia', '2026-06-24 00:08:48', NULL),
	(1347, 'EQ-00110-FWI', 'Histeroscópio Cirúrgico', 18, 'Bettocchi', 'Set Completo', 'BET-2022-88543', '2022', '2022-10-05', 35000.00, 'Compra', 'Ativo', 'Alta', 40, 'Ginecologia - sala de procedimentos', '2026-06-24 00:08:48', NULL),
	(1348, 'EQ-00111-GWE', 'Artroscópio HD para Joelho', 16, 'Stryker', '1288 HD', 'STR-1288-2023-44213', '2023', '2023-02-28', 65000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - artroscopia', '2026-06-24 00:08:48', NULL),
	(1349, 'EQ-00112-HJX', 'Sistema de Navegação para Próteses', 16, 'Zimmer', 'ORTHOsoft', 'ZIM-ORT-2022-22104', '2022', '2022-08-08', 95000.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - ortopedia', '2026-06-24 00:08:48', NULL),
	(1350, 'EQ-00113-IWS', 'Cama Articulada UCi com Balança Integrada', 11, 'Stryker', 'InTouch ICU Bed', 'STR-ITC-2022-66543', '2022', '2022-01-20', 18000.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - box 6', '2026-06-24 00:08:48', NULL),
	(1351, 'EQ-00114-JIR', 'Maca de Transporte com Raio-X', 11, 'Stryker', 'Transport Chair', 'STR-TC-2021-44321', '2021', '2021-05-30', 8500.00, 'Compra', 'Ativo', 'Média', 22, 'Urgência - transporte interno', '2026-06-24 00:08:48', NULL),
	(1352, 'EQ-00115-KIW', 'Cadeira de Banho Hospitalar', 11, 'Arjo', 'Carendo', 'ARJ-CAR-2020-22104', '2020', '2020-08-18', 1200.00, 'Compra', 'Ativo', 'Baixa', 26, 'Medicina Interna B - higiene', '2026-06-24 00:08:48', NULL),
	(1353, 'EQ-00116-LSK', 'Glucómetro de Controlo Laboratorial', 17, 'Roche', 'Accu-Chek Inform II', 'ROC-ACC-2022-55342', '2022', '2022-06-14', 2800.00, 'Compra', 'Ativo', 'Média', 47, 'Laboratório - Point of Care', '2026-06-24 00:08:48', NULL),
	(1354, 'EQ-00117-MOE', 'Analisador Portátil I-stat', 15, 'Abbott', 'I-stat 1', 'ABB-IST-2021-33120', '2021', '2021-09-22', 5800.00, 'Compra', 'Ativo', 'Alta', 22, 'Urgência - gases urgência', '2026-06-24 00:08:48', '2026-06-24 00:47:49'),
	(1355, 'EQ-00118-NIW', 'Otoscópio Diagnóstico LED', 17, 'Welch Allyn', 'PanOptic', 'WA-PAN-2022-11230', '2022', '2022-04-18', 580.00, 'Compra', 'Ativo', 'Baixa', 25, 'Medicina Interna A - consulta', '2026-06-24 00:08:48', NULL),
	(1356, 'EQ-00119-OUR', 'Oftalmoscópio Direto LED', 17, 'Keeler', 'All Pupil II', 'KEE-2020-88543', '2020', '2020-11-30', 450.00, 'Compra', 'Ativo', 'Baixa', 43, 'Consulta Externa - oftalmologia', '2026-06-24 00:08:48', NULL),
	(1357, 'EQ-00120-PWS', 'Dermascópio Polarizado', 17, 'Dermlite', 'DL4', 'DER-2023-66342', '2023', '2023-06-08', 380.00, 'Compra', 'Ativo', 'Baixa', 43, 'Consulta Externa - dermatologia', '2026-06-24 00:08:48', NULL),
	(1358, 'EQ-00121-QIE', 'Ventilador UCI (em manutenção)', 19, 'Dräger', 'Evita 4', 'EV4-2019-33210', '2019', '2019-08-01', 38000.00, 'Compra', 'Em manutenção', 'Suporte de vida', 23, 'Preventiva anual - Dräger Portugal prevista até fim do mês', '2026-06-24 00:08:48', NULL),
	(1359, 'EQ-00122-RED', 'Autoclave (em manutenção)', 12, 'Getinge', 'GSS67H-B', 'GET-B-2020-44213', '2020', '2020-06-15', 34000.00, 'Compra', 'Em manutenção', 'Alta', 48, 'Falha no ciclo de vácuo - peça em encomenda', '2026-06-24 00:08:48', NULL),
	(1360, 'EQ-00123-SGQ', 'Monitor UCI (em calibração)', 20, 'Mindray', 'BeneVision N22', 'MR-N22-2021-55432', '2021', '2021-11-10', 12000.00, 'Compra', 'Em calibração', 'Alta', 23, 'Calibração semestral IPAC - retorno 3 dias', '2026-06-24 00:08:48', NULL),
	(1361, 'EQ-00124-TUE', 'Bomba De Perfusão (quarentena)', 21, 'B. Braun', 'Infusomat Space', 'BB-INF-2019-77654', '2019', '2019-04-15', 1800.00, 'Compra', 'Em quarentena', 'Alta', 23, 'Alarme de oclusão falso positivo - aguarda validação técnica', '2026-06-24 00:08:48', '2026-06-24 00:48:17'),
	(1362, 'EQ-00125-UPR', 'Ecógrafo Portátil (quarentena)', 18, 'Ge Healthcare', 'Logiq E R8', 'GE-LR8-2020-88765', '2020', '2020-09-20', 28000.00, 'Compra', 'Em quarentena', 'Alta', 22, 'Imagem com artefactos - aguarda decisão técnica', '2026-06-24 00:08:48', '2026-06-24 00:48:39'),
	(1363, 'EQ-00126-VTW', 'Monitor Sinais Vitais (Abatido)', 20, 'Datex-Ohmeda', 'S/5 Light', 'DOH-2012-22341', '2012', '2012-05-10', 8500.00, 'Compra', 'Abatido', 'Média', 23, 'Substituído por modelo mais recente - 2021', '2026-06-24 00:08:48', NULL),
	(1364, 'EQ-00127-WFF', 'Ventilador Antigo (Abatido)', 19, 'Dräger', 'Evita 2 Dura', 'EV2D-2010-33210', '2010', '2010-03-20', 28000.00, 'Compra', 'Abatido', 'Suporte de vida', 23, 'Fim de vida útil - peças descontinuadas', '2026-06-24 00:08:48', NULL),
	(1365, 'EQ-00128-XKF', 'ECG (Abatido)', 17, 'Marquette', 'MAC 5000', 'MAQ-2008-44321', '2008', '2008-07-15', 3800.00, 'Compra', 'Abatido', 'Média', 25, 'Substituído digitalmente em 2022', '2026-06-24 00:08:48', NULL),
	(1366, 'EQ-00129-YAG', 'Tensiômetro Digital de Mesa', 20, 'Omron', 'HBP-1300', 'OMR-2022-55432', '2022', '2022-08-10', 480.00, 'Compra', 'Ativo', 'Baixa', 25, 'Medicina Interna A - triagem', '2026-06-24 00:08:48', NULL),
	(1367, 'EQ-00130-ZAQ', 'Termómetro Infravermelho Timpânico', 17, 'Braun', 'ThermoScan PRO 6000', 'BR-TSP-2022-11230', '2022', '2022-09-05', 220.00, 'Compra', 'Ativo', 'Baixa', 22, 'Urgência - triagem', '2026-06-24 00:08:48', NULL),
	(1368, 'EQ-00131-ANH', 'Balança Digital de Precisão', 17, 'Seca', '803', 'SEC-803-2021-66543', '2021', '2021-10-20', 750.00, 'Compra', 'Ativo', 'Baixa', 22, 'Urgência - peso e IMC', '2026-06-24 00:08:48', NULL),
	(1369, 'EQ-00132-BHR', 'Negatoscópio LED Duplo', 18, 'Waldmann', 'Diplom 2', 'WAL-2020-44213', '2020', '2020-05-28', 1200.00, 'Compra', 'Ativo', 'Baixa', 47, 'Laboratório - leitura de radiografias', '2026-06-24 00:08:48', NULL),
	(1370, 'EQ-00133-CSW', 'Foco de Exame LED', 16, 'Rimsa', 'Starlight', 'RIM-2023-22104', '2023', '2023-05-15', 380.00, 'Compra', 'Ativo', 'Baixa', 43, 'Consulta Externa - sala de exames', '2026-06-24 00:08:48', NULL),
	(1371, 'EQ-00134-DJY', 'Manta de Aquecimento Forçado', 16, '3M', 'Bair Hugger 775', '3M-BH-2022-33120', '2022', '2022-01-30', 3500.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - prevenção hipotermia', '2026-06-24 00:08:48', NULL),
	(1372, 'EQ-00135-EFE', 'Carro de Emergência (desfibrilhador + fármacos)', 13, 'Ergotron', 'CareFit Pro', 'ERG-2022-55342', '2022', '2022-07-01', 4800.00, 'Compra', 'Ativo', 'Suporte de vida', 22, 'Urgência - carro de paragem padrão', '2026-06-24 00:08:48', NULL),
	(1373, 'EQ-00136-FWS', 'Nebulizador Ultrassónico', 19, 'PARI', 'PARI BOY SX', 'PAR-2021-77543', '2021', '2021-11-25', 850.00, 'Compra', 'Ativo', 'Média', 46, 'Pneumologia - terapia inalatória', '2026-06-24 00:08:48', NULL),
	(1374, 'EQ-00137-GUT', 'Câmara Hiperbárica', 19, 'Perry Baromedical', '1300', 'PER-2020-88765', '2020', '2020-03-15', 185000.00, 'Compra', 'Ativo', 'Alta', 51, 'Medicina Hiperbárica - sala principal', '2026-06-24 00:08:48', NULL),
	(1375, 'EQ-00138-HBT', 'Robot Cirúrgico Assistido (Intuitive)', 16, 'Intuitive Surgical', 'Da Vinci Xi', 'INT-DV-2022-99001', '2022', '2022-12-01', 2100000.00, 'Compra', 'Ativo', 'Suporte de vida', 37, 'Bloco Operatório - cirurgia robótica', '2026-06-24 00:08:48', NULL),
	(1376, 'EQ-00139-IGG', 'Sistema de Arquivo PACS/RIS', 18, 'Sectra', 'IDS7', 'SEC-IDS-2021-11223', '2021', '2021-04-01', 95000.00, 'Compra', 'Ativo', 'Alta', 43, 'Imagiologia - servidor principal', '2026-06-24 00:08:48', NULL),
	(1377, 'EQ-00140-JPL', 'Angiógrafo Digital Subtração (DSA)', 18, 'Siemens', 'Artis zee', 'SIE-AZ-2022-44213', '2022', '2022-09-15', 980000.00, 'Compra', 'Ativo', 'Alta', 24, 'Cardiologia - sala de cateterismo', '2026-06-24 00:08:48', NULL),
	(1378, 'EQ-00141-KLK', 'Broncoscópio Flexível Vídeo', 18, 'Olympus', 'BF-Q180AC', 'OLY-BF-2023-22104', '2023', '2023-01-25', 35000.00, 'Compra', 'Ativo', 'Alta', 46, 'Pneumologia - broncoscopia', '2026-06-24 00:08:48', NULL),
	(1379, 'EQ-00142-LGT', 'Esfigmomanómetro Aneroide', 20, 'Riester', 'e-mega', 'RIE-2022-66543', '2022', '2022-10-12', 185.00, 'Compra', 'Ativo', 'Baixa', 26, 'Medicina Interna B - leitos', '2026-06-24 00:08:48', NULL),
	(1380, 'EQ-00143-MAT', 'Cadeira de Phlebotomia', 11, 'Medical Expo', 'PHL-500', 'MEX-2021-44321', '2021', '2021-06-30', 850.00, 'Compra', 'Ativo', 'Baixa', 47, 'Laboratório - colheita de sangue', '2026-06-24 00:08:48', NULL),
	(1381, 'EQ-00144-NVP', 'Espirómetro Portátil de Triagem', 17, 'Vitalograph', '2120', 'VIT-2022-22104', '2022', '2022-03-18', 2200.00, 'Compra', 'Ativo', 'Média', 22, 'Urgência - avaliação respiratória', '2026-06-24 00:08:48', NULL),
	(1382, 'EQ-00145-OFO', 'Ecógrafo Point of Care (POCUS)', 18, 'SonoSite', 'M-Turbo', 'SNS-2022-55342', '2022', '2022-07-20', 38000.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - avaliação rápida beira-leito', '2026-06-24 00:08:48', NULL),
	(1383, 'EQ-00146-PAW', 'Monitor de BIS (Índice Biespectral)', 17, 'Medtronic', 'BIS VISTA', 'MED-BIS-2021-88543', '2021', '2021-09-10', 8500.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - profundidade anestesia', '2026-06-24 00:08:48', NULL),
	(1384, 'EQ-00147-QPQ', 'Estimulador de Nervos Periféricos', 17, 'Fisher & Paykel', 'Nerve Stimulator', 'FP-NS-2022-11230', '2022', '2022-12-15', 3800.00, 'Compra', 'Ativo', 'Alta', 37, 'Bloco Operatório - bloqueios', '2026-06-24 00:08:48', NULL),
	(1385, 'EQ-00148-RTG', 'Doppler Transcraniano', 17, 'Spencer Technologies', 'PMD 150', 'SPC-PMD-2021-33120', '2021', '2021-08-04', 28000.00, 'Compra', 'Ativo', 'Alta', 45, 'Neurologia - AVC/stroke', '2026-06-24 00:08:48', NULL),
	(1386, 'EQ-00149-SQW', 'Bomba de Heparina (ECMO)', 21, 'Maquet', 'Rotaflow II', 'MAQ-RF-2022-66543', '2022', '2022-05-15', 45000.00, 'Compra', 'Ativo', 'Suporte de vida', 23, 'UCI - suporte ECMO', '2026-06-24 00:08:48', NULL),
	(1387, 'EQ-00150-TGR', 'Sistema de Monitorização Contínua de Glicose', 17, 'Dexcom', 'G7 Professional', 'DEX-G7-2023-44213', '2023', '2023-08-01', 1800.00, 'Compra', 'Ativo', 'Alta', 23, 'UCI - controlo glicémico intensivo', '2026-06-24 00:08:48', NULL);

-- A despejar estrutura para tabela db1241841.equipamento_fornecedor
CREATE TABLE IF NOT EXISTS `equipamento_fornecedor` (
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `tipo_relacao` enum('Fabricante','Distribuidor','Assistência técnica') COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`equipamento_id`,`fornecedor_id`),
  KEY `FK_equip_fornec_fornecedor` (`fornecedor_id`),
  CONSTRAINT `FK_equip_fornec_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_equip_fornec_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.equipamento_fornecedor: ~12 rows (aproximadamente)
INSERT INTO `equipamento_fornecedor` (`equipamento_id`, `fornecedor_id`, `tipo_relacao`) VALUES
	(2, 35, 'Fabricante'),
	(153, 6, 'Fabricante'),
	(1248, 13, 'Fabricante'),
	(1268, 17, 'Fabricante'),
	(1281, 27, 'Fabricante'),
	(1288, 26, 'Fabricante'),
	(1292, 14, 'Fabricante'),
	(1294, 27, 'Fabricante'),
	(1312, 14, 'Fabricante'),
	(1354, 27, 'Fabricante'),
	(1361, 28, 'Fabricante'),
	(1362, 12, 'Fabricante');

-- A despejar estrutura para tabela db1241841.fornecedores
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `nif` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `tipo` enum('Fabricante','Distribuidor','Assistência Técnica','Consumíveis') COLLATE utf8mb4_bin NOT NULL,
  `telefone` varchar(30) COLLATE utf8mb4_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `morada` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL,
  `website` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `tecnico_nome` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `tecnico_telefone` varchar(30) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `arquivado` tinyint(1) NOT NULL DEFAULT '0',
  `criado_em` datetime NOT NULL DEFAULT (now()),
  `atualizado_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_fornecedores_nif` (`nif`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.fornecedores: ~51 rows (aproximadamente)
INSERT INTO `fornecedores` (`id`, `nome`, `nif`, `tipo`, `telefone`, `email`, `morada`, `website`, `tecnico_nome`, `tecnico_telefone`, `observacoes`, `arquivado`, `criado_em`, `atualizado_em`) VALUES
	(6, 'Philips Medical Systems Portugal', '501234567', 'Fabricante', '+351 210 300 400', 'suporte.pt@philips.com', 'Av. da Liberdade, 120 - 4º Esq, 1250-145 Lisboa', '', 'Eng. Ricardo Silva', '+351 910 111 222', '', 0, '2026-06-16 22:37:41', '2026-06-18 13:02:03'),
	(7, 'Siemens Healthineers Portugal Lda', '502345678', 'Fabricante', '+351 214 178 000', 'service.pt@siemens-healthineers.com', 'Rua Lopo Soares de Albergaria, 300, 2710-089 Sintra', 'https://www.siemens-healthineers.com/pt', 'Eng.ª Sofia Martins', '+351 920 333 444', 'Responsável pelos equipamentos de Imagiologia (TAC, Ressonância e Raio-X).', 0, '2026-06-16 22:37:41', NULL),
	(8, 'Biomedical Distribuição S.A.', '503456789', 'Distribuidor', '+351 229 555 111', 'comercial@biomedical.pt', 'Zona Industrial da Maia, Lote 14, 4470-200 Maia', 'https://www.biomedical.pt', 'Carlos Mendes', '+351 930 555 666', 'Distribuidor oficial de desfibrilhadores e consumíveis cirúrgicos.', 0, '2026-06-16 22:37:41', NULL),
	(9, 'TecnoMedica - Assistência Técnica Hospitalar', '504567890', 'Assistência Técnica', '+351 239 888 222', 'urgencias@tecnomedica.pt', 'Estrada de Coimbra, Pavilhão B, 3040-300 Coimbra', 'https://www.tecnomedica.pt', 'Téc. João Pereira', '+351 960 777 888', 'Empresa externa contratada para calibrações de autoclaves e ecógrafos antigos.', 0, '2026-06-16 22:37:41', NULL),
	(10, 'Gasoxmed - Gases Medicinais e Consumíveis', '505678901', 'Consumíveis', '+351 218 999 333', 'encomendas@gasoxmed.pt', 'Parque Tecnológico de Palmela, Rua 4, 2950-400 Palmela', 'https://www.gasoxmed.pt', 'Marta Rodrigues', '+351 915 999 111', 'Fornecedor de fluxómetros, tomadas de vácuo, tubagens e máscaras de O2.', 0, '2026-06-16 22:37:41', NULL),
	(11, 'Medtronic Portugal S.A.', '506123456', 'Fabricante', '+351 217 245 100', 'rs.portugal@medtronic.com', 'Rua do Pólo Norte, Lote 1.06.1.1, 1990-235 Lisboa', 'https://www.medtronic.pt', 'Eng. Bruno Fonseca', '+351 911 888 222', 'Fornecedor oficial de pacemakers, bombas de insulina e laparoscopia.', 0, '2026-06-16 22:37:41', NULL),
	(12, 'GE HealthCare Portugal', '507234567', 'Fabricante', '+351 214 242 500', 'gehc.portugal@ge.com', 'Av. do Forte, n.º 3 - Edifício Suécia III, 2794-044 Carnaxide', 'https://www.gehealthcare.com', 'Eng.ª Mariana Costa', '+351 925 444 777', 'Contacto técnico para ecógrafos, arcos cirúrgicos e anestesia.', 0, '2026-06-16 22:37:41', NULL),
	(13, 'Dräger Portugal Lda', '508345678', 'Fabricante', '+351 211 554 500', 'assistencia.clientes@draeger.com', 'Rua da Volga, n.º 8, 1990-410 Lisboa', 'https://www.draeger.com', 'Eng. Pedro Álvares', '+351 932 111 000', 'Especialistas em incubadoras neonatais, ventiladores de UCI e calibração.', 0, '2026-06-16 22:37:41', NULL),
	(14, 'Stryker Portugal', '509456789', 'Fabricante', '+351 214 134 600', 'suporte.iberia@stryker.com', 'Lagoas Park, Edifício 5B - Piso 2, 2740-245 Porto Salvo', 'https://www.stryker.com', 'Téc. Miguel Antunes', '+351 961 999 888', 'Camas articuladas hospitalares, macas de urgência e motores cirúrgicos.', 0, '2026-06-16 22:37:41', NULL),
	(15, 'Olympus Medical Portugal', '510567890', 'Fabricante', '+351 217 543 200', 'medical.pt@olympus.es', 'Parque das Nações, Alameda dos Oceanos, 1990-200 Lisboa', 'https://www.olympus.pt', 'Eng.ª Ana Rita Santos', '+351 914 777 333', 'Fornecedor exclusivo de endoscópios, colonoscópios e óticas cirúrgicas.', 0, '2026-06-16 22:37:41', NULL),
	(16, 'IberBiomedica - Distribuição de Equipamento', '511678901', 'Distribuidor', '+351 229 000 111', 'geral@iberbiomedica.pt', 'Rua de Santa Catarina, 450, 4000-444 Porto', 'https://www.iberbiomedica.pt', 'Francisco Guedes', '+351 918 222 333', 'Distribuidor multimarcas de pequena instrumentação (oxímetros, termómetros).', 0, '2026-06-16 22:37:41', NULL),
	(17, 'Hospitalia - Soluções Integradas', '512789012', 'Distribuidor', '+351 219 450 600', 'encomendas@hospitalia.pt', 'Estrada Nacional 10, Km 130, 2695-011 Bobadela', 'https://www.hospitalia.pt', 'Paula Vicência', '+351 939 444 555', 'Logística e distribuição de mobiliário clínico e focos cirúrgicos.', 0, '2026-06-16 22:37:41', NULL),
	(18, 'EuroMedix Portugal S.A.', '513890123', 'Distribuidor', '+351 239 499 800', 'info@euromedix.pt', 'iParque - Parque Tecnológico de Coimbra, 3040-540 Coimbra', 'https://www.euromedix.pt', 'Nuno Albuquerque', '+351 965 111 222', 'Importador oficial de dispositivos de diagnóstico rápido e testes laboratoriais.', 0, '2026-06-16 22:37:41', NULL),
	(19, 'Biomedicália Norte - Equipamentos Médicos', '514901234', 'Distribuidor', '+351 253 600 700', 'comercial@biomedicalianorte.pt', 'Av. Central, 89 - 2º Andar, 4710-228 Braga', 'https://www.biomedicalianorte.pt', 'Ricardo Jorge', '+351 912 333 999', 'Fornece e distribui consumíveis e equipamentos para reabilitação.', 0, '2026-06-16 22:37:41', NULL),
	(20, 'Clinitec - Engenharia Clínica', '515012345', 'Assistência Técnica', '+351 224 888 999', 'suporte@clinitec.pt', 'Rua das Forças Armadas, 1024, 4200-000 Porto', 'https://www.clinitec.pt', 'Téc. Carlos Antunes', '+351 936 777 111', 'Manutenção corretiva e preventiva de bombas de perfusão e seringas.', 0, '2026-06-16 22:37:41', NULL),
	(21, 'CalibraMed - Metrologia Hospitalar', '516123456', 'Assistência Técnica', '+351 218 123 456', 'laboratorio@calibramed.pt', 'Pólo Tecnológico de Lisboa, Rua 2, 1600-485 Lisboa', 'https://www.calibramed.pt', 'Eng.ª Luísa Melo', '+351 919 555 444', 'Contrato de testes de segurança elétrica e calibração de balanças.', 0, '2026-06-16 22:37:41', NULL),
	(22, 'Biomédica Centro - Reparações Médicas', '517234567', 'Assistência Técnica', '+351 244 500 600', 'tecnico@biomedicacentro.pt', 'Zona Industrial de Leiria, Pavilhão 4, 2400-000 Leiria', 'https://www.biomedicacentro.pt', 'Téc. Fernando Rocha', '+351 962 444 111', 'Assistência rápida local para aspiração de secreções e marquesas.', 0, '2026-06-16 22:37:41', NULL),
	(23, 'SumiSaúde - Consumíveis Hospitalares', '518345678', 'Consumíveis', '+351 256 700 800', 'vendas@sumisaude.pt', 'Zona Industrial de Ovar, Rua dos Operários, 3880-000 Ovar', 'https://www.sumisaude.pt', 'Diana Ribeiro', '+351 913 888 777', 'Fornecedor de elétrodos, gel de eco, mangas de esterilização e papel de ECG.', 0, '2026-06-16 22:37:41', NULL),
	(24, 'Gases Oxicentro Lda', '519456789', 'Consumíveis', '+351 249 300 300', 'logistica@oxicentro.pt', 'Zona Industrial de Tomar, Lote 45, 2300-000 Tomar', 'https://www.oxicentro.pt', 'Vítor Neto', '+351 927 666 555', 'Abastecimento de garrafas de Oxigénio Medicinal e Óxido Nitroso.', 0, '2026-06-16 22:37:41', NULL),
	(25, 'CardioFoco - Material de Cardiologia', '520567890', 'Consumíveis', '+351 212 900 900', 'encomendas@cardiofoco.pt', 'Av. D. João II, Edifício Adamastor, 1990-012 Lisboa', 'https://www.cardiofoco.pt', 'Marta Silveira', '+351 968 333 222', 'Fornecimento de cateteres, guias e elétrodos cirúrgicos avançados.', 0, '2026-06-16 22:37:41', NULL),
	(26, 'Roche Diagnostics Portugal', '521678901', 'Fabricante', '+351 214 257 000', 'pt.roche@roche.com', 'Estrada Nacional 249-1, 2720-413 Amadora', 'https://www.roche.pt', 'Eng. Tiago Neves', '+351 915 222 666', 'Equipamentos laboratoriais analíticos de grande porte e reagentes clínicos.', 0, '2026-06-16 22:37:41', NULL),
	(27, 'Abbott Laboratories Portugal', '522789012', 'Fabricante', '+351 214 467 100', 'suporte@abbott.pt', 'Rua Castilho, 165 - 4º, 1070-050 Lisboa', 'https://www.abbott.pt', 'Eng.ª Cláudia Silva', '+351 931 444 888', 'Sistemas de monitorização de glicose e analisadores de hematologia.', 0, '2026-06-16 22:37:41', NULL),
	(28, 'B. Braun Medical Lda', '523890123', 'Fabricante', '+351 214 368 300', 'info.pt@bbraun.com', 'Estrada de Queluz, Parque Alfragide, 2610-141 Amadora', 'https://www.bbraun.pt', 'Eng. Vítor Santos', '+351 964 111 555', 'Sistemas de perfusão, bombas de seringa, suturas e material cirúrgico.', 0, '2026-06-16 22:37:41', NULL),
	(29, 'Baxter Médico-Farmacêutica', '524901234', 'Fabricante', '+351 219 403 500', 'portugal@baxter.com', 'Sintra Business Park, Edifício 10, 2710-089 Sintra', 'https://www.baxter.pt', 'Eng.ª Paula Franco', '+351 912 777 444', 'Equipamentos de hemodiálise, diálise peritoneal e respetivos circuitos.', 0, '2026-06-16 22:37:41', NULL),
	(30, 'Boston Scientific Portugal', '525012345', 'Fabricante', '+351 213 138 900', 'bsc.portugal@bsci.com', 'Av. da República, 50 - 2º, 1050-196 Lisboa', 'https://www.bostonscientific.com', 'Téc. Jorge Rocha', '+351 936 222 999', 'Dispositivos médicos para cardiologia intervencionista e endoscopia.', 0, '2026-06-16 22:37:41', NULL),
	(31, 'Zimmer Biomet Portugal', '526123456', 'Fabricante', '+351 214 243 000', 'info.portugal@zimmerbiomet.com', 'Lagoas Park, Edifício 6 - Piso 1, 2740-244 Porto Salvo', 'https://www.zimmerbiomet.com', 'Eng. Gonçalo Dias', '+351 917 555 111', 'Implantes ortopédicos, próteses e instrumentação cirúrgica associada.', 0, '2026-06-16 22:37:41', NULL),
	(32, 'Smith & Nephew Portugal', '527234567', 'Fabricante', '+351 214 123 200', 'pt.info@smith-nephew.com', 'Taguspark, Edifício Inovação II, 2740-122 Porto Salvo', 'https://www.smith-nephew.com', 'Eng.ª Marta Vale', '+351 924 999 000', 'Equipamentos de artroscopia, medicina desportiva e pensos avançados.', 0, '2026-06-16 22:37:41', NULL),
	(33, 'Linde Saúde Lda', '528345678', 'Consumíveis', '+351 214 246 400', 'encomendas.saude@linde.com', 'Av. Infante D. Henrique, Lote 21, 1800-217 Lisboa', 'https://www.lindesaude.pt', 'Rui Moreira', '+351 961 444 333', 'Oxigénio líquido hospitalar, concentradores de O2 e ventiladores CPAP.', 0, '2026-06-16 22:37:41', NULL),
	(34, 'Air Liquide Medicinal S.A.', '529456789', 'Consumíveis', '+351 214 167 300', 'medicinal.pt@airliquide.com', 'Rua Dr. António Loureiro Borges, 2794-045 Algés', 'https://www.airliquide.com', 'José Augusto', '+351 933 888 111', 'Instalações de redes de gases medicinais e fornecimento de óxido nítrico.', 0, '2026-06-16 22:37:41', NULL),
	(35, 'Steris Portugal - Sistemas de Esterilização', '530567890', 'Fabricante', '+351 210 114 500', 'info_steris@steris.com', 'Zona Industrial de Alfragide, Lote 3, 2610-000 Amadora', 'https://www.steris.com', 'Eng. Hugo Pires', '+351 919 666 777', 'Lava-desinfetadoras, autoclaves de grande porte e focos de bloco operatório.', 0, '2026-06-16 22:37:41', NULL),
	(36, 'Gilmédica - Dispositivos Médicos Lda', '531678901', 'Distribuidor', '+351 229 398 400', 'geral@gilmedica.com', 'Rua D. Marcos da Cruz, 1425, 4455-482 Perafita', 'https://www.gilmedica.com', 'Manuel Moura', '+351 918 444 555', 'Distribuição de consumíveis de anestesia, vias aéreas e monitorização.', 0, '2026-06-16 22:37:41', NULL),
	(37, 'GeriSaúde - Equipamentos de Reabilitação', '532789012', 'Distribuidor', '+351 251 400 300', 'comercial@gerisaude.pt', 'Zona Industrial de Valença, Lote 12, 4930-000 Valença', 'https://www.gerisaude.pt', 'Téc. Alberto Costa', '+351 935 111 777', 'Distribuidor de andarilhos, gruas de transferência de doentes e cadeiras de rodas.', 0, '2026-06-16 22:37:41', NULL),
	(38, 'CardioMedica - Soluções Cardiovasculares', '533890123', 'Distribuidor', '+351 213 511 200', 'suporte@cardiomedica.pt', 'Rua Tomás Ribeiro, 45 - 2º, 1050-225 Lisboa', 'https://www.cardiomedica.pt', 'Diana Antunes', '+351 962 333 444', 'Distribuidor de ecocardiógrafos portáteis e holters de ECG.', 0, '2026-06-16 22:37:41', NULL),
	(39, 'OftalExpress - Material Oftálmico', '534901234', 'Distribuidor', '+351 226 099 800', 'pedidos@oftalexpress.pt', 'Rua Júlio Dinis, 820 - 4º, 4050-318 Porto', 'https://www.oftalexpress.pt', 'Nuno Seixas', '+351 914 888 111', 'Distribuição de lâmpadas de fenda, tonómetros e consumíveis de cirurgia oftálmica.', 0, '2026-06-16 22:37:41', NULL),
	(40, 'LaboNorte - Produtos de Laboratório', '535012345', 'Consumíveis', '+351 253 200 900', 'encomendas@labonorte.pt', 'Parque Industrial de Celeirós, Lote 8, 4705-414 Braga', 'https://www.labonorte.pt', 'Silvia Ferreira', '+351 927 555 999', 'Tubos de colheita de sangue, ligaduras, agulhas e material plástico de laboratório.', 0, '2026-06-16 22:37:41', NULL),
	(41, 'Medisport - Medicina Desportiva', '536123456', 'Consumíveis', '+351 234 422 111', 'geral@medisport.pt', 'Av. Dr. Lourenço Peixinho, 14, 3800-159 Aveiro', 'https://www.medisport.pt', 'Téc. Pedro Ribeiro', '+351 913 222 444', 'Gelo químico, ligaduras neuromusculares (Kinesio) e marquesas portáteis.', 0, '2026-06-16 22:37:41', NULL),
	(42, 'SurgicalStore - Instrumentação Cirúrgica', '537234567', 'Consumíveis', '+351 225 300 200', 'vendas@surgicalstore.pt', 'Rua de Costa Cabral, 1800, 4200-215 Porto', 'https://www.surgicalstore.pt', 'António Simões', '+351 969 444 888', 'Pinças, bisturis elétricos, tesouras cirúrgicas e caixas de esterilização.', 0, '2026-06-16 22:37:41', NULL),
	(43, 'BioRad Laboratories Portugal', '538345678', 'Fabricante', '+351 214 720 700', 'tech_support@bio-rad.com', 'Av. Reinaldo dos Santos, 14 - Loja D, 2790-136 Carnaxide', 'https://www.bio-rad.com', 'Eng.ª Rita Lopes', '+351 911 333 888', 'Sistemas de eletroforese, controlo de qualidade laboratorial e termocicladores.', 0, '2026-06-16 22:37:41', NULL),
	(44, 'Nihon Kohden Europe (Portugal)', '539456789', 'Fabricante', '+351 212 400 500', 'suporte@nihonkohden.pt', 'Av. da República, 102 - 3º, 1050-191 Lisboa', 'https://www.nihonkohden.com', 'Eng. Carlos Viegas', '+351 932 777 666', 'Equipamentos de EEG (Eletroencefalografia), ECG e desfibrilhadores de bancada.', 0, '2026-06-16 22:37:41', NULL),
	(45, 'Mindray Medical Portugal', '540567890', 'Fabricante', '+351 211 200 300', 'service.pt@mindray.com', 'Edifício Zenith, Av. Dr. António Loureiro Borges, 2795-002 Algés', 'https://www.mindray.com', 'Eng. Alexandre Cruz', '+351 965 444 111', 'Equipamentos de anestesia, ecógrafos e monitores de sinais vitais de UCI.', 0, '2026-06-16 22:37:41', NULL),
	(46, 'ServiBiomed - Assistência Técnica Global', '541678901', 'Assistência Técnica', '+351 219 150 200', 'geral@servibiomed.pt', 'Zona Industrial de Sintra, Pavilhão 8, 2710-000 Sintra', 'https://www.servibiomed.pt', 'Téc. Marco Santos', '+351 916 222 333', 'Manutenção preventiva multimarcas em desfibrilhadores e eletrocardiógrafos.', 0, '2026-06-16 22:37:41', NULL),
	(47, 'Metrologia Médica Vasconcelos', '542789012', 'Assistência Técnica', '+351 227 111 300', 'ensaios@mmvasconcelos.pt', 'Rua de Camões, 340, 4400-000 Vila Nova de Gaia', 'https://www.mmvasconcelos.pt', 'Eng.ª Inês Moura', '+351 933 555 777', 'Ensaios de segurança elétrica hospitalar segundo a norma IEC 62353.', 0, '2026-06-16 22:37:41', NULL),
	(48, 'EndoReparar - Manutenção de Endoscopia', '543890123', 'Assistência Técnica', '+351 252 600 400', 'oficina@endoreparar.pt', 'Zona Industrial de Vila do Conde, Pavilhão A2, 4480-000 Vila do Conde', 'https://www.endoreparar.pt', 'Téc. Paulo Moreira', '+351 918 999 444', 'Laboratório especializado na reparação ótica e mecânica de endoscópios flexíveis.', 0, '2026-06-16 22:37:41', NULL),
	(49, 'X-Ray Service Portugal', '544901234', 'Assistência Técnica', '+351 214 900 800', 'suporte@xrayservice.pt', 'Estrada de Paço de Arcos, 88, 2735-336 Cacém', 'https://www.xrayservice.pt', 'Téc. Luís Antunes', '+351 963 888 999', 'Manutenção e substituição de ampolas de Raio-X e calibração de dosímetros.', 0, '2026-06-16 22:37:41', NULL),
	(50, 'LaserMedica - Reparação de Lasers Clínicos', '545012345', 'Assistência Técnica', '+351 239 400 100', 'tecnicos@lasermedica.pt', 'Pólo II da Universidade de Coimbra, Pavilhão C, 3030-290 Coimbra', 'https://www.lasermedica.pt', 'Eng. Daniel Rocha', '+351 926 777 555', 'Manutenção corretiva de lasers cirúrgicos CO2, Nd:YAG e luz pulsada.', 0, '2026-06-16 22:37:41', NULL),
	(51, 'SaúdePrime Consumíveis Lda', '546123456', 'Consumíveis', '+351 217 900 100', 'comercial@saudeprime.pt', 'Av. das Forças Armadas, 40, 1600-000 Lisboa', 'https://www.saudeprimeconsumiveis.pt', 'Mariana Neves', '+351 914 111 888', 'Fornecimento de luvas de nitrilo, batas cirúrgicas e máscaras FFP2.', 0, '2026-06-16 22:37:41', NULL),
	(52, 'GastroFoco - Material de Gastroenterologia', '547234567', 'Consumíveis', '+351 229 400 300', 'pedidos@gastrofoco.pt', 'Rua de Álvaro Castelões, 120, 4450-040 Matosinhos', 'https://www.gastrofoco.pt', 'Filipa Santos', '+351 936 888 222', 'Fornecedor de pinças de biópsia descartáveis e canais de água para endoscopia.', 0, '2026-06-16 22:37:41', NULL),
	(53, 'DialiseMundo - Produtos de Nefrologia', '548345678', 'Consumíveis', '+351 218 300 200', 'encomendas@dialisemundo.pt', 'Av. Infante D. Henrique, Lote 15, 1900-000 Lisboa', 'https://www.dialisemundo.pt', 'Téc. Rui Baptista', '+351 968 444 333', 'Fornecimento de dialisadores, linhas de sangue e concentrados de diálise.', 0, '2026-06-16 22:37:41', NULL),
	(54, 'AnestSumi - Material de Anestesia', '549456789', 'Consumíveis', '+351 225 100 500', 'vendas@anestsumi.pt', 'Rua de Gondarem, 450, 4150-372 Porto', 'https://www.anestsumi.pt', 'Ricardo Mesquita', '+351 912 555 666', 'Circuitos respiratórios descartáveis, filtros antibacterianos e tubos endotraqueais.', 0, '2026-06-16 22:37:41', NULL),
	(55, 'EsteriConsum - Produtos de Central de Esterilização', '550567890', 'Consumíveis', '+351 214 500 900', 'geral@estericonsum.pt', 'Zona Industrial de Casais de Mem Martins, 2725-000 Sintra', 'https://www.estericonsum.pt', 'Isabel Andrade', '+351 939 222 111', 'Indicadores químicos e biológicos para autoclaves e rolos de papel/filme.', 0, '2026-06-16 22:37:41', NULL),
	(56, 'Bioglow Soluções Clínicas Lda', '551678901', 'Assistência Técnica', '+351 215 700 900', 'suporte@bioglow.pt', 'Rua dos Inventores, Pavilhão 3A, 4460-000 Matosinhos', 'https://www.bioglow.pt', 'Eng. Nuno Jardim', '+351 912 999 333', 'ESPECIALISTAS EM FOTOTERAPIA E INCUBADORAS NEONATAIS.', 0, '2026-06-16 22:42:06', NULL),
	(57, 'Teste', '456392876', 'Fabricante', '+351 215 700 900', 'teste@gmail.com', 'R. Dr. Teste 431', 'http://teste.pt', 'Eng. Inês Moreira', '+351 912 345 678', NULL, 1, '2026-06-18 10:40:27', NULL);

-- A despejar estrutura para tabela db1241841.garantias
CREATE TABLE IF NOT EXISTS `garantias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_contrato` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT 'Ex: CTR-DRG-0001',
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL COMMENT 'opcional',
  `tipo` enum('Garantia de Fábrica','Contrato de Manutenção','Outro') COLLATE utf8mb4_bin NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `periodicidade` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `ficheiro_path` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Caminho do PDF local (Forma 1)',
  `url_externo` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Link para a Cloud/OneDrive (Forma 2)',
  `observacoes` text COLLATE utf8mb4_bin,
  `arquivado` tinyint(1) NOT NULL DEFAULT '0',
  `criado_em` datetime NOT NULL DEFAULT (now()),
  `atualizado_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_garantias_num_contrato` (`num_contrato`),
  KEY `FK_garantias_equipamento` (`equipamento_id`),
  KEY `FK_garantias_fornecedor` (`fornecedor_id`),
  CONSTRAINT `FK_garantias_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_garantias_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`) ON DELETE SET NULL,
  CONSTRAINT `CK_garantias_datas` CHECK ((`data_fim` > `data_inicio`))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.garantias: ~1 rows (aproximadamente)
INSERT INTO `garantias` (`id`, `num_contrato`, `equipamento_id`, `fornecedor_id`, `tipo`, `data_inicio`, `data_fim`, `periodicidade`, `ficheiro_path`, `url_externo`, `observacoes`, `arquivado`, `criado_em`, `atualizado_em`) VALUES
	(5, 'SLA-PHI-2026-001', 153, 6, 'Contrato de Manutenção', '2026-01-01', '2027-12-31', 'Anual', 'CTR_1781989640_3c9a973e.pdf', NULL, '', 0, '2026-06-20 19:40:32', NULL);

-- A despejar estrutura para tabela db1241841.localizacoes
CREATE TABLE IF NOT EXISTS `localizacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_bin NOT NULL COMMENT 'Ex: SRV-UCI',
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL COMMENT 'Ex: Unidade de Cuidados Intensivos',
  `edificio` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT 'Ex: Bloco B - Cirúrgico',
  `piso` tinyint NOT NULL DEFAULT '0',
  `responsavel` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `arquivado` tinyint(1) NOT NULL DEFAULT '0',
  `criado_em` datetime NOT NULL DEFAULT (now()),
  `atualizado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_localizacoes_codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.localizacoes: ~50 rows (aproximadamente)
INSERT INTO `localizacoes` (`id`, `codigo`, `nome`, `edificio`, `piso`, `responsavel`, `observacoes`, `arquivado`, `criado_em`, `atualizado_em`) VALUES
	(22, 'HOS-PUG', 'Serviço De Urgência Geral', 'Bloco A', 0, 'Dra. Marta Antunes', 'Área de triagem e reanimação rápida.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(23, 'HOS-UCIP', 'Unidade De Cuidados Intensivos Polivalente', 'Bloco A', 1, 'Dr. Carlos Seabra', 'Equipamentos de suporte de vida permanente.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(24, 'HOS-CCOR', 'Unidade De Cuidados Intensivos Coronários', 'Bloco A', 1, 'Dra. Sílvia Ribeiro', 'Monitores cardíacos e telemetria avançada.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(25, 'HOS-MED1', 'Enfermaria De Medicina Interna A', 'Bloco A', 2, 'Enf. Chefe João Lucas', 'Camas de internamento geral.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(26, 'HOS-MED2', 'Enfermaria De Medicina Interna B', 'Bloco A', 3, 'Enf. Chefe Clara Mendes', 'Ala norte de internamento.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(27, 'HOS-CARD', 'Serviço De Cardiologia e Diagnóstico', 'Bloco A', 2, 'Dr. Ricardo Veloso', 'Salas de eletrocardiografia e ecocardiogramas.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(28, 'HOS-NEUR', 'Unidade De AVC e Neurologia', 'Bloco A', 3, 'Dra. Beatriz Braga', 'Vigilância neurológica contínua.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(29, 'HOS-PNEU', 'Serviço De Pneumologia e Função Respiratória', 'Bloco A', 4, 'Dr. Artur Fonseca', 'Laboratório de exames respiratórios.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(30, 'HOS-GAST', 'Unidade De Gastrenterologia e Endoscopia', 'Bloco A', 4, 'Dra. Joana Coimbra', 'Salas de exames endoscópicos invasivos.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(31, 'HOS-HEMO', 'Unidade De Hemodiálise Central', 'Bloco A', 0, 'Eng. Miguel Cruz', 'Reclinares com postos de diálise dedicados.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(32, 'CIR-CBL1', 'Bloco Operatório - Sala 1 Geral', 'Bloco B', 1, 'Dra. Helena Matos', 'Sala equipada para cirurgia geral.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(33, 'CIR-CBL2', 'Bloco Operatório - Sala 2 Ortopedia', 'Bloco B', 1, 'Dr. Nuno Quintas', 'Foco em cirurgia ortopédica e trauma.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(34, 'CIR-CBL3', 'Bloco Operatório - Sala 3 Neurocirurgia', 'Bloco B', 1, 'Dr. Fernando Pais', 'Equipamento de microscopia e neuronavegação.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(35, 'CIR-UCPA', 'Unidade De Cuidados Pós-Anestésicos (Recobro)', 'Bloco B', 1, 'Enf. Tiago Brandão', 'Monitorização de doentes em pós-operatório imediato.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(36, 'CIR-AMBU', 'Unidade De Cirurgia De Ambulatório', 'Bloco B', 0, 'Dra. Mafalda Costa', 'Procedimentos cirúrgicos de dia.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(37, 'CIR-CORT', 'Enfermaria De Cirurgia Ortopédica', 'Bloco B', 2, 'Enf. Chefe Rui Santos', 'Internamento pós-cirúrgico de ortopedia.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(38, 'CIR-CGEN', 'Enfermaria De Cirurgia Geral', 'Bloco B', 3, 'Enf. Chefe Laura Sousa', 'Internamento pós-cirúrgico geral.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(39, 'IMG-RX1', 'Radiologia Convencional - Sala 1', 'Bloco B', -1, 'Tec. Superior José Vila', 'Equipamento fixo de Raios-X digital.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(40, 'IMG-TAC1', 'Sala De Tomografia Computorizada (TAC)', 'Bloco B', -1, 'Dra. Isabel Rocha', 'Equipamento de TAC de alta resolução.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(41, 'IMG-RMN1', 'Sala De Ressonância Magnética', 'Bloco B', -1, 'Eng. Pedro Moreira', 'Sala blindada (Gaiola de Faraday).', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(42, 'IMG-ECO1', 'Sala De Ecografia Geral e Vascular', 'Bloco B', 0, 'Dra. Sofia Nogueira', 'Ecógrafos portáteis e fixos.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(43, 'IMG-MAM0', 'Gabinete De Mamografia Digital', 'Bloco B', 0, 'Tec. Maria João', 'Rastreio e diagnóstico mamário.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(44, 'PED-URG', 'Urgência Pediátrica', 'Bloco C', 0, 'Dra. Teresa Almeida', 'Triagem e atendimento de urgência infantil.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(45, 'PED-UCIN', 'Unidade De Cuidados Intensivos Neonatais', 'Bloco C', 1, 'Dr. Manuel Neves', 'Incubadoras de alta tecnologia e berços aquecidos.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(46, 'PED-UCIP', 'Unidade De Cuidados Intensivos Pediátricos', 'Bloco C', 1, 'Dra. Filipa Borges', 'Monitorização intensiva para crianças e jovens.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(47, 'PED-ENF', 'Enfermaria De Internamento Pediátrico', 'Bloco C', 2, 'Enf. Patrícia Silva', 'Quartos de internamento decorados e infantis.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(48, 'OBS-BLPA', 'Bloco De Partos - Salas De Parto', 'Bloco C', 1, 'Dra. Catarina Ramos', 'Salas de partos e monitorização cardiotocográfica (CTG).', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(49, 'OBS-RECO', 'Recobro De Obstetrícia', 'Bloco C', 1, 'Enf. Chefe Sónia Dias', 'Cuidados imediatos pós-parto.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(50, 'OBS-PUER', 'Enfermaria De Puerpério e Maternidade', 'Bloco C', 3, 'Enf. Chefe Rosa Lima', 'Alojamento conjunto mãe e recém-nascido.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(51, 'OBS-GINE', 'Internamento e Consultas De Ginecologia', 'Bloco C', 3, 'Dr. Jorge Moutinho', 'Área cirúrgica e médica de ginecologia.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(52, 'CEX-GAB01', 'Consulta Externa - Gabinete 01 Medicina', 'Bloco D', 0, 'Dr. Alberto Cunha', 'Equipamento médico básico de diagnóstico de rotina.', 1, '2026-06-17 09:51:32', '2026-06-17 16:45:46'),
	(53, 'CEX-GAB02', 'Consulta Externa - Gabinete 02 Oftalmologia', 'Bloco D', 0, 'Dr. Vítor Freire', 'Cadeiras oftalmológicas, refratómetros e lâmpadas de fenda.', 1, '2026-06-17 09:51:32', '2026-06-17 16:45:59'),
	(54, 'CEX-GAB03', 'Consulta Externa - Gabinete 03 Otorrino', 'Bloco D', 0, 'Dra. Luísa Faria Dias', 'Cabine audiométrica e torres de endoscopia ORL.', 0, '2026-06-17 09:51:32', '2026-06-18 12:31:35'),
	(55, 'CEX-GAB04', 'Consulta Externa - Gabinete 04 Dermatologia', 'Bloco D', 1, 'Dra. Rita Saraiva', 'Equipamento de dermatoscopia digital.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(56, 'CEX-GAB05', 'Consulta Externa - Gabinete 05 Urologia', 'Bloco D', 1, 'Dr. Paulo Ferreira', 'Ecógrafo urológico e fluxómetros.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(57, 'CEX-ENF', 'Sala De Tratamentos De Enfermagem Central', 'Bloco D', 0, 'Enf. Gonçalo Neto', 'Pensos, pequenas suturas e pensos complexos.', 1, '2026-06-17 09:51:32', '2026-06-17 16:45:38'),
	(58, 'CEX-MFIZ', 'Serviço De Medicina Física e Reabilitação', 'Bloco D', -1, 'Dr. Ricardo Simões', 'Ginásio terapêutico, equipamentos de eletroterapia e ultrassons.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(59, 'LAB-ANCL', 'Laboratório De Análises Clínicas Central', 'Bloco E', 1, 'Dra. Cláudia Henriques', 'Equipamentos automáticos de bioquímica e hematologia.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(60, 'LAB-ANPA', 'Laboratório De Anatomia Patológica', 'Bloco E', 1, 'Dr. Hugo Teixeira', 'Processadores de tecidos e microscópios óticos de precisão.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(61, 'LAB-MICR', 'Laboratório De Microbiologia', 'Bloco E', 2, 'Dra. Inês Castro', 'Incubadoras de culturas e câmaras de fluxo laminar.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(62, 'FAR-CENT', 'Farmácia Hospitalar Central', 'Bloco E', 0, 'Dra. Leonor Guedes', 'Sistemas automatizados de dispensa e frigoríficos de vacinas.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(63, 'FAR-PREP', 'Sala De Preparação De Citostáticos', 'Bloco E', 0, 'Dr. Nuno Abreu', 'Preparação de quimioterapia em ambiente controlado.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(64, 'LOG-STER', 'Central De Esterilização (CME)', 'Bloco E', -1, 'Enf. Chefe Anabela Cruz', 'Autoclaves de grande volume e lavadoras desinfetadoras.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(65, 'LOG-ALMC', 'Armazém Central De Dispositivos e Consumíveis', 'Bloco E', -1, 'Sr. António Machado', 'Logística e stock de materiais médicos descartáveis.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(66, 'ONC-HOD1', 'Hospital De Dia Oncológico', 'Bloco F', 0, 'Dra. Amélia Fonseca', 'Cadeirões de quimioterapia com bombas de infusão dedicadas.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(67, 'ONC-ENFO', 'Enfermaria De Internamento De Oncologia', 'Bloco F', 1, 'Enf. Chefe Mário Coelho', 'Quartos de isolamento protetor para imunodeficientes.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(68, 'ENG-BIOM', 'Serviço De Engenharia Biomédica / Oficina', 'Bloco F', -1, 'Eng. Inês Moreira', 'Oficina técnica, analisadores de segurança e simuladores.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(69, 'SER-SAD0', 'Serviço De Apoio Ao Domicílio', 'Bloco F', 0, 'Enf. Susana Tavares', 'Gestão de ventiladores e concentradores para o exterior.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32'),
	(70, 'ADM-GMAN', 'Direção e Administração Do Hospital', 'Bloco F', 2, 'Dr. Francisco Melo', 'Gabinetes administrativos da comissão executiva.', 1, '2026-06-17 09:51:32', '2026-06-17 16:42:24'),
	(71, 'SER-MORG', 'Morgue Hospitalar e Anatomia', 'Bloco F', -2, 'Dr. Xavier Cardoso', 'Câmaras frigoríficas e mesas de autópsia técnicas.', 0, '2026-06-17 09:51:32', '2026-06-17 09:51:32');

-- A despejar estrutura para tabela db1241841.utilizadores
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1241841.utilizadores: ~0 rows (aproximadamente)
INSERT INTO `utilizadores` (`id`, `username`, `password`) VALUES
	(1, 'admin@isep.pt', '$2y$10$vTA9GfDf6MkYoCP37bYenupg0bMFdTI99S8maZJd/KPullEJbEsFG');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
