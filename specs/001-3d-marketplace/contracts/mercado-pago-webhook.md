# Contrato: webhook do Mercado Pago

## Recebimento

**Rota**: \`POST /integracoes/mercado-pago/webhook\`

O endpoint recebe a notificação HTTPS do Mercado Pago. O corpo recebido é tratado
como entrada não confiável.

Comportamento:

1. Validar assinatura e identificador da aplicação conforme configuração do
   Mercado Pago.
2. Registrar o identificador da notificação para idempotência.
3. Responder rapidamente com \`200 OK\` ou \`201 Created\` quando a mensagem foi
   aceita para processamento.
4. Consultar o recurso oficial no Mercado Pago.
5. Reconciliar o pagamento externo com o pedido local.
6. Liberar download apenas quando o estado validado for pago.

Requisitos:

- Repetição da mesma notificação não pode criar nova liberação.
- Notificação desconhecida é registrada e não altera pedidos.
- Falha temporária de consulta pode ser reprocessada.
- Tokens e credenciais nunca entram na resposta ou no log.
