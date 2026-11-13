# Guia de validação: Marketplace de modelos 3D

## Pré-requisitos

- PHP 8.2 ou superior.
- Composer instalado.
- MariaDB/MySQL disponível.
- Projeto configurado em \`C:\xampp\htdocs\3d\`.
- Credenciais de teste do Google OAuth.
- Credenciais de teste do Mercado Pago.
- URL HTTPS acessível para o webhook de teste.

## Preparar o ambiente

Na raiz do projeto:

\`\`\`powershell
composer install
php spark migrate --all
php spark serve
\`\`\`

Configure no ambiente, sem versionar:

- \`app.baseURL\`
- banco de dados
- cliente e segredo Google OAuth
- token e assinatura do Mercado Pago
- telefone do WhatsApp
- diretório privado de arquivos

## Cenário 1: catálogo público

1. Criar um modelo em rascunho com pré-visualização GLB/GLTF.
2. Adicionar pelo menos um arquivo comercial original.
3. Definir preço, formatos, licença e especificações.
4. Publicar o modelo.
5. Abrir a página em janela anônima.

Resultado esperado:

- A página mostra conteúdo comercial e pré-visualização.
- A página pode ser encontrada pelo catálogo.
- O arquivo original não está acessível por URL pública.

## Cenário 2: compra Pix e download

1. Abrir o modelo como visitante.
2. Entrar pelo Google.
3. Criar o pedido.
4. Gerar as instruções Pix no ambiente de testes.
5. Simular ou concluir o pagamento.
6. Entregar a notificação do Mercado Pago.
7. Consultar a conta do cliente.

Resultado esperado:

- O pedido inicia como pendente.
- O pedido só fica pago após confirmação validada.
- O download aparece somente depois da confirmação.
- Reenviar a mesma notificação não duplica a liberação.
- Um usuário diferente não consegue usar a autorização.

## Cenário 3: administração delegada

1. Criar um administrador com permissão apenas de catálogo.
2. Entrar com esse administrador.
3. Criar e publicar um modelo.
4. Tentar acessar funções financeiras.
5. Revogar a permissão pelo proprietário.

Resultado esperado:

- O administrador publica o modelo.
- Funções fora da permissão ficam bloqueadas.
- A revogação impede novas ações.
- A criação, publicação e revogação aparecem na auditoria.

## Cenário 4: peça sob encomenda

1. Abrir o formulário público de encomenda.
2. Enviar descrição, requisitos, contato e anexo válido.
3. Verificar o registro no painel de atendimento.
4. Usar a ação de WhatsApp.

Resultado esperado:

- A solicitação é registrada sem alterar o texto original.
- O administrador pode mudar o status e adicionar notas internas.
- O contato do WhatsApp aponta para o canal configurado.

## Verificações automatizadas

\`\`\`powershell
vendor\\bin\\phpunit.bat --no-coverage
php spark routes
php spark migrate:status
\`\`\`

Os testes devem cobrir autenticação, transições de pagamento, idempotência,
autorização de download, upload privado e permissões administrativas. Os
contratos detalhados estão em \`contracts/\`; as entidades e estados estão em
\`data-model.md\`.
