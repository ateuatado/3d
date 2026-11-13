# Tarefas: Marketplace de modelos 3D

**Entrada**: documentos de projeto em \`specs/001-3d-marketplace/\`

**Documentos utilizados**: \`plan.md\`, \`spec.md\`, \`research.md\`,
\`data-model.md\`, \`contracts/\` e \`quickstart.md\`.

**Estratégia**: entregar primeiro um catálogo público completo; depois compra e
download; em seguida administração delegada e encomendas; por fim endurecimento
e validação transversal.

## Fase 1: Preparação

- [ ] T001 Confirmar dependências PHP e adicionar clientes de OAuth Google e Mercado Pago em \`composer.json\`
- [x] T002 [P] Criar configurações tipadas em \`app/Config/Google.php\`, \`app/Config/MercadoPago.php\` e \`app/Config/Files.php\`
- [x] T003 [P] Criar a estrutura de diretórios de controllers, serviços, views, testes e armazenamento privado conforme \`plan.md\`
- [ ] T004 [P] Criar variáveis documentadas e seguras no arquivo \`env\`, mantendo segredos fora de \`.env\` versionado
- [x] T005 [P] Definir estilos e scripts-base separados em \`public/assets/css/app.css\` e \`public/assets/js/app.js\`

## Fase 2: Base obrigatória

**Objetivo**: preparar banco, autenticação, permissões, auditoria, upload privado
e convenções de resposta antes das histórias de usuário.

- [x] T006 Criar migrações para modelos, arquivos, pedidos, itens, pagamentos, autorizações, solicitações e eventos em \`app/Database/Migrations/\`
- [x] T007 Criar modelos e regras de validação das entidades em \`app/Models/\`
- [x] T008 [P] Configurar grupos e permissões administrativas em \`app/Config/AuthGroups.php\` e \`app/Config/Auth.php\`
- [ ] T009 [P] Criar filtros de autenticação, autorização e proteção CSRF em \`app/Config/Filters.php\` e \`app/Filters/\`
- [x] T010 [P] Implementar registro de auditoria em \`app/Services/Audit/AuditService.php\` e \`app/Models/AuditEventModel.php\`
- [x] T011 Implementar armazenamento privado com nomes aleatórios, validação de tipo/tamanho e caminhos controlados em \`app/Services/Storage/PrivateFileStorage.php\`
- [ ] T012 [P] Criar tratamento seguro de exceções, logs e respostas de erro em \`app/Config/Exceptions.php\` e \`app/Services/Audit/\`
- [ ] T013 Criar dados iniciais de grupos administrativos e permissões em \`app/Database/Seeds/MarketplaceSeeder.php\`
- [x] T014 [P] Criar testes de base para permissões, upload privado e auditoria em \`tests/unit/MarketplaceFoundationTest.php\`

## Fase 3: História 1 — Catálogo e pré-visualização pública (P1) — MVP

**Objetivo**: permitir que visitantes encontrem modelos publicados, vejam a
pré-visualização e entendam o produto sem acessar o original.

**Teste independente**: publicar um modelo com GLB/GLTF e arquivo comercial,
abrir a página anonimamente, pesquisar pelo catálogo e confirmar que o original
não está acessível por URL pública.

- [ ] T015 [P] [US1] Criar regras de publicação, slug, preço e campos obrigatórios em \`app/Models/ModelModel.php\`
- [ ] T016 [P] [US1] Criar upload e associação de pré-visualização e arquivos comerciais em \`app/Services/Catalog/ModelFileService.php\`
- [x] T017 [US1] Implementar consulta de modelos publicados e pesquisa em \`app/Services/Catalog/CatalogService.php\`
- [x] T018 [US1] Criar rotas públicas de catálogo em \`app/Config/Routes.php\` e \`app/Controllers/Catalog/CatalogController.php\`
- [x] T019 [US1] Criar páginas de lista, busca e detalhe em \`app/Views/catalog/\`
- [x] T020 [US1] Implementar visualizador GLB/GLTF com \`model-viewer\` em \`public/assets/js/catalog-viewer.js\`
- [ ] T021 [US1] Criar metatags, dados estruturados, sitemap e regras de indexação em \`app/Controllers/Catalog/\`, \`app/Views/catalog/\` e \`public/robots.txt\`
- [ ] T022 [US1] Criar testes de catálogo, publicação, busca e não exposição do original em \`tests/Feature/Catalog/\`

## Fase 4: História 2 — Login, Pix e download protegido (P1)

**Objetivo**: permitir que um cliente autenticado pague por Pix e receba o
download somente depois da confirmação válida.

**Teste independente**: autenticar pelo Google em ambiente de teste, criar pedido,
processar uma notificação Pix aprovada e confirmar download autorizado; repetir
com pedido pendente e usuário diferente para confirmar negação.

- [ ] T023 [P] [US2] Implementar configuração e fluxo OAuth Google conforme \`contracts/google-oauth.md\` em \`app/Services/Auth/GoogleOAuthService.php\` e \`app/Controllers/Auth/GoogleController.php\`
- [ ] T024 [P] [US2] Criar telas e rotas de login, retorno, erro e logout em \`app/Views/auth/\` e \`app/Config/Routes.php\`
- [ ] T025 [US2] Implementar criação de pedido com congelamento de preço em \`app/Services/Checkout/OrderService.php\` e \`app/Models/OrderModel.php\`
- [ ] T026 [US2] Implementar criação de cobrança Pix pelo Mercado Pago em \`app/Services/Payments/MercadoPagoPaymentService.php\`
- [ ] T027 [US2] Criar tela de checkout com valor, QR Code ou Pix Copia e Cola em \`app/Controllers/Checkout/\` e \`app/Views/checkout/\`
- [ ] T028 [US2] Implementar endpoint de webhook idempotente e reconciliação em \`app/Controllers/Checkout/MercadoPagoWebhookController.php\`
- [ ] T029 [US2] Implementar atualização transacional de pagamento, pedido e itens em \`app/Services/Payments/PaymentReconciliationService.php\`
- [ ] T030 [US2] Criar geração de autorizações para arquivos da versão comprada em \`app/Services/Downloads/DownloadAuthorizationService.php\`
- [ ] T031 [US2] Implementar endpoint de download com expiração, revogação e streaming privado em \`app/Controllers/Downloads/DownloadController.php\`
- [ ] T032 [US2] Criar conta do cliente com pedidos, pagamentos e downloads em \`app/Controllers/Account/AccountController.php\` e \`app/Views/account/\`
- [ ] T033 [P] [US2] Criar testes de contrato do OAuth e webhook em \`tests/Contract/Auth/\` e \`tests/Contract/Payments/\`
- [ ] T034 [P] [US2] Criar testes de integração de pedido, idempotência e download protegido em \`tests/Integration/Checkout/\` e \`tests/Integration/Downloads/\`

## Fase 5: História 3 — Administração delegada (P1)

**Objetivo**: permitir que o proprietário mantenha o catálogo e delegue tarefas
sem conceder acesso indevido.

**Teste independente**: criar administrador de catálogo, publicar um modelo,
tentar acessar funções financeiras e revogar o acesso.

- [ ] T035 [P] [US3] Criar painel administrativo base e navegação protegida em \`app/Controllers/Admin/AdminController.php\` e \`app/Views/admin/\`
- [ ] T036 [US3] Criar formulários administrativos de modelos, versões, arquivos e publicação em \`app/Controllers/Admin/CatalogAdminController.php\` e \`app/Views/admin/catalog/\`
- [ ] T037 [US3] Implementar gerenciamento de usuários administrativos e permissões em \`app/Controllers/Admin/AdminUsersController.php\` e \`app/Views/admin/users/\`
- [ ] T038 [US3] Aplicar autorização por permissão em cada rota administrativa em \`app/Filters/AdminPermissionFilter.php\` e \`app/Config/Routes.php\`
- [ ] T039 [US3] Criar telas de pedidos, pagamentos e eventos de auditoria em \`app/Controllers/Admin/OrdersAdminController.php\` e \`app/Views/admin/orders/\`
- [ ] T040 [US3] Criar testes de permissão, revogação e auditoria em \`tests/Feature/Admin/\`

## Fase 6: História 4 — Solicitações de peças sob encomenda (P2)

**Objetivo**: receber solicitações de peças, permitir atendimento manual e
encaminhar o cliente ao WhatsApp.

**Teste independente**: enviar uma solicitação com anexo válido, revisar no
painel e usar o contato configurado do WhatsApp.

- [ ] T041 [P] [US4] Criar migração, modelo e validação de solicitações personalizadas em \`app/Database/Migrations/\` e \`app/Models/CustomRequestModel.php\`
- [ ] T042 [US4] Criar formulário público e endpoint de solicitação em \`app/Controllers/CustomRequests/CustomRequestController.php\` e \`app/Views/custom-requests/\`
- [ ] T043 [US4] Implementar anexos privados e validação de formato/tamanho em \`app/Services/CustomRequests/CustomRequestAttachmentService.php\`
- [ ] T044 [US4] Criar painel de atendimento, status e notas internas em \`app/Controllers/Admin/CustomRequestsAdminController.php\` e \`app/Views/admin/custom-requests/\`
- [ ] T045 [P] [US4] Criar ação de WhatsApp com mensagem segura e telefone configurável em \`app/Services/CustomRequests/WhatsAppLinkService.php\`
- [ ] T046 [P] [US4] Criar testes de envio, anexo, status, notas e link do WhatsApp em \`tests/Feature/CustomRequests/\`

## Fase 7: Acabamento e preocupações transversais

- [ ] T047 [P] Criar testes de segurança para CSRF, autorização, upload, enumeração de arquivos e repetição de webhook em \`tests/Security/\`
- [ ] T048 [P] Criar dados estruturados, sitemap e revisão de títulos/descrições em \`app/Views/catalog/\` e \`app/Controllers/Catalog/\`
- [ ] T049 [P] Adicionar limites, mensagens e monitoramento para uploads, checkout, webhook e downloads em \`app/Config/\` e \`app/Services/\`
- [ ] T050 Atualizar o guia operacional e variáveis de ambiente em \`README.md\`, \`env\` e \`specs/001-3d-marketplace/quickstart.md\`
- [ ] T051 Executar todos os cenários do guia de validação e registrar resultados em \`specs/001-3d-marketplace/quickstart.md\`
- [ ] T052 Revisar dependências, segredos, permissões de arquivos e logs antes do primeiro ambiente público em \`.gitignore\`, \`app/Config/\` e \`writable/\`

## Dependências e ordem de execução

### Dependências entre fases

- Fase 1 não depende de outra fase.
- Fase 2 depende da Fase 1 e bloqueia todas as histórias.
- As histórias 1, 2 e 3 são P1 e dependem da Fase 2; a História 2 usa o catálogo
  da História 1 para iniciar checkout, embora seus serviços sejam testáveis
  separadamente.
- A História 4 depende da Fase 2 e pode ser desenvolvida em paralelo às demais.
- A Fase 7 depende das histórias que forem incluídas no lançamento.

### Ordem recomendada das histórias

1. História 1: catálogo e pré-visualização — MVP de descoberta.
2. História 2: login, Pix e download — MVP comercial.
3. História 3: administração delegada — operação segura.
4. História 4: encomendas — segunda fonte de receita.

## Oportunidades de execução paralela

- T002 a T005 podem ser executadas em paralelo.
- T008 a T012 podem ser executadas em paralelo após T006 e T007.
- Na História 1, T015 e T016 podem ocorrer em paralelo; T018 e T019 dependem
  da consulta definida em T017.
- Na História 2, T023, T024 e T026 podem ocorrer em paralelo; T028 e T029
  dependem do contrato do pagamento.
- Na História 3, T035, T036 e T037 podem ser divididas entre pessoas diferentes.
- Na História 4, T041, T042 e T045 podem ocorrer em paralelo.

## Estratégia de implementação

### MVP mínimo

1. Concluir as Fases 1 e 2.
2. Concluir a História 1 e validar catálogo público e pré-visualização.
3. Concluir a História 2 e validar Pix, confirmação e download protegido.
4. Parar para demonstração e validação comercial antes de expandir o painel.

### Entrega incremental

1. Catálogo público.
2. Compra Pix e download.
3. Administração delegada.
4. Solicitações personalizadas.
5. Endurecimento de segurança e validação completa.

## Critérios de formato

Todas as tarefas usam checkbox, identificador sequencial, marcador \`[P]\` somente
quando aplicável, etiqueta da história nas fases de usuário e caminho explícito.

**Total de tarefas**: 52.

**Contagem por história**: US1 = 8; US2 = 12; US3 = 6; US4 = 6.

**Tarefas paralelizáveis**: 23.
