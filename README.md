# Start do projeto com o docker

Comando para build das imagens docker:

```
docker-compose build
```

Ou diretamente, para criar e  subir containers docker:

```
docker-compose up -d
```

OBS: Caso tenha algum conflito com a porta local (localhost:8080), altere a porta 8080 para outra, no arquivo docker-compose.yml na linha 19;

### Acessar terminal do container:

```
docker-compose exec app bash
```

### Testes (PHP Unit)

Comando para rodar todos os testes:

**Obs**: certifique-se de que a pasta vendor já tenha sido instalada completamente através do entrypoint (o comando 'docker-compose up -d' aciona o comando 'composer install' no /docker/app/docker-entrypoint.sh).

```
./vendor/bin/phpunit
```

# Carrinho de compras

Repositório com o desafio a ser resolvido pelos candidatos a vaga de _backend_ da _Bussola_.

Um sistema de carrinho de compras online permite que os clientes adicionem itens ao carrinho, escolham a forma de pagamento e visualizem o valor final da compra.

Você precisa desenvolver um módulo que calcule o valor final da compra com base nos itens adicionados ao carrinho e na forma de pagamento.
**Apenas implemente a camada de regras de negócio**, simulando o comportamento esperado com base nas informações fornecidas. **Não é preciso implementar conexão com banco de dados nem endpoints de API.**

## Regras de negócio:

Cada item deve ter:

- nome
- preço
- quantidade.

Deve aceitar uma das seguintes formas de pagamento:

- pagamento via pix (à vista)
- pagamento com cartão de crédito, à vista (1x) ou parcelado (2x a 12x)
- Se a forma de pagamento for cartão de crédito, deve esperar os seguintes dados:
  - Nome do titular do cartão
  - Número do cartão de crédito
  - Data de validade do cartão
  - Código de segurança (CVV)

Se o cliente optar por **pagar à vista**, deve ser aplicado um **desconto de 10% no valor total** da compra.

Se optar por pagar com **cartão de crédito** parcelado, deve ser adicionada uma **taxa de juros de 1% ao mês**.

Utilize a seguinte fórmula para calcular o valor total com juros compostos:

```
  M=P(1+i)^n
  M é o valor final com juros
  P é o principal (o valor inicial)
  i é a taxa de juros por período (mês) em decimal (1% = 0,01)
  n é o número de períodos (meses)
```

## Requisitos:

- Deve retornar o valor final da compra de acordo com a forma de pagamento escolhida, considerando descontos e acréscimos.
- **Deve ser testado** usando PHPUnit ou semelhante.
- Deve ser desenvolvido utilizando **OOP, design patterns e seguir os princípios SOLID**.
- Arquitetura limpa/hexagonal são um **diferencial**
- Certifique-se de que a implementação **seja flexível o suficiente para permitir a inclusão de novas formas de pagamento** no futuro, como pagamento com cartão de débito ou boleto.

Para entregar crie um repositório público e envie para o email que lhe foi encaminhado o teste.

O prazo de entrega está definido no email.

Bom trabalho e boa sorte!
