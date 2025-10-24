# AbacatePay PHP SDK

SDK para integração com a **API AbacatePay**.
Permite gerenciar clientes, cobranças e transações de forma simples usando PHP.

---
## Requisitos

- PHP 8.4 ou maior
- Composer
- Conta na AbacatePay
- API Token

## Instalação

```bash
composer require abacatepay/client-php
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

## Inicialização

```php
use AbacatePay\AbacatePayClient;

$sdk = new AbacatePayClient('seu-token-aqui');
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
  composer test
```
4. Envie um Pull Request

---
