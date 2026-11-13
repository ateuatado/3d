# Modelo de dados: Marketplace de modelos 3D

## Usuário

Representa cliente autenticado e identidade administrativa.

Campos principais: identificador, nome, e-mail, identificador do Google, imagem,
status, data de criação e último acesso.

Regras:

- O identificador do Google é único quando presente.
- O e-mail normalizado é único.
- Um usuário desativado não inicia novos pedidos nem downloads.

## Papel e permissão administrativa

Define acesso delegado sem misturar funções de catálogo, atendimento, pedidos,
pagamentos e administração da plataforma.

Campos principais: usuário, papel, permissões, criado por, ativo, criado em,
revogado em.

Regras:

- Somente o proprietário ou administrador com permissão pode delegar acesso.
- A revogação tem efeito imediato em novas ações.
- Alterações são registradas em auditoria.

## Modelo

Produto público do catálogo.

Campos principais: título, slug, descrição, categoria, etiquetas, dimensões,
unidade, uso pretendido, compatibilidade, licença, preço, moeda, status, versão
publicada, criado por, atualizado por e datas.

Estados: rascunho -> publicado -> arquivado; publicado pode voltar a rascunho.

Regras:

- Slug é único.
- Publicação exige título, descrição, preço, licença, pré-visualização e pelo
  menos um arquivo comercial válido.
- Modelo arquivado não aparece em novas buscas.
- Alterações de preço e publicação geram evento de auditoria.

## Arquivo do modelo

Arquivo associado a um modelo e a uma versão.

Campos principais: modelo, versão, tipo de arquivo, nome original exibido,
nome interno aleatório, caminho privado, tamanho, hash, extensão, MIME, status,
pré-visualização ou original, criado em.

Regras:

- O caminho interno nunca é derivado diretamente da entrada do cliente.
- O arquivo original fica fora de \`public/\`.
- O hash permite detectar substituições e validar integridade.
- Arquivos rejeitados não podem ser publicados nem baixados.

## Pedido

Compra de um ou mais modelos.

Campos principais: cliente, número público, status, subtotal, desconto, total,
moeda, criado em, pago em, cancelado em e motivo.

Estados: pendente -> pago; pendente -> cancelado; pendente -> expirado; pago ->
estornado; pago -> contestado.

Regras:

- O valor do pedido é congelado no momento da criação.
- O cliente pode consultar somente os próprios pedidos.
- A transição para pago exige confirmação validada do pagamento.

## Item do pedido

Vincula um pedido a um modelo e registra o preço no momento da compra.

Campos principais: pedido, modelo, versão, descrição comercial, quantidade,
preço unitário e total.

Regras:

- O preço histórico não muda quando o preço atual do modelo muda.
- Um item pago gera autorização para os arquivos da versão comprada.

## Pagamento

Representa a transação Pix no Mercado Pago.

Campos principais: pedido, identificador externo, status externo, método, valor,
resposta resumida, data de criação, última consulta, confirmação e estorno.

Regras:

- O identificador externo é único.
- O sistema armazena o mínimo necessário da resposta e nunca armazena segredo.
- Mudanças externas são reconciliadas antes de alterar o pedido.

## Autorização de download

Concede acesso a um cliente para um arquivo comprado.

Campos principais: cliente, pedido, item, arquivo, identificador aleatório,
expiração, quantidade de usos, revogado em, último download e criado em.

Regras:

- Deve existir cliente autenticado, pedido pago e arquivo associado.
- O identificador não pode revelar caminho ou identificador sequencial.
- A autorização pode expirar ou ser revogada.
- Toda tentativa relevante é registrada.

## Solicitação personalizada

Pedido de orçamento manual para uma peça sob encomenda.

Campos principais: cliente opcional, nome, e-mail, telefone, descrição,
requisitos, formato desejado, anexos, status, notas internas, responsável,
criado em e atualizado em.

Estados: recebida -> em análise -> aguardando cliente -> orçada -> aprovada ->
em produção -> concluída; também pode ser recusada ou cancelada.

Regras:

- A mensagem original do cliente não é alterada por notas internas.
- Anexos obedecem limites de tamanho e tipos permitidos.
- A ação de WhatsApp usa configuração do negócio, não entrada livre do cliente.

## Evento de auditoria

Registro imutável de atividade sensível.

Campos principais: ator, ação, entidade, identificador da entidade, resultado,
metadados não sensíveis, endereço de origem, data e correlação.

Regras:

- Não registrar tokens, segredos ou conteúdo integral de arquivos.
- Eventos de pagamento, permissões, publicação e downloads devem ser pesquisáveis.
- Falhas de auditoria devem ser observáveis sem impedir uma resposta segura ao
  cliente quando possível.

## Relacionamentos

\`\`\`text
Usuário 1---N Pedido
Usuário 1---N Papel/Permissão
Usuário 1---N Solicitação Personalizada
Modelo 1---N Arquivo do Modelo
Pedido 1---N Item do Pedido
Item do Pedido N---1 Modelo
Pedido 1---N Pagamento
Item do Pedido 1---N Autorização de Download
Arquivo do Modelo 1---N Autorização de Download
Usuário 1---N Evento de Auditoria
\`\`\`
