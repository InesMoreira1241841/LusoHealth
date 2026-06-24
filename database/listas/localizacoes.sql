-- NOTA: Este código foi gerado com recurso a IA a fim de facilitar o
--      processo de inserção de localizacoes à base de dados

-- -------------------- CÓDIGO GERADO PELA IA --------------------


INSERT INTO `localizacoes` (
    `codigo`, `nome`, `edificio`, `piso`, `responsavel`, `observacoes`, `criado_em`
) VALUES

/* --- BLOCO A: SERVIÇOS CLÍNICOS CENTRAIS --- */
('HOS-PUG', 'Serviço De Urgência Geral', 'Bloco A', 0, 'Dra. Marta Antunes', 'Área de triagem e reanimação rápida.', NOW()),
('HOS-UCIP', 'Unidade De Cuidados Intensivos Polivalente', 'Bloco A', 1, 'Dr. Carlos Seabra', 'Equipamentos de suporte de vida permanente.', NOW()),
('HOS-CCOR', 'Unidade De Cuidados Intensivos Coronários', 'Bloco A', 1, 'Dra. Sílvia Ribeiro', 'Monitores cardíacos e telemetria avançada.', NOW()),
('HOS-MED1', 'Enfermaria De Medicina Interna A', 'Bloco A', 2, 'Enf. Chefe João Lucas', 'Camas de internamento geral.', NOW()),
('HOS-MED2', 'Enfermaria De Medicina Interna B', 'Bloco A', 3, 'Enf. Chefe Clara Mendes', 'Ala norte de internamento.', NOW()),
('HOS-CARD', 'Serviço De Cardiologia e Diagnóstico', 'Bloco A', 2, 'Dr. Ricardo Veloso', 'Salas de eletrocardiografia e ecocardiogramas.', NOW()),
('HOS-NEUR', 'Unidade De AVC e Neurologia', 'Bloco A', 3, 'Dra. Beatriz Braga', 'Vigilância neurológica contínua.', NOW()),
('HOS-PNEU', 'Serviço De Pneumologia e Função Respiratória', 'Bloco A', 4, 'Dr. Artur Fonseca', 'Laboratório de exames respiratórios.', NOW()),
('HOS-GAST', 'Unidade De Gastrenterologia e Endoscopia', 'Bloco A', 4, 'Dra. Joana Coimbra', 'Salas de exames endoscópicos invasivos.', NOW()),
('HOS-HEMO', 'Unidade De Hemodiálise Central', 'Bloco A', 0, 'Eng. Miguel Cruz', 'Reclinares com postos de diálise dedicados.', NOW()),

