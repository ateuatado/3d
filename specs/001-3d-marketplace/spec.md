# Especificação da funcionalidade: Marketplace de modelos 3D

**Ramo da funcionalidade**: \`001-3d-marketplace\`

**Criado em**: 2026-11-13

**Status**: Rascunho

**Descrição recebida**: "Criar um marketplace de modelos 3D com páginas públicas indexáveis pelo Google, pré-visualização interativa, downloads protegidos após pagamento Pix pelo Mercado Pago, login pelo Google, solicitações manuais de peças sob encomenda via WhatsApp e contas administrativas delegáveis."

## Cenários de uso e testes

### História de usuário 1 - Descobrir e avaliar um modelo (Prioridade: P1)

Como visitante, quero encontrar um modelo pela busca ou pelo catálogo e analisar sua pré-visualização e informações antes de decidir se ele é útil.

**Motivo da prioridade**: A descoberta pública é a base para atrair clientes.

**Teste independente**: Publicar um modelo, abrir sua página anonimamente e verificar que o visitante consegue analisar a pré-visualização e as informações comerciais sem receber o arquivo original.

**Cenários de aceitação**:

1. **Dado** um modelo publicado, **quando** um visitante abre sua página, **então** a página mostra título, descrição, pré-visualização, formatos, preço e ação de compra.
2. **Dado** um visitante pesquisando no catálogo, **quando** ele envia um termo relevante, **então** são exibidos modelos publicados correspondentes.
3. **Dado** um visitante anônimo em uma página de modelo, **quando** ele analisa os recursos da página, **então** o arquivo original protegido não é exposto.

4. **Dado** um visitante na página inicial, **quando** ele acessa o site, **então** ele vê modelos publicados, descrições resumidas, modelos gratuitos e uma chamada para solicitar uma peça impressa ou personalizada.
5. **Dado** um modelo marcado como gratuito, **quando** o visitante acessa sua página, **então** ele consegue baixar o arquivo original sem pagamento, por uma rota protegida que só entrega modelos publicados e gratuitos.

### História de usuário 2 - Comprar e baixar um modelo (Prioridade: P1)

Como cliente, quero entrar com o Google, pagar por Pix e baixar os arquivos comprados após a confirmação do pagamento.

**Motivo da prioridade**: A entrega paga é a transação central do negócio.

**Teste independente**: Concluir um pagamento Pix de teste, confirmar a notificação do provedor e verificar que somente o cliente pago recebe um download autorizado.

**Cenários de aceitação**:

1. **Dado** um visitante não autenticado, **quando** ele inicia a compra, **então** o sistema solicita autenticação pelo Google.
2. **Dado** um cliente autenticado, **quando** ele inicia a compra, **então** o sistema cria um pedido pendente e apresenta as instruções Pix.
3. **Dado** um pedido pendente, **quando** o Mercado Pago confirma o pagamento, **então** o pedido fica pago e a conta exibe o download.
4. **Dado** um pedido não pago ou recusado, **quando** o cliente solicita o arquivo, **então** o acesso é negado.
5. **Dado** uma notificação de pagamento duplicada, **quando** ela é processada, **então** a liberação ocorre apenas uma vez.

### História de usuário 3 - Gerenciar catálogo e administração delegada (Prioridade: P1)

Como proprietário ou administrador autorizado, quero gerenciar modelos, arquivos, definições, preços, publicação e permissões delegadas.

**Motivo da prioridade**: O marketplace precisa de um catálogo operável e de delegação segura.

**Teste independente**: Criar um administrador de catálogo, publicar um modelo completo com essa conta e verificar que as funções financeiras continuam indisponíveis para ela.

**Cenários de aceitação**:

1. **Dado** um administrador de catálogo, **quando** ele cria um modelo, **então** pode informar descrição, definições técnicas, formatos, preço, pré-visualização, arquivos originais e status.
2. **Dado** um modelo sem informações comerciais obrigatórias, **quando** a publicação é tentada, **então** o sistema informa o que falta e bloqueia a publicação.
3. **Dado** o proprietário, **quando** ele cria ou atualiza um administrador, **então** pode atribuir e depois revogar permissões.
4. **Dado** uma alteração de permissão ou catálogo, **quando** ela é concluída, **então** o responsável e a alteração são registrados.

### História de usuário 4 - Solicitar uma peça personalizada (Prioridade: P2)

Como visitante ou cliente, quero enviar requisitos e anexos de uma peça personalizada e continuar o atendimento pelo WhatsApp.

**Motivo da prioridade**: O trabalho personalizado cria uma segunda fonte de receita.

**Teste independente**: Enviar uma solicitação com detalhes e anexo, verificar que ela foi registrada e confirmar a ação de contato pelo WhatsApp.

**Cenários de aceitação**:

1. **Dado** um visitante ou cliente, **quando** ele envia uma solicitação, **então** o sistema registra contato, requisitos e anexos permitidos.
2. **Dado** uma solicitação enviada, **quando** ela é aceita, **então** o usuário recebe uma ação clara para contato pelo WhatsApp.
3. **Dado** um administrador de atendimento, **quando** ele analisa uma solicitação, **então** pode atualizar o status e as notas internas sem alterar o envio original.

### Casos extremos

- Um modelo pode conter vários arquivos de origem e uma pré-visualização separada.
- O pagamento pode ser confirmado depois que o cliente fecha o navegador.
- O pagamento pode ser recusado, cancelado, estornado ou contestado.
- Um modelo pode ser retirado do catálogo depois da compra; compras anteriores continuam visíveis, salvo revogação explícita.
- O checkout pode ser aberto em várias abas; pedidos e liberações duplicadas devem ser impedidos ou reconciliados.
- Google, Mercado Pago, upload ou download podem ficar temporariamente indisponíveis.
- Arquivos de modelos e anexos incompatíveis ou maliciosos devem ser rejeitados ou isolados.

## Requisitos

### Requisitos funcionais

- **RF-001**: O sistema DEVE fornecer uma página inicial pública com modelos publicados, descrições resumidas, destaque para modelos gratuitos e chamada para modelos impressos ou peças personalizadas.
- **RF-001a**: O sistema DEVE fornecer páginas públicas para modelos publicados com título, descrição, pré-visualização, especificações, formatos, preço e ação de compra ou download gratuito.
- **RF-002**: O sistema DEVE permitir a indexação das páginas públicas e excluir da descoberta pública as páginas administrativas, de clientes, pagamentos e downloads.
- **RF-003**: O sistema DEVE fornecer pré-visualização 3D compatível com o navegador sem expor o original comercial.
- **RF-004**: Administradores autorizados DEVEM poder criar, editar, publicar, retirar, arquivar e versionar modelos.
- **RF-005**: O sistema DEVE aceitar formatos 3D comuns e vários arquivos por modelo.
- **RF-005a**: O sistema DEVE permitir classificar cada modelo como gratuito ou pago e exibir essa condição no catálogo e na página de detalhes.
- **RF-005b**: O download gratuito DEVE validar publicação, gratuidade e arquivo original aprovado antes de entregar o arquivo.
- **RF-005c**: O sistema DEVE apresentar uma chamada para solicitação de impressão física ou peça personalizada, inicialmente direcionada ao WhatsApp.
- **RF-006**: As definições do modelo DEVEM aceitar dimensões, unidades, uso pretendido, notas de compatibilidade, etiquetas, categorias e termos de licença.
- **RF-007**: Administradores DEVEM poder definir preços e status de publicação, com histórico de alterações relevantes.
- **RF-008**: Clientes DEVEM autenticar-se pelo Google antes que uma compra ou download seja associado a uma conta.
- **RF-009**: O sistema DEVE criar um pedido pendente antes de mostrar as instruções Pix.
- **RF-010**: As instruções Pix DEVEM ser específicas para o pedido e seu valor.
- **RF-011**: O status do pagamento e do pedido DEVE mudar somente por confirmação validada do provedor ou ação administrativa autorizada e auditada.
- **RF-012**: Um modelo DEVE ser baixável somente quando o pedido associado estiver pago e não revogado.
- **RF-013**: Os arquivos originais DEVEM ser inacessíveis diretamente e entregues por download autorizado, expirável ou revogável.
- **RF-014**: Notificações duplicadas ou ações repetidas do cliente NÃO DEVEM causar liberações duplicadas.
- **RF-015**: Clientes DEVEM visualizar pedidos, status de pagamento, compras e downloads disponíveis.
- **RF-016**: O proprietário DEVE poder criar, editar, desativar e atribuir permissões a administradores.
- **RF-017**: O sistema DEVE aplicar menor privilégio às funções de catálogo, atendimento, pedidos, pagamentos e administração da plataforma.
- **RF-018**: Ações sensíveis, alterações de permissões, ajustes de pagamento, mudanças de publicação e autorizações de download DEVEM ser auditáveis.
- **RF-019**: Visitantes ou clientes DEVEM poder enviar solicitações de peças sob encomenda com contato, requisitos e anexos permitidos.
- **RF-020**: Administradores de atendimento DEVEM poder analisar solicitações, atualizar status e adicionar notas internas sem alterar o envio original.
- **RF-021**: O sistema DEVE fornecer uma ação de contato pelo WhatsApp para atendimento personalizado.
- **RF-022**: Arquivos de modelos e anexos DEVEM ser validados quanto a tipo, tamanho, integridade e elegibilidade para publicação.
- **RF-023**: Mensagens de falha DEVEM ser úteis e NÃO DEVEM revelar segredos, caminhos privados ou recursos privados.
- **RF-024**: Informações de pedidos, pagamentos, acessos e auditoria DEVEM ser mantidas para atendimento e investigação de disputas.

### Entidades principais

- **Modelo**: Produto do catálogo com descrição, pré-visualização, definições, formatos, preço, status, licença e versões.
- **Arquivo do modelo**: Arquivo original, de pré-visualização ou complementar, com classificação de visibilidade e uso.
- **Cliente**: Pessoa autenticada pelo Google que pode comprar, baixar e solicitar trabalho personalizado.
- **Administrador**: Conta da equipe com permissões delegadas e identidade auditável.
- **Pedido**: Intenção de compra com itens, valor, moeda e status.
- **Pagamento**: Transação do Mercado Pago associada ao pedido e aos dados de confirmação Pix.
- **Autorização de download**: Permissão que liga cliente, pedido pago, arquivo, validade e revogação.
- **Solicitação personalizada**: Pedido manual de atendimento com requisitos, anexos, status, notas e contexto do WhatsApp.
- **Evento de auditoria**: Registro de atividade de segurança, comércio, permissões, publicação ou liberação.

## Critérios de sucesso

### Resultados mensuráveis

- **CS-001**: Pelo menos 95% dos visitantes iniciantes encontram a ação de compra e as especificações na página pública sem ajuda.
- **CS-002**: Um cliente autenticado consegue criar um pedido Pix em menos de 3 minutos quando o provedor está disponível.
- **CS-003**: Pelo menos 98% dos pedidos pagos confirmados exibem o download autorizado em até 2 minutos após a confirmação.
- **CS-004**: Nenhum caso de teste não pago ou não autenticado consegue recuperar um arquivo original.
- **CS-005**: Pelo menos 95% dos clientes representativos concluem login Google, início do pagamento e download sem suporte.
- **CS-006**: Um administrador de catálogo consegue publicar um modelo completo em menos de 10 minutos.
- **CS-007**: 100% das alterações de permissões, ajustes de pagamento, publicação e autorizações de download têm registro auditável.
- **CS-008**: Pelo menos 90% das solicitações válidas são registradas com sucesso e fornecem um contato utilizável pelo WhatsApp.

## Premissas

- A primeira versão vende arquivos digitais; fabricação e envio físicos estão fora do escopo.
- O Pix pelo Mercado Pago é o método inicial; outros métodos poderão ser adicionados.
- O login pelo Google é o método principal; login por senha está fora da primeira versão.
- As pré-visualizações usam representação convertida ou enviada separadamente; os formatos originais permanecem protegidos.
- Credenciais do Mercado Pago, Google OAuth e WhatsApp serão fornecidas por configuração segura do ambiente.
- O preço de peças personalizadas é manual e não será calculado automaticamente na primeira versão.
- O WhatsApp é o canal principal de atendimento personalizado; chat interno está fora da primeira versão.
- A primeira versão atende navegadores responsivos; aplicativo nativo está fora do escopo.
- Os preços usam a moeda configurada para o negócio, e as exigências fiscais e legais serão confirmadas antes do lançamento.
