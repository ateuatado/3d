# Pesquisa técnica: Marketplace de modelos 3D

## Decisão 1: confirmação Pix por webhook e consulta do pagamento

**Decisão**: receber notificações HTTPS do Mercado Pago, validar a autenticidade
da notificação, consultar o recurso no Mercado Pago e somente então alterar o
pedido para pago. O identificador da notificação será armazenado para garantir
idempotência.

**Motivo**: o navegador não é uma fonte confiável para declarar pagamento. A
documentação do Mercado Pago informa que webhooks são enviados por HTTPS e que
o servidor deve responder rapidamente e consultar o recurso notificado para obter
os dados completos. A integração precisa tratar reenvios e responder com sucesso
antes de executar processamento demorado.

**Alternativas consideradas**:

- Consulta periódica pelo navegador: rejeitada por ser frágil e não confiável.
- Liberar após retorno do checkout: rejeitada porque o retorno não confirma a
  liquidação.
- Processar a notificação sem consulta: rejeitada porque não valida o estado
  completo da transação.

**Fonte**: [Webhooks do Mercado Pago](https://www.mercadopago.com.br/developers/pt/docs/your-integrations/notifications/webhooks)

## Decisão 2: fluxo OAuth do Google no servidor

**Decisão**: usar o fluxo de autorização OAuth 2.0 para aplicação web, com estado
anti-CSRF, URI de retorno HTTPS, escopos mínimos \`openid\`, \`email\` e \`profile\`,
e associação pelo identificador estável do Google. O segredo ficará somente no
ambiente do servidor.

**Motivo**: o fluxo de servidor permite manter o segredo fora do navegador e
validar a resposta no backend. O Google exige que a URI de retorno coincida
exatamente com a cadastrada e recomenda bibliotecas maduras para OAuth.

**Alternativas consideradas**:

- Implementar senha própria: rejeitada para a primeira versão, pois o usuário
  escolheu login Google.
- Confiar somente em dados enviados pelo navegador: rejeitada por segurança.
- Solicitar escopos amplos: rejeitada por privacidade e menor privilégio.

**Fonte**: [OAuth 2.0 para aplicações web do Google](https://developers.google.com/identity/protocols/oauth2/web-server?hl=pt-br)

## Decisão 3: formato canônico da pré-visualização 3D

**Decisão**: usar GLB ou glTF como formato de pré-visualização web e manter os
formatos originais como arquivos comerciais. Na primeira versão, o administrador
enviará a pré-visualização pronta; conversão automática de STL, OBJ, FBX e outros
formatos fica fora do primeiro ciclo.

**Motivo**: glTF é um formato aberto adequado à transmissão de cenas 3D e o
GLTFLoader do Three.js fornece suporte direto ao glTF 2.0 e extensões comuns.
Separar a pré-visualização do original evita que o visualizador se torne um
canal de download do produto comercial.

**Alternativas consideradas**:

- Tentar converter todo formato no servidor: rejeitada na primeira versão por
  aumentar dependências, consumo de CPU e risco de arquivos malformados.
- Entregar o original ao visualizador: rejeitada porque viola a proteção do produto.
- Usar somente imagens: rejeitada porque não atende à visualização interativa.

**Fonte**: [GLTFLoader do Three.js](https://threejs.org/docs/pages/GLTFLoader.html)

## Decisão 4: armazenamento privado de arquivos

**Decisão**: armazenar originais e anexos em diretório controlado pela aplicação,
fora de \`public/\`, com nomes gerados pelo sistema. A entrega será feita por uma
ação autenticada que verifica cliente, pedido, arquivo, status de pagamento e
revogação antes de transmitir o conteúdo.

**Motivo**: o CodeIgniter recomenda manter uploads fora da pasta pública e não
usar diretamente nomes ou diretórios fornecidos pelo usuário. A estratégia
também permite migrar a camada de armazenamento para um serviço de objetos sem
alterar a regra de autorização.

**Alternativas consideradas**:

- Colocar os arquivos em \`public/uploads\`: rejeitada por permitir acesso direto.
- Usar o nome enviado pelo cliente: rejeitada por risco de colisão e travessia de
  diretórios.
- Exigir armazenamento externo desde o início: rejeitada para reduzir o custo da
  primeira versão local.

**Fonte**: [Uploads de arquivos no CodeIgniter 4](https://codeigniter.com/user_guide/libraries/uploaded_files.html)

## Decisão 5: integrações isoladas por serviços

**Decisão**: Mercado Pago, Google, armazenamento, pré-visualização e auditoria
serão acessados por serviços com contratos próprios. Controllers não terão regras
de pagamento ou montagem de caminhos privados.

**Motivo**: reduz acoplamento, facilita testes de contrato e permite substituir
fornecedores ou mover arquivos para armazenamento de objetos.

**Alternativas consideradas**:

- Chamar APIs diretamente nos controllers: rejeitada por dificultar testes e
  aumentar o risco de misturar autorização com transporte.
- Criar uma API separada nesta fase: rejeitada por aumentar a superfície e o
  custo operacional sem necessidade para o primeiro lançamento.
