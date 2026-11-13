# Contrato: autenticação Google

## Início

**Rota**: \`GET /login/google\`

O sistema redireciona o navegador para o Google com escopo mínimo, estado
aleatório associado à sessão e URI de retorno HTTPS previamente cadastrada.

## Retorno

**Rota**: \`GET /login/google/callback\`

Entradas: \`code\`, \`state\`, e parâmetros de erro do provedor.

Comportamento:

- Validar \`state\` contra a sessão e rejeitar reutilização.
- Trocar \`code\` no servidor.
- Validar identidade, e-mail e identificador estável do Google.
- Criar ou atualizar o usuário local.
- Redirecionar para a página originalmente solicitada ou para a conta.
- Não expor segredo, token ou código de autorização ao cliente.

Falhas retornam mensagem segura e não criam pedido incompleto.
