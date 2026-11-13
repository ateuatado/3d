# Plano de implementação: Marketplace de modelos 3D

**Ramo**: \`001-3d-marketplace\` | **Data**: 2026-11-13 | **Especificação**: [spec.md](spec.md)

## Resumo

Construir o primeiro fluxo completo do marketplace: catálogo público indexável,
pré-visualização 3D em formato web, login pelo Google, pedido Pix pelo Mercado
Pago, confirmação por webhook, download protegido, administração delegável e
solicitações de peças sob encomenda.

A solução seguirá a aplicação CodeIgniter existente, com arquivos originais fora
da pasta pública, pré-visualizações separadas e autorização de download baseada
em pedido pago, cliente autenticado e arquivo específico.

## Contexto técnico

**Linguagem/versão**: PHP 8.2; JavaScript moderno no navegador.

**Dependências principais**: CodeIgniter 4.7.6, CodeIgniter Shield 1.4.1,
Composer, MariaDB/MySQL, Apache, Three.js com GLTFLoader para a pré-visualização.

**Armazenamento**: MariaDB/MySQL para dados; armazenamento privado em disco fora
de \`public/\` na primeira versão, com abstração para armazenamento compatível com
objeto em futura implantação.

**Testes**: PHPUnit para unidade e integração; testes de contrato para webhook,
autenticação, download e permissões; validação manual no ambiente \`3d.test\`.

**Plataforma-alvo**: Aplicação web responsiva em PHP/Apache, inicialmente no
Windows/XAMPP e preparada para servidor Linux.

**Tipo de projeto**: Aplicação web monolítica com páginas públicas, área do
cliente e painel administrativo.

**Metas de desempenho**: páginas públicas comuns em até 2 segundos no ambiente
local; pesquisa do catálogo em até 1 segundo para o volume inicial; confirmação
de pagamento e disponibilização do download em até 2 minutos após notificação
válida.

**Restrições**: nenhum original em local público; nenhuma liberação baseada apenas
em dados enviados pelo navegador; webhooks idempotentes; credenciais fora do
controle de versão; uploads validados e limitados; erros de produção sem detalhes
internos.

**Escala/escopo**: primeira versão para catálogo inicial de até 10.000 modelos,
até 100.000 clientes e administração por poucos usuários delegados.

## Verificação da constituição

*GATE: aprovado antes da pesquisa e revisado após o desenho.*

- **Princípio I**: aprovado. Pré-visualização pública e arquivos originais privados
  serão entidades e fluxos separados.
- **Princípio II**: aprovado. O pagamento só muda de estado após confirmação
  validada do Mercado Pago; o webhook será idempotente.
- **Princípio III**: aprovado. O plano prioriza a jornada completa de descoberta,
  compra e download, deixando conversão automática de formatos para depois.
- **Princípio IV**: aprovado. O Shield e permissões explícitas limitarão as áreas
  administrativas e registrarão ações sensíveis.
- **Princípio V**: aprovado. Cada integração crítica terá testes de contrato,
  unidade, integração e roteiro de validação.
- **Segurança do produto**: aprovado. Arquivos privados ficam fora de \`public/\`,
  segredos ficam no ambiente e downloads dependem de autorização persistida.

## Estrutura do projeto

### Documentação desta funcionalidade

\`\`\`text
specs/001-3d-marketplace/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   ├── google-oauth.md
│   ├── mercado-pago-webhook.md
│   └── download-protegido.md
└── tasks.md                 # criado pelo speckit-tasks
\`\`\`

### Código-fonte

\`\`\`text
app/
├── Config/
│   ├── Auth.php
│   ├── MercadoPago.php       # nova configuração
│   ├── Google.php             # nova configuração
│   └── Files.php              # nova configuração
├── Controllers/
│   ├── Catalog/
│   ├── Auth/
│   ├── Checkout/
│   ├── Downloads/
│   ├── Admin/
│   └── CustomRequests/
├── Models/
├── Services/
│   ├── Payments/
│   ├── Storage/
│   ├── Preview/
│   └── Audit/
├── Database/
│   ├── Migrations/
│   └── Seeds/
└── Views/
    ├── layouts/
    ├── catalog/
    ├── checkout/
    ├── account/
    ├── admin/
    └── custom-requests/
public/
└── assets/
    ├── css/
    └── js/
tests/
├── unit/
├── integration/
└── feature/
writable/
└── uploads/private/models/   # nunca servido diretamente pelo Apache
\`\`\`

**Decisão estrutural**: manter o monólito CodeIgniter já existente, separando
controllers, modelos, serviços e views por contexto. A camada de serviços
isolará Mercado Pago, Google, armazenamento, pré-visualização e auditoria para
permitir testes e troca futura de fornecedor.

## Rastreamento de complexidade

Não há violações da constituição que exijam justificativa. A separação em
serviços é necessária para proteger pagamentos e arquivos, não é uma camada
organizacional sem responsabilidade.
