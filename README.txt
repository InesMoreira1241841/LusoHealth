-------------------- INVENTÁRIO HOSPITALAR DE EQUIPAMENTOS MÉDICOS --------------------


1. IDENTIFICAÇÃO DO ESTUDANTE

 - Nomes: Inês Maraia Fernandes Moreira
 - Número Mecanográfico: 1241841
 - Unidade Curricular: Sistemas de Informação e Bases de Dados Aplicados à Saúde
 - Curso: Licenciatura em Engenharia Biomédica
 - Projeto: LusoHealth
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 
2. INSTRUÇÕES DE INSTALAÇÃO E EXECUÇÃO (AMBIENTE LOCAL - LARAGON)

Passos para executar e testar este projeto localmente:

    1. Extrair o conteúdo do ficheiro ZIP para a diretoria do Laragon em:
        C:\laragon\www\

    2. Iniciar o Laragon e clicar em "Start All".

    3. Importação da Base de Dados:
        - Clicar no botão "Database" no Laragon para abrir o HeidiSQL
        - Criar uma base de dados vazia com o nome: lusohealth
        - Selecionar essa base de dados, vá a Ficheiro -> Carregar ficheiro SQL...
        - Escolher o ficheiro localizado em: /database/lusohealth.sql
        - Executar o script

    4. Acesso à Aplicação:
        - Abrir o browser e digitar o URL oficial obrigatório:
            http://127.0.0.1/sibdas/1241841/lusohealth/
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 
3. CREDENCIAIS DE ACESSO (PERFIS DE TESTE)

Conta criada previamente na base de dados:

Perfil 1: Administrador / Gestor de Inventário
- Login/Email: admin@isep.pt
- Palavra-passe: 123456
- Permissões: Acesso total (CRUD de equipamentos, fornecedores, relatórios e dashboard)
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 
4. GUIÃO DE TESTES (PRINCIPAIS FUNCIONALIDADES A TESTAR)

Validar os requisitos obrigatórios do projeto:

Teste 1: Acesso Público e Redirecionamento (Módulo Público)
    1. Digitar o URL inicial: http://127.0.0.1/sibdas/1241841/lusohealth/
    2. Confirmar que o sistema faz o encaminhamento/redirecionamento automático correto para a 
pasta pública (/public/), apresentando a página de Login/Apresentação sem expor ficheiros privados.

Teste 2: Autenticação, Sessões e Controlo de Acesso
    1. Tentar aceder diretamente a uma página restrita pelo URL (ex: `/private/home.php`). 
O sistema deve detetar a ausência de sessão e redirecionar imediatamente para o Login público.
    2. No formulário de login, insirir as credenciais de Administrador fornecidas na Secção 3.
    3. Após o sucesso, o sistema deve iniciar a sessão segura e carregar o ambiente privado.

Teste 3: Dashboard e Métricas (Requisito Técnico Base)
    1. No ambiente privado, verificar o Dashboard Principal.
    2. Confirmar que os indicadores (total de equipamentos ativos, equipamentos com criticidade alta, 
alertas de garantias ou documentação em falta) são calculados em tempo real através de queries dinâmicas 
à base de dados relacional.

Teste 4: Operações CRUD Completas e Módulos Relacionais
    1. No menu, aceder a "Equipamentos" -> "Registar Equipamento".
    2. Preencher os campos e submeter.
    3. Verificar se o equipamento é listado na tabela geral (Leitura).
    4. Clicar em "Detalhes" para aceder à vista detalhada com todas as relações do equipamento.
    5. Clicar em "Editar" para alterar um dado (ex: alterar o serviço/localização) e guardar (Alteração).
    6. clicar em "Eliminar" para testar a remoção ou arquivação segura do equipamento (Eliminação).

    *NOTA:* Repita as validações das operações CRUD nos sub-módulos integrados de: 
        - Documentação;
        - Garantias;
        - Localizações
        - Fornecedores 
        - Conteudos (edita texto do public/index.php)
        
Teste 5: Mecanismos de Validação (Cliente e Servidor)
    1. Aceder a qualquer formulário de inserção e tentar submeter dados vazios, e-mails com formato 
errado ou valores inconsistentes.
    2. Verificar que o Frontend (HTML5/JS) bloqueia os erros comuns de preenchimento.