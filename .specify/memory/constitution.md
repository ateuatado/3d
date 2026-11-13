<!--
Relatório de impacto da sincronização
- Alteração de versão: 1.0.0 -> 1.0.1
- Alterações: tradução integral para português, sem mudança de regras.
- Seções adicionadas: nenhuma.
- Seções removidas: nenhuma.
- Modelos que exigem atualização: nenhum.
- Pendências: nenhuma.
-->

# Constituição do 3d

## Princípios fundamentais

### I. Descoberta pública e proteção dos produtos digitais
O catálogo DEVE ser público e útil para mecanismos de busca, enquanto os arquivos
comerciais dos modelos DEVEM permanecer inacessíveis até que uma compra autorizada
seja confirmada. Os recursos de pré-visualização e os arquivos originais para
download DEVEM ser tratados como itens separados. Toda autorização de download
DEVE estar associada a um cliente e a um pedido.

### II. Comércio seguro e verdade do pagamento
O sistema DEVE considerar a notificação confirmada do provedor de pagamento como
a fonte de verdade para a conclusão do pagamento. DEVE permitir novas tentativas
seguras, impedir liberações duplicadas, manter auditoria das alterações de pedidos
e pagamentos e nunca liberar um download apenas porque o cliente declarou que pagou.

### III. Valor para o cliente antes da complexidade administrativa
Cada funcionalidade DEVE atender a um resultado claro para o cliente ou para o
negócio: descobrir um modelo, entendê-lo, comprá-lo, baixá-lo ou solicitar uma
peça personalizada. As funções administrativas DEVEM viabilizar esses resultados,
não criar complexidade interna isolada. A primeira versão DEVE priorizar uma jornada
de compra pequena e completa em vez de muitas funções incompletas.

### IV. Administração delegável com menor privilégio
A plataforma DEVE permitir várias contas administrativas com funções e permissões
explícitas. O acesso administrativo DEVE seguir o princípio do menor privilégio,
registrar ações sensíveis e permitir que o proprietário revogue acessos delegados
sem afetar as compras dos clientes.

### V. Entrega testável, observável e reversível
Os fluxos críticos DEVEM possuir testes automatizados e cenários de aceitação,
especialmente autenticação, transições de pagamento, autorização de download e
permissões administrativas. Falhas DEVEM gerar registros úteis sem expor segredos
ou arquivos de modelos. Alterações em comércio ou segurança DEVEM ser reversíveis
ou acompanhadas de plano de migração e reversão.

## Escopo do produto e padrões de segurança

O produto é um marketplace de modelos 3D e solicitações de peças sob encomenda.
A primeira versão inclui páginas públicas do catálogo, pré-visualização 3D na web,
login pelo Google, pagamento Pix pelo Mercado Pago, downloads protegidos após a
confirmação, catálogo administrativo, administração delegada e solicitações de
orçamento manual com atendimento pelo WhatsApp. Os arquivos originais NÃO DEVEM
ser armazenados em local público acessível pela web. Formatos comuns podem ser
vendidos, enquanto as pré-visualizações DEVEM usar uma representação adequada ao
navegador.

Segredos, credenciais de pagamento, credenciais OAuth e locais privados de arquivos
DEVEM permanecer fora do controle de versão. Os dados de clientes e pagamentos
DEVEM ter controles de acesso apropriados, e respostas de erro em produção NÃO
DEVEM revelar caminhos internos, credenciais ou recursos privados.

## Fluxo de desenvolvimento

Toda funcionalidade DEVE começar com uma especificação voltada ao usuário,
cenários priorizados, requisitos testáveis, critérios de sucesso mensuráveis e
premissas explícitas. Os planos DEVEM identificar impactos de segurança, pagamento,
proteção de arquivos e permissões. A implementação DEVE incluir testes automatizados
direcionados e uma etapa de verificação da jornada afetada. Uma alteração não está
pronta apenas porque funciona localmente; ela está pronta quando seus cenários de
aceitação, caminhos de falha e notas operacionais estiverem cobertos.

## Governança

Esta constituição governa especificações, planos, implementação e revisões do
produto. Alterações exigem justificativa documentada, atualização da versão
semântica e revisão das especificações e modelos dependentes. Versões principais
indicam mudanças de governança incompatíveis; versões secundárias adicionam ou
ampliam princípios; versões de correção esclarecem o texto sem alterar sua intenção.

Todas as revisões DEVEM verificar a conformidade com estes princípios. Qualquer
exceção DEVE ser explícita na especificação ou no plano correspondente, conter sua
justificativa e definir correção posterior ou prazo de validade. A constituição
DEVE ser revisada quando houver mudança em pagamentos, autenticação, entrega de
arquivos, permissões administrativas ou escopo do produto.

**Versão**: 1.0.1 | **Ratificada**: 2026-11-13 | **Última alteração**: 2026-11-13
