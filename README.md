# AbacatePay PHP SDK

SDK para integração com a **API AbacatePay**.
Permite gerenciar clientes, cobranças e transações de forma simples usando PHP.

---
## Requisitos

- PHP 8.3 ou maior
- Composer
- Conta na AbacatePay
- API Token

## Instalação

```sh
composer require basementdevs/abacatepay-php-sdk
```

## Features

O SDK oferece suporte completo às principais operações da API AbacatePay:

|Categoria|Funcionalidade|Status|
|---|---|---|
|**Clientes**|Criar novo cliente|✅|
||Listar clientes|✅|
|**Cobranças**|Criar nova cobrança|✅|
||Listar cobranças|✅|
|**Pix**|Criar QR Code Pix|✅|
||Consultar status de pagamento do QR Code Pix|✅|
|**Cupons**|Criar novo cupom|✅|
||Listar cupons|✅|
||Criar cupom via builder|✅|
|**Saques**|Criar novo saque|✅|
||Buscar saque por ID externo|✅|
||Listar saques|✅|

---

## Exemplos de Uso

### Criar Cliente

```php
use Basement\AbacatePay\AbacatePayClient;
use Basement\AbacatePay\Customer\CreateCustomerRequest;

$sdk = new AbacatePayClient('seu-token-aqui');

$request = new CreateCustomerRequest(
    name: 'Maria Silva',
    cellphone: '5599999999999',
    email: 'maria@teste.com',
    taxId: '12345678900',
);

$response = $sdk->customers()->create($request);
```

### Criar Cobrança

```php
use Basement\AbacatePay\AbacatePayClient;
use Basement\AbacatePay\Billing\CreateBillingRequest;
use Basement\AbacatePay\Billing\ProductRequest;
use Basement\AbacatePay\Billing\Enum\BillingMethodEnum;
use Basement\AbacatePay\Billing\Enum\BillingFrequencyEnum;

$sdk = new AbacatePayClient('seu-token-aqui');

$product = new ProductRequest(
    externalId: 'PROD-01',
    name: 'Curso PHP',
    description: 'Curso completo de PHP moderno',
    quantity: 1,
    price: 15000,
);

$request = new CreateBillingRequest(
    frequency: BillingFrequencyEnum::OneTime,
    methods: [BillingMethodEnum::Pix],
    products: [$product],
    return_url: 'https://seusite.com/sucesso',
    completion_url: 'https://seusite.com/finalizado',
    customerId: 'cust_abcdefghij',
    customer: null,
    allow_coupons: true,
    coupons: [],
    externalId: 'BILL-001',
);

// OR

$request = CreateBillingRequest::multipleTimes()
    ->creditCard()
    ->completionUrl('https://fodase.com')
    ->returnUrl('https://google.com')
    ->forCustomerId('cust_abc123412312312')
    ->externalId('some-amazing-key')
    ->addProduct(new ProductRequest('some-amazing-product', 't-shirt', 'def a tshirt', 1, 1337_00))
    ->build()

$response = $sdk->billing()->create($request);
```

### Criar QR Code Pix

```php
use Basement\AbacatePay\AbacatePayClient;
use Basement\AbacatePay\Pix\CreatePixQrCodeRequest;
use Basement\AbacatePay\Pix\PixCustomerRequest;
use Basement\AbacatePay\Pix\PixMetadataRequest;

$sdk = new AbacatePayClient('seu-token-aqui');

$customer = new PixCustomerRequest(
    name: 'João Souza',
    cellphone: '5598888888888',
    email: 'joao@teste.com',
    taxId: '98765432100'
);

$metadata = new PixMetadataRequest('ORDER-123');

$request = new CreatePixQrCodeRequest(
    amount: 2500,
    expiresIn: 600,
    description: 'Pagamento de pedido #123',
    customer: $customer,
    metadata: $metadata
);

$response = $sdk->pix()->createQrCode($request);
print_r($response);
```

### Criar Saque Pix

```php
use Basement\AbacatePay\AbacatePayClient;
use Basement\AbacatePay\Withdraw\CreateWithdrawRequest;
use Basement\AbacatePay\Withdraw\WithdrawPixRequest;
use Basement\AbacatePay\Withdraw\Enum\WithdrawMethodsEnum;
use Basement\AbacatePay\Withdraw\Enum\WithdrawPixTypeEnum;

$sdk = new AbacatePayClient('seu-token-aqui');

$pix = new WithdrawPixRequest(
    type: WithdrawPixTypeEnum::Random,
    key: 'random-key-aqui'
);

$request = new CreateWithdrawRequest(
    externalId: 'WITHDRAW-001',
    method: WithdrawMethodsEnum::Pix,
    amount: 10000,
    pix: $pix,
    description: 'Saque de teste'
);

$response = $sdk->withdraw()->create($request);
print_r($response);
```

## Comandos de Desenvolvimento

| Comando       | Descrição                                                         |
| ------------- | ----------------------------------------------------------------- |
| `make all`    | Executa todos os passos de qualidade e teste: Pint, Rector e Pest |
| `make rector` | Analisa o código com Rector e sugere refatorações                 |
| `make pint`   | Formata o código automaticamente usando Pint                      |
| `make test`   | Executa os testes automatizados com Pest                          |
| `make fix`    | Aplica correções automáticas no código com Pint e Rector          |

---

## Exceções

| Código | Exceção                               | Descrição                 |
| ------ | ------------------------------------- | ------------------------- |
| 401    | `AbacatePayException::unauthorized()` | Token de autenticação inválido ou ausente. |
| 500    | `AbacatePayException`                 | Erro interno na API       |

---

## Contribuição

1. Faça fork do repositório
2. Crie um branch: `git checkout -b feature/nova-funcionalidade`
3. Rode os testes com **Pest**:

```bash
  make test
```
4. Envie um Pull Request

---

## Suporte

- Para problemas com o SDK, abra uma issue no repositório.
- Para dúvidas sobre a API, envie e-mail para **[ajuda@abacatepay.com](mailto:ajuda@abacatepay.com)**.
- Para casos urgentes, entre em contato com o **time de suporte AbacatePay**.

