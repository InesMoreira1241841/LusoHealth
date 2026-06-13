-- ------------------------------ LusoHealth - Script SQL ------------------------------


-- ------------------------------ TABELA: UTILIZADORES ------------------------------
CREATE TABLE `utilizadores` (

  -- - guarda os dados de autenticação dos utilizadores do sistema
  -- - tabela responsável pelo controlo de acesso à área privada
  -- - inclui: username; password encriptada e nome de utilizador

  -- indicador único do utilizador
  -- NOT NULL: campo obrigatório
  -- AUTO_INCREMENT: o valor aumenta automaticamente
  `id` int NOT NULL AUTO_INCREMENT,

  -- nome de utilizador usado no login
  -- NOT NULL: campo obrigatório
  `username` varchar(50) NOT NULL,

  -- palavra-passe do utilizador
  -- NOT NULL: campo obrigatório
  -- não é guardada em texto simples
  -- varchar(255): tamanho suficiente para armazenar hashes gerados pelo bcrypt
  -- ex hashes: $2y$10$wP3e9VhM8Y7xK...
  `password` varchar(255) NOT NULL COMMENT 'hash bcrypt',

  -- nome completo do utilizador
  -- NOT NULL: campo obrigatório
  `nome` varchar(100) NOT NULL,

  -- data e hora de criação do utilizador
  -- NOT NULL: campo obrigatório
  -- DEFAULT (now()): a base de dados insere automaticamente a data e hora atual
  `criado_em` datetime NOT NULL DEFAULT (now()),

  -- constraints (regras da tabela)

  -- garante que cada utilizador possua um ID único
  CONSTRAINT PK_utilizadores PRIMARY KEY (`id`),

  -- UNIQUE: impede usernames repetidos
  CONSTRAINT UQ_utilizadores_username UNIQUE (`username`)
) ENGINE=InnoDB;


-- ------------------------------ TABELA: CATEGORIAS ------------------------------
CREATE TABLE `categorias` (

  -- - as categorias dos equipamentos médicos
  -- - permite classificar equipamentos por tipo/função

  -- indicador único do utilizador
  -- NOT NULL: campo obrigatório
  -- AUTO_INCREMENT: o valor aumenta automaticamente
  `id` int NOT NULL AUTO_INCREMENT,

  -- nome da categoria
  `nome` varchar(100) NOT NULL COMMENT 'Ex: Monitorização, Suporte de vida',

  -- constraints (regras da tabela)

  -- garante que cada categoria possua um ID único
  CONSTRAINT PK_categorias PRIMARY KEY (`id`),

  -- UNIQUE: impede categorias repetidos
  CONSTRAINT UQ_categorias_nome UNIQUE (`nome`)
) ENGINE=InnoDB;


-- ------------------------------ TABELA: LOCALIZACOES ------------------------------
CREATE TABLE `localizacoes` (

  -- - guarda as localizações físicas dos equipamentos no hospital
  -- - permite saber onde um equipamento está instalado

  -- identificador único da localização
  `id` int NOT NULL AUTO_INCREMENT,

  -- código interno da localização
  `codigo` varchar(20) NOT NULL COMMENT 'Ex: SRV-UCI',

  -- nome do serviço ou unidade
  `nome` varchar(100) NOT NULL COMMENT 'Ex: Unidade de Cuidados Intensivos',

  -- edifício ou bloco hospitalar
  `edificio` varchar(50) NOT NULL COMMENT 'Ex: Bloco B - Cirúrgico',

  -- piso onde se encontra a localização
  -- valor por defeito = 0
  `piso` tinyint NOT NULL DEFAULT 0,

  -- nome do responsável pelo espaço
  -- campo opcional
  `responsavel` varchar(100),

  -- campo livre para observações adicionais
  `observacoes` text,

  -- data de criação do registo
  `criado_em` datetime NOT NULL DEFAULT (now()),

  -- data da última atualização
  `atualizado_em` datetime,
  
  -- constraints (regras da tabela)

  -- garante que cada localização possua um ID único
  CONSTRAINT PK_localizacoes PRIMARY KEY (`id`),

  -- UNIQUE: impede categorias repetidos
  CONSTRAINT UQ_localizacoes_codigo UNIQUE (`codigo`)
) ENGINE=InnoDB;


