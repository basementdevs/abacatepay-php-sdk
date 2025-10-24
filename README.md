# AbacatePay PHP SDK

SDK para integração com a **API AbacatePay**.
Permite gerenciar clientes, cobranças e transações de forma simples usando PHP.

---

## Instalação

### Via Composer (quando publicado)

```bash
composer require abacatepay/sdk
```

---

## Inicialização

```php
use AbacatePay\AbacatePay;

$sdk = new AbacatePay('seu-token-aqui');
```

O token deve ser um **Bearer Token** válido fornecido pelo painel da AbacatePay.

---

## Recursos Disponíveis

### CustomerResource

Gerencia operações relacionadas a clientes.

#### Criar Cliente

```php
use AbacatePay\Customer\CustomerResource;
use AbacatePay\Customer\Request\CreateCustomerRequest;

$customer = new CreateCustomerRequest(
    name: 'Maria Silva',
    email: 'maria@example.com',
    document: '12345678900',
    phone: '+5511999999999'
);

$response = $sdk->customer()->create($customer);

echo $response->data->id; // ID do cliente criado
```

---

#### Listar Clientes

```php
$customers = $sdk->customer()->list();

foreach ($customers as $customer) {
    echo $customer->name . PHP_EOL;
}
```

---

### Tratamento de Erros

Todas as operações podem lançar:

* `AbacatePay\Exception\AbacatePayException`
* `JsonException`

Exemplo:

```php
try {
    $sdk->customer()->list();
} catch (AbacatePayException $e) {
    echo "Erro: {$e->getMessage()} ({$e->getCode()})";
}
```

---

## Exceções Comuns

| Código | Exceção                               | Descrição                 |
| ------ | ------------------------------------- | ------------------------- |
| 401    | `AbacatePayException::unauthorized()` | Token inválido ou ausente |
| 500    | `AbacatePayException`                 | Erro interno na API       |

---

## Contribuição

1. Faça fork do repositório
2. Crie um branch: `git checkout -b feature/nova-funcionalidade`
3. Rode os testes com **Pest**:

   ```bash
   composer test
   ```
4. Envie um Pull Request

---

## Licença

MIT © AbacatePay

---

Quer que eu adicione uma seção com a **documentação dos modelos** (`CreateCustomerRequest`, `CreateCustomerResponse`, `CustomerEntity`)? Isso completa o guia para quem vai usar o SDK.
********