/* --- BLOCO B: BLOCO CIRÚRGICO E IMAGEM --- */
('CIR-CBL1', 'Bloco Operatório - Sala 1 Geral', 'Bloco B', 1, 'Dra. Helena Matos', 'Sala equipada para cirurgia geral.', NOW()),
('CIR-CBL2', 'Bloco Operatório - Sala 2 Ortopedia', 'Bloco B', 1, 'Dr. Nuno Quintas', 'Foco em cirurgia ortopédica e trauma.', NOW()),
('CIR-CBL3', 'Bloco Operatório - Sala 3 Neurocirurgia', 'Bloco B', 1, 'Dr. Fernando Pais', 'Equipamento de microscopia e neuronavegação.', NOW()),
('CIR-UCPA', 'Unidade De Cuidados Pós-Anestésicos (Recobro)', 'Bloco B', 1, 'Enf. Tiago Brandão', 'Monitorização de doentes em pós-operatório imediato.', NOW()),
('CIR-AMBU', 'Unidade De Cirurgia De Ambulatório', 'Bloco B', 0, 'Dra. Mafalda Costa', 'Procedimentos cirúrgicos de dia.', NOW()),
('CIR-CORT', 'Enfermaria De Cirurgia Ortopédica', 'Bloco B', 2, 'Enf. Chefe Rui Santos', 'Internamento pós-cirúrgico de ortopedia.', NOW()),
('CIR-CGEN', 'Enfermaria De Cirurgia Geral', 'Bloco B', 3, 'Enf. Chefe Laura Sousa', 'Internamento pós-cirúrgico geral.', NOW()),
('IMG-RX1', 'Radiologia Convencional - Sala 1', 'Bloco B', -1, 'Tec. Superior José Vila', 'Equipamento fixo de Raios-X digital.', NOW()),
('IMG-TAC1', 'Sala De Tomografia Computorizada (TAC)', 'Bloco B', -1, 'Dra. Isabel Rocha', 'Equipamento de TAC de alta resolução.', NOW()),
('IMG-RMN1', 'Sala De Ressonância Magnética', 'Bloco B', -1, 'Eng. Pedro Moreira', 'Sala blindada (Gaiola de Faraday).', NOW()),
('IMG-ECO1', 'Sala De Ecografia Geral e Vascular', 'Bloco B', 0, 'Dra. Sofia Nogueira', 'Ecógrafos portáteis e fixos.', NOW()),
('IMG-MAM0', 'Gabinete De Mamografia Digital', 'Bloco B', 0, 'Tec. Maria João', 'Rastreio e diagnóstico mamário.', NOW()),

/* --- BLOCO C: SAÚDE DA MULHER E DA CRIANÇA --- */
('PED-URG', 'Urgência Pediátrica', 'Bloco C', 0, 'Dra. Teresa Almeida', 'Triagem e atendimento de urgência infantil.', NOW()),
('PED-UCIN', 'Unidade De Cuidados Intensivos Neonatais', 'Bloco C', 1, 'Dr. Manuel Neves', 'Incubadoras de alta tecnologia e berços aquecidos.', NOW()),
('PED-UCIP', 'Unidade De Cuidados Intensivos Pediátricos', 'Bloco C', 1, 'Dra. Filipa Borges', 'Monitorização intensiva para crianças e jovens.', NOW()),
('PED-ENF', 'Enfermaria De Internamento Pediátrico', 'Bloco C', 2, 'Enf. Patrícia Silva', 'Quartos de internamento decorados e infantis.', NOW()),
('OBS-BLPA', 'Bloco De Partos - Salas De Parto', 'Bloco C', 1, 'Dra. Catarina Ramos', 'Salas de partos e monitorização cardiotocográfica (CTG).', NOW()),
('OBS-RECO', 'Recobro De Obstetrícia', 'Bloco C', 1, 'Enf. Chefe Sónia Dias', 'Cuidados imediatos pós-parto.', NOW()),
('OBS-PUER', 'Enfermaria De Puerpério e Maternidade', 'Bloco C', 3, 'Enf. Chefe Rosa Lima', 'Alojamento conjunto mãe e recém-nascido.', NOW()),
('OBS-GINE', 'Internamento e Consultas De Ginecologia', 'Bloco C', 3, 'Dr. Jorge Moutinho', 'Área cirúrgica e médica de ginecologia.', NOW()),

