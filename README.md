# AbacatePay PHP SDK

SDK para integração com a **API AbacatePay**.
Permite gerenciar clientes, cobranças, QR Codes Pix e saques de forma simples e fluente usando PHP moderno.

---

## Requisitos

* PHP 8.3 ou maior
* Composer
* Conta na AbacatePay
* API Token válido

---

## Instalação

```bash
composer require basementdevs/abacatepay-php-sdk
```

---

## Features

| Categoria     | Funcionalidade                               | Status |
| ------------- | -------------------------------------------- | ------ |
| **Clientes**  | Criar novo cliente                           | ✅      |
|               | Listar clientes                              | ✅      |
| **Cobranças** | Criar nova cobrança                          | ✅      |
|               | Listar cobranças                             | ✅      |
| **Pix**       | Criar QR Code Pix                            | ✅      |
|               | Consultar status de pagamento do QR Code Pix | ✅      |
| **Cupons**    | Criar novo cupom                             | ✅      |
|               | Listar cupons                                | ✅      |
| **Saques**    | Criar novo saque                             | ✅      |
|               | Buscar saque por ID externo                  | ✅      |
|               | Listar saques                                | ✅      |

---

## Builders

O SDK utiliza o **padrão Builder** para tornar a criação de requisições mais fluente, segura e legível.
Em vez de instanciar objetos manualmente com longas listas de parâmetros, o *builder* permite construir o objeto passo a passo e valida automaticamente campos obrigatórios antes do envio.

### Motivação

* **Legibilidade:** o código descreve claramente o que está sendo criado.
* **Segurança:** evita instâncias inválidas de requisições (campos obrigatórios ausentes).
* **Consistência:** todos os tipos de requisição seguem a mesma interface fluente (`::builder()` + `->build()`).
* **Extensibilidade:** fácil adicionar novos campos ou validações sem quebrar código existente.

### Exemplo comparativo

Sem builder:

```php
$request = new CreatePixQrCodeRequest(
    10000, 3600, 'Pagamento de assinatura',
    new PixCustomerRequest('Maria', '+55...', 'maria@email.com', '123'),
    new PixMetadataRequest('order-abc-123')
);
```

Com builder:

```php
$pixQr = CreatePixQrCodeRequest::builder()
    ->amount(10000)
    ->expiresIn(3600)
    ->description('Pagamento de assinatura')
    ->customer(
        PixCustomerRequest::builder()
            ->name('Maria Oliveira')
            ->cellphone('+5511988887777')
            ->email('maria@email.com')
            ->taxId('12345678900')
            ->build()
    )
    ->metadata(
        PixMetadataRequest::builder()
            ->externalId('order-abc-123')
            ->build()
    )
    ->build();
```

---

## Exemplos de Uso

### Criar Produto e Cobrança

```php
$client = new AbacatePayClient('test_key');

$product = ProductRequest::builder()
    ->externalId('prod-123')
    ->name('Garrafa Térmica 500ml')
    ->description('Mantém bebidas quentes ou frias por até 8h')
    ->quantity(2)
    ->price(8900)
    ->build();

$response = $client->billing()
    ->create(
        CreateBillingRequest::multipleTimes()
            ->creditCard()
            ->completionUrl('https://exemplo.com/sucesso')
            ->returnUrl('https://exemplo.com/retorno')
            ->forCustomerId('cust_abc123')
            ->externalId('billing-001')
            ->addProduct($product)
            ->build()
    );
```

---

### Criar Cliente

```php
$createCustomer = CreateCustomerRequest::builder()
    ->name('João Silva')
    ->cellphone('+5511999999999')
    ->email('joao@email.com')
    ->taxId('12345678900')
    ->build();

$response = $client->customers()->create($createCustomer);
```

---

### Criar QR Code Pix