-- ------------------------------ TABELA: EQUIPAMENTOS ------------------------------
CREATE TABLE `equipamentos` (

  -- - tabela principal do sistema
  -- - guarda todas as informações sobre os equipamentos médicos

  -- identificador único do equipamento
  `id` int NOT NULL AUTO_INCREMENT,

  -- código interno de inventário
  `codigo_inventario` varchar(30)  NOT NULL COMMENT 'Ex: INV-0091',

  -- nome / designação do equipamento
  `designacao` varchar(150) NOT NULL,

  -- campo categoria_id guarda um número inteiro (int) correspondente ao ID de uma categoria
  `categoria_id` int,

  -- marca do equipamento
  `marca` varchar(100) NOT NULL,

  -- modelo do equipamento
  `modelo` varchar(100) NOT NULL,

  -- número de série único do fabricante
  `num_serie` varchar(100) NOT NULL,

  -- nome do fabricante
  `fabricante` varchar(100),

  -- ano de fabrico do equipamento
  `ano_fabrico` year,

  -- data em que o equipamento foi adquirido
  `data_aquisicao` date,

  -- custo de aquisição do equipamento
  -- 10: nº total de dígitos permitidos
  -- 2: casas decimais após a vírgula, ponto
  `custo_aquisicao` decimal(10,2),

  -- tipo de entrada no hospital
  `tipo_entrada` enum('Compra','Doação','Aluguer','Empréstimo') NOT NULL,

  -- estado atual do equipamento
  `estado` enum('Ativo','Em manutenção','Inativo','Em calibração','Em quarentena','Abatido') NOT NULL,

  -- grau de criticidade clínica do equipamento
  `criticidade` enum('Baixa','Média','Alta','Suporte de vida') NOT NULL,

  -- campo localizacao_id guarda um número inteiro (int) correspondente ao ID de uma localização
  `localizacao_id` int,

  -- campo funciona como um estado simples (tipo “sim/não”)
  -- tinyint: nº inteiro pequeno
    -- 0: falso / não
    -- 1: verdadeiro / sim
  `arquivado` tinyint NOT NULL DEFAULT 0 COMMENT '0 = ativo, 1 = arquivado',

  -- observações adicionais
  `observacoes` text,

  -- data de criação do registo
  `criado_em` datetime NOT NULL DEFAULT (now()),

  -- data da última atualização
  `atualizado_em` datetime,
 
  -- constraints (regras da tabela)

  -- garante que cada equipamento possua um ID único
  CONSTRAINT PK_equipamentos PRIMARY KEY (`id`),

  -- UNIQUE: impede categorias repetidos
  CONSTRAINT UQ_equipamentos_codigo UNIQUE (`codigo_inventario`),

  -- o campo categoria_id em equipamentos está ligado ao id da tabela categorias
  -- ON DELETE SET NULL: Se a categoria for apagada, os equipamentos que estavam ligados a ela ficam com categoria_id = NULL
  CONSTRAINT FK_equipamentos_categoria FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,

  -- o campo localizacoes_id em equipamentos está ligado ao id da tabela localizacoes
  -- ON DELETE SET NULL: Se a categoria for apagada, os equipamentos que estavam ligados a ela ficam com categoria_id = NULL
  CONSTRAINT FK_equipamentos_localizacao FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`) ON DELETE SET NULL,

  CONSTRAINT UQ_equipamentos_num_serie UNIQUE (`num_serie`),

  -- a BD só aceita valores de custo_aquisição >= a 0
  CONSTRAINT CK_equipamentos_custo CHECK (`custo_aquisicao` >= 0)
) ENGINE=InnoDB;


-- ------------------------------ TABELA: FORNECEDORES ------------------------------
CREATE TABLE `fornecedores` (

  -- - guarda os dados dos fornecedores associados aos equipamentos
  -- - pode incluir fabricantes, distribuidores, assistência técnica ou fornecedores de consumíveis

  -- identificador único do fornecedor
  `id` int NOT NULL AUTO_INCREMENT,

  -- nome da empresa fornecedora
  `nome` varchar(150) NOT NULL,

  -- número de Identificação Fiscal (NIF)
  `nif` varchar(20) NOT NULL,

  -- tipo de fornecedor
  `tipo` enum('Fabricante','Distribuidor','Assistência Técnica','Consumíveis') NOT NULL,

  -- número de telefone do fornecedor
  `telefone` varchar(30),

  -- endereço de email do fornecedor
  `email` varchar(100),

  -- morada física da empresa
  `morada` varchar(200),

  -- website oficial do fornecedor
  `website` varchar(150),

  -- nome do técnico responsável ou contacto principal
  `tecnico_nome` varchar(100),

  -- contacto telefónico do técnico responsável
  `tecnico_telefone` varchar(30),

  -- observações adicionais
  `observacoes` text,

  -- data de criação do registo
  `criado_em` datetime NOT NULL DEFAULT (now()),

  -- data da última atualização do registo
  `atualizado_em` datetime,
 
  -- constraints (regras da tabela)

  -- garante que cada fornecedor possua um ID único
  CONSTRAINT PK_fornecedores PRIMARY KEY (`id`),

  -- UNIQUE: impede categorias repetidos
  CONSTRAINT UQ_fornecedores_nif UNIQUE (`nif`)
) ENGINE=InnoDB;


-- ------------------------------ TABELA: EQUIPAMENTO_FORNECEDOR ------------------------------
CREATE TABLE `equipamento_fornecedor` (

  -- - tabela intermédia responsável por ligar equipamentos aos seus fornecedores
  -- - esta tabela existe porque um equipamento pode ter vários fornecedores e um fornecedor pode estar associado a vários equipamentos
  
  -- - exemplo:
    -- - Equipamento → Ventilador
    -- - Fabricante → Philips
    -- - Assistência técnica → Siemens

  -- campo equipamento_id guarda um número inteiro (int) correspondente ao ID de um equipamento
  `equipamento_id` int NOT NULL,

  -- campo dornecedor_id guarda um número inteiro (int) correspondente ao ID de um fornecedor
  `fornecedor_id` int NOT NULL,

  -- tipo de relação entre equipamento e fornecedor 
  `tipo_relacao` enum('Fabricante', 'Distribuidor', 'Assistência técnica') NOT NULL,
 
  -- constraints (regras da tabela)

  -- a identificação única desta tabela é feita pela combinação de dois campos: equipamento_id + fornecedor_id
  -- PRIMARY KEY: identifica cada linha de forma única; não pode repetir valores; não pode ser NULL
  -- PK: impede duplicados
  CONSTRAINT PK_equipamento_fornecedor PRIMARY KEY (`equipamento_id`, `fornecedor_id`),

  -- o campo equipamento_id nesta tabela está ligado ao id da tabela equipamentos
  -- Foreign key: garante:
    -- só se pode usar valores de equipamento_id que existam na tabela equipamentos
    -- mantém a integridade dos dados
  -- ON DELETE CASCADE: Se um equipamento for apagado, todos os registos ligados a ele nesta tabela também são apagados automaticamente
  CONSTRAINT FK_equip_fornec_equipamento FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,

  -- o campo fornecedor_id nesta tabela está ligado ao id da tabela fornecedores
  -- Foreign key: garante:
    -- só se pode usar valores de fornecedor_id que existam na tabela fornecedores
    -- mantém a integridade dos dados
  -- ON DELETE CASCADE: Se um fornecedor for apagado, todos os registos ligados a ele nesta tabela também são apagados automaticamente
  CONSTRAINT FK_equip_fornec_fornecedor FOREIGN KEY (`fornecedor_id`)  REFERENCES `fornecedores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ------------------------------ TABELA: DOCUMENTOS ------------------------------
CREATE TABLE `documentos` (

  -- - guarda documentos associados aos equipamentos

  -- identificador único do documento
  `id` int NOT NULL AUTO_INCREMENT,

  -- equipamento ao qual o documento pertence
  `equipamento_id` int NOT NULL,

  -- fornecedor relacionado com o documento
  -- campo opcional
  `fornecedor_id` int,

  -- tipo de documento
  `tipo` varchar(50) NOT NULL COMMENT 'Manual do Utilizador / Manual de Serviço / Certificado de Calibração / etc.',

  -- nome do ficheiro ou localização do ficheiro
  `nome_ficheiro_caminho` varchar(255) NOT NULL,

  -- nome visível do documento
  `nome_documento` varchar(255) NOT NULL,

  -- data do documento
  `data_documento` date,

  -- data de validade do documento
  `data_validade` date,

  -- notas adicionais sobre o documento
  `notas` text,

  -- data de criação do registo
  `criado_em` datetime NOT NULL DEFAULT (now()),
 
  -- constraints (regras da tabela)

  -- garante que cada documento possua um ID único
  CONSTRAINT PK_documentos PRIMARY KEY (`id`),

  -- o campo equipamento_id nesta tabela está ligado ao id da tabela equipamentos
  -- Foreign key: garante:
    -- só se pode usar valores de equipamento_id que existam na tabela equipamentos
    -- mantém a integridade dos dados
  -- ON DELETE CASCADE: Se um equipamento for apagado, todos os registos ligados a ele nesta tabela também são apagados automaticamente
  CONSTRAINT FK_documentos_equipamento FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,

  -- o campo fornecedor_id nesta tabela está ligado ao id da tabela fornecedores
  -- Foreign key: garante:
    -- só se pode usar valores de fornecedor_id que existam na tabela fornecedores
    -- mantém a integridade dos dados
  -- ON DELETE SET NULL: Se o fornecedor for apagada, o documento mantém-se, mas perde a ligação ao fornecedor
  CONSTRAINT FK_documentos_fornecedor FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;


-- ------------------------------ TABELA: GARANTIAS ------------------------------
CREATE TABLE `garantias` (
  
  -- - guarda contratos e garantias

  -- identificador único da garantia
  `id` int NOT NULL AUTO_INCREMENT,

  -- identificador único do contrato (ex: CTR-DRG-0001)
  `num_contrato` varchar(50) NOT NULL COMMENT 'Ex: CTR-DRG-0001',

  -- equipamento associado à garantia
  `equipamento_id` int NOT NULL,

  -- fornecedor responsável pela garantia
  -- fornecedor opcional - pode ser null
  `fornecedor_id` int COMMENT 'opcional',

  -- nome da entidade responsável, utilizado quando não existe fornecedor registado
  `entidade_responsavel` varchar(150) COMMENT 'quando não é um fornecedor registado',

  -- tipo de garantia/contrato
  `tipo` enum('Garantia de Fábrica', 'Contrato de Manutenção', 'Outro') NOT NULL,

  -- define se existe contrato de manutenção
  -- 0 = Não | 1 = Sim
  `tem_contrato_manutencao` tinyint NOT NULL DEFAULT 0 COMMENT '0 = Não, 1 = Sim',

  -- data de início do contrato
  `data_inicio` date NOT NULL,

  -- data de fim do contrato
  `data_fim` date NOT NULL,

  -- frequência das manutenções
  `periodicidade` varchar(50),

  -- termos do contrato (ex: "resposta em 4h, peças incluídas")
  `clausulas` text,

  -- observações adicionais
  `observacoes` text,

  -- data de criação do registo
  `criado_em` datetime NOT NULL DEFAULT (now()),

  -- data da última atualização
  `atualizado_em` datetime,
   
  -- constraints (regras da tabela)

  -- garante que cada garantia possua um ID único
  CONSTRAINT PK_garantias PRIMARY KEY (`id`),

  -- garante que o num_contrato não se repete
  CONSTRAINT UQ_garantias_num_contrato UNIQUE (`num_contrato`),

  -- o campo equipamento_id da tabela garantias está ligado ao id da tabela equipamentos
  -- FOREIGN KEY: só podes associar garantias a equipamentos que existam
  -- ON DELETE CASCADE: se um equipamento for apagado, as garantias associadas a esse equipamento também são apagadas automaticamente
  CONSTRAINT FK_garantias_equipamento FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`) ON DELETE CASCADE,

  -- o campo fornecedor_id na tabela garantias está ligado ao id da tabela fornecedores
  -- FOREIGN KEY: só podes usar fornecedores que existam
  -- ON DELETE SET NULL: se o fornecedor for apagado, a garantia continua a existir, mas o fornecedor_id fica vazio (NULL)
  CONSTRAINT FK_garantias_fornecedor FOREIGN KEY (`fornecedor_id`)  REFERENCES `fornecedores` (`id`) ON DELETE SET NULL,

  -- verifica se a data-fim é mais recente do que a data_inicio
  CONSTRAINT CK_garantias_datas CHECK (`data_fim` > `data_inicio`)
) ENGINE=InnoDB;


-- ------------------------------ TABELA: CONTEUDOS_PUBLICOS ------------------------------
CREATE TABLE `conteudos_publicos` (
  
  -- - guarda conteúdos dinâmicos do website público
  -- - permite alterar textos do website sem necessidade de modificar código-fonte

  -- identificador único do conteúdo
  `id` int NOT NULL AUTO_INCREMENT,

  -- chave identificadora do conteúdo
  `chave` varchar(50) NOT NULL COMMENT 'Ex: nome_hospital, telefone, email, texto_home',

  -- valor associado à chave
  -- pode guardar texto simples ou contéudo longo
  `valor` text NOT NULL,

  -- data da última atualização do conteúdo
  `atualizado_em` datetime,
 
  -- constraints (regras da tabela)

  -- garante que cada conteudo publico possua um ID único
  CONSTRAINT PK_conteudos_publicos PRIMARY KEY (`id`),

  -- UNIQUE: impede usernames repetidos
  CONSTRAINT UQ_conteudos_publicos_chave UNIQUE (`chave`)
) ENGINE=InnoDB;