/* --- BLOCO D: CONSULTAS EXTERNAS E AMBULATÓRIO --- */
('CEX-GAB01', 'Consulta Externa - Gabinete 01 Medicina', 'Bloco D', 0, 'Dr. Alberto Cunha', 'Equipamento médico básico de diagnóstico de rotina.', NOW()),
('CEX-GAB02', 'Consulta Externa - Gabinete 02 Oftalmologia', 'Bloco D', 0, 'Dr. Vítor Freire', 'Cadeiras oftalmológicas, refratómetros e lâmpadas de fenda.', NOW()),
('CEX-GAB03', 'Consulta Externa - Gabinete 03 Otorrino', 'Bloco D', 0, 'Dra. Luísa Faria', 'Cabine audiométrica e torres de endoscopia ORL.', NOW()),
('CEX-GAB04', 'Consulta Externa - Gabinete 04 Dermatologia', 'Bloco D', 1, 'Dra. Rita Saraiva', 'Equipamento de dermatoscopia digital.', NOW()),
('CEX-GAB05', 'Consulta Externa - Gabinete 05 Urologia', 'Bloco D', 1, 'Dr. Paulo Ferreira', 'Ecógrafo urológico e fluxómetros.', NOW()),
('CEX-ENF', 'Sala De Tratamentos De Enfermagem Central', 'Bloco D', 0, 'Enf. Gonçalo Neto', 'Pensos, pequenas suturas e pensos complexos.', NOW()),
('CEX-MFIZ', 'Serviço De Medicina Física e Reabilitação', 'Bloco D', -1, 'Dr. Ricardo Simões', 'Ginásio terapêutico, equipamentos de eletroterapia e ultrassons.', NOW()),

/* --- BLOCO E: LABORATÓRIOS, FARMÁCIA E LOGÍSTICA --- */
('LAB-ANCL', 'Laboratório De Análises Clínicas Central', 'Bloco E', 1, 'Dra. Cláudia Henriques', 'Equipamentos automáticos de bioquímica e hematologia.', NOW()),
('LAB-ANPA', 'Laboratório De Anatomia Patológica', 'Bloco E', 1, 'Dr. Hugo Teixeira', 'Processadores de tecidos e microscópios óticos de precisão.', NOW()),
('LAB-MICR', 'Laboratório De Microbiologia', 'Bloco E', 2, 'Dra. Inês Castro', 'Incubadoras de culturas e câmaras de fluxo laminar.', NOW()),
('FAR-CENT', 'Farmácia Hospitalar Central', 'Bloco E', 0, 'Dra. Leonor Guedes', 'Sistemas automatizados de dispensa e frigoríficos de vacinas.', NOW()),
('FAR-PREP', 'Sala De Preparação De Citostáticos', 'Bloco E', 0, 'Dr. Nuno Abreu', 'Preparação de quimioterapia em ambiente controlado.', NOW()),
('LOG-STER', 'Central De Esterilização (CME)', 'Bloco E', -1, 'Enf. Chefe Anabela Cruz', 'Autoclaves de grande volume e lavadoras desinfetadoras.', NOW()),
('LOG-ALMC', 'Armazém Central De Dispositivos e Consumíveis', 'Bloco E', -1, 'Sr. António Machado', 'Logística e stock de materiais médicos descartáveis.', NOW()),

/* --- BLOCO F: ONCOLOGIA E APOIO TÉCNICO --- */
('ONC-HOD1', 'Hospital De Dia Oncológico', 'Bloco F', 0, 'Dra. Amélia Fonseca', 'Cadeirões de quimioterapia com bombas de infusão dedicadas.', NOW()),
('ONC-ENFO', 'Enfermaria De Internamento De Oncologia', 'Bloco F', 1, 'Enf. Chefe Mário Coelho', 'Quartos de isolamento protetor para imunodeficientes.', NOW()),
('ENG-BIOM', 'Serviço De Engenharia Biomédica / Oficina', 'Bloco F', -1, 'Eng. Inês Moreira', 'Oficina técnica, analisadores de segurança e simuladores.', NOW()),
('SER-SAD0', 'Serviço De Apoio Ao Domicílio', 'Bloco F', 0, 'Enf. Susana Tavares', 'Gestão de ventiladores e concentradores para o exterior.', NOW()),
('ADM-GMAN', 'Direção e Administração Do Hospital', 'Bloco F', 2, 'Dr. Francisco Melo', 'Gabinetes administrativos da comissão executiva.', NOW()),
('SER-MORG', 'Morgue Hospitalar e Anatomia', 'Bloco F', -2, 'Dr. Xavier Cardoso', 'Câmaras frigoríficas e mesas de autópsia técnicas.', NOW());