```php
$pixCustomer = PixCustomerRequest::builder()
    ->name('Maria Oliveira')
    ->cellphone('+5511988887777')
    ->email('maria@email.com')
    ->taxId('12345678900')
    ->build();

$metadata = PixMetadataRequest::builder()
    ->externalId('order-abc-123')
    ->build();

$pixQr = CreatePixQrCodeRequest::builder()
    ->amount(10000)
    ->expiresIn(3600)
    ->description('Pagamento de assinatura')
    ->customer($pixCustomer)
    ->metadata($metadata)
    ->build();

$response = $client->pix()->createQrCode($pixQr);
```

---

### Criar Saque Pix

```php
$pix = WithdrawPixRequest::builder()
    ->type(WithdrawPixTypeEnum::Email)
    ->key('user@email.com')
    ->build();

$request = CreateWithdrawRequest::builder()
    ->externalId('withdraw-001')
    ->method(WithdrawMethodsEnum::Pix)
    ->amount(15000)
    ->pix($pix)
    ->description('Saque de teste')
    ->build();

$response = $client->withdraw()->create($request);
```

---

## Por que usar Builders?

| Benefício             | Descrição                                                                           |
| --------------------- | ----------------------------------------------------------------------------------- |
| **API fluente**       | A construção é expressiva e de fácil leitura.                                       |
| **Validação interna** | Todos os campos obrigatórios são verificados antes da criação.                      |
| **Extensão segura**   | Novos campos podem ser adicionados sem alterar código existente.                    |
| **Coerência**         | Todos os recursos (`Billing`, `Pix`, `Withdraw`, `Customer`) seguem o mesmo padrão. |

### **Lista de Builders Disponíveis**

| Builder                                                                     | Classe gerada            | Descrição                                                            |
| --------------------------------------------------------------------------- | ------------------------ | -------------------------------------------------------------------- |
| `ProductRequest::builder()`                                                 | `ProductRequest`         | Cria produtos utilizados em cobranças.                               |
| `CreateBillingRequest::multipleTimes()` / `CreateBillingRequest::oneTime()` | `CreateBillingRequest`   | Cria cobranças únicas ou recorrentes.                                |
| `CreateCustomerRequest::builder()`                                          | `CreateCustomerRequest`  | Cria novos clientes.                                                 |
| `CustomerRequest::builder()`                                                | `CustomerRequest`        | Representa clientes existentes em cobranças.                         |
| `PixCustomerRequest::builder()`                                             | `PixCustomerRequest`     | Define dados do cliente para pagamentos via Pix.                     |
| `PixMetadataRequest::builder()`                                             | `PixMetadataRequest`     | Define informações adicionais (como `externalId`) para QR Codes Pix. |
| `CreatePixQrCodeRequest::builder()`                                         | `CreatePixQrCodeRequest` | Cria requisições para gerar QR Codes Pix.                            |
| `WithdrawPixRequest::builder()`                                             | `WithdrawPixRequest`     | Define dados da chave Pix para saques.                               |
| `CreateWithdrawRequest::builder()`                                          | `CreateWithdrawRequest`  | Cria requisições para saques via Pix.                                |

---

## Comandos de Desenvolvimento

| Comando       | Descrição                         |
| ------------- | --------------------------------- |
| `make all`    | Executa Pint, Rector e Pest.      |
| `make rector` | Analisa e sugere refatorações.    |
| `make pint`   | Formata automaticamente o código. |
| `make test`   | Executa testes automatizados.     |
| `make fix`    | Corrige código com Pint e Rector. |

---

## Exceções

| Código | Exceção                               | Descrição                  |
| ------ | ------------------------------------- | -------------------------- |
| 401    | `AbacatePayException::unauthorized()` | Token inválido ou ausente. |

---

## Contribuição

1. Faça fork do repositório
2. Crie um branch: `git checkout -b feature/nova-funcionalidade`
3. Rode os testes com Pest:

```bash
make test
```

4. Envie um Pull Request.

---

## Suporte

* Abra uma **issue** no repositório para dúvidas ou bugs
* Suporte técnico: **[ajuda@abacatepay.com](mailto:ajuda@abacatepay.com)**
* Casos urgentes: entre em contato com o **time AbacatePay**
