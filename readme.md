# php challenge

O intuito desse projeto é poder criar contas, e transferir dinheiro entre elas.

---


- A conta pode ter dois tipos: Loja e nâo loja.

- A conta tipo loja, apenas recebe dinheiro.

- A API usada para mockar o envio de confirmação de pagamentos é chamada via job no final de cada transação e tem um tempo de execução fixo de 10 segundos, portanto, é importante que os jobs não estejam rodando de modo sync, ou toda transação levará no mínimo 10s para ser completada.

- As dependências do serviço responsavel por realizar a transação são injetadas pelo framework, e a definição de qual classe será utilizada está localizado em bootstrap/app.php na seção de Container Bindings.

- foi escolhido não usar PHPdoc nas classes e métodos não apenas para evitar poluir demais o codigo com comentários, mas também para seguir a premissa de que caso você precise explicar um código, ele deve estar mal escrito.

---
Os métodos disponíveis para consulta via rest são:
- /user (POST)
    - Cria um novo usuário
    ```json
    {
	"name": "alface",
	"email": "alfa@ce.com",
	"password": "password",
	"taxCode": "1234",
	"store": true/false
    }
    ```
- /user/{id} (GET)
    - lista as Informações envolvendo o usuário consultado
- /user/{id}/transactions (GET)
    - lista as transações envolvendo o usuário consultado (enviando ou recebendo)
- /transaction (POST)
    - Realiza uma transação
    ```json
    {
    "value" : 100.00,
    "payer" : 1,
    "payee" : 2
    }
    ```


### **Lista de Tarefas**
- Usuário
    - create user ok
    - view user ok
- payment
    - make payment ok
    - check history ok
- unit testing
    - services doing
    - repositories doing
    - controller todo
- dockerfile todo
    - from scratch
    - with mysql
    - 
- async jobs todo