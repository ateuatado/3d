# Contrato: download protegido

## Solicitação

**Rota**: \`GET /downloads/{autorizacao}\`

Pré-condições:

- Sessão autenticada.
- Autorização pertence ao usuário autenticado.
- Pedido associado está pago e não revogado.
- Arquivo está aprovado e existe no armazenamento privado.
- Autorização não expirou e ainda pode ser usada.

Resposta de sucesso:

- Transmissão do arquivo com nome comercial seguro.
- Nenhum caminho interno é revelado.
- Registro do download e atualização do uso da autorização.

Respostas de falha:

- \`401\` quando não há autenticação.
- \`403\` quando a autorização não pertence ao usuário ou foi revogada.
- \`404\` quando o identificador não existe ou o arquivo não está disponível.
- \`410\` quando a autorização expirou.

O endpoint não aceita caminho de arquivo, nome de arquivo ou identificador de
modelo como substituto da autorização.
