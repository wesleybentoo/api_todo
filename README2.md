# Documentação da API ToDo

4. **Deletar Categoria**
   **Método:** `DELETE`    
**Endpoint:** /categories/{id}  
**Headers:** `Authorization: Bearer {TOKEN}`    


Status
Listar Status
**Método:** `GET`
**Endpoint:** /statuses
**Headers:** `Authorization: Bearer {TOKEN}`


Criar Status
**Método:** `POST`
**Endpoint:** /statuses
**Headers:** `Authorization: Bearer {TOKEN}`


Body:

json
Copiar código
{
"name": "Em Progresso",
"description": "Este status indica que a tarefa está sendo realizada.",
"color": "#FF5733",
"order": 1
}
Atualizar Status
**Método:** `PUT`
**Endpoint:** /statuses/{id}
**Headers:** `Authorization: Bearer {TOKEN}`


Body:

json
Copiar código
{
"name": "Concluído",
"description": "Indica tarefa finalizada.",
"color": "#00FF00",
"order": 3
}
Deletar Status
**Método:** `DELETE`
**Endpoint:** /statuses/{id}
**Headers:** `Authorization: Bearer {TOKEN}`


Tarefas
Listar Tarefas
**Método:** `GET`
**Endpoint:** /tasks
**Headers:** `Authorization: Bearer {TOKEN}`


Criar Tarefa
**Método:** `POST`
**Endpoint:** /tasks
**Headers:** `Authorization: Bearer {TOKEN}`


Body:

json
Copiar código
{
"name": "Fazer o DBA",
"description": "Criar a documentação final do projeto.",
"status_id": 1,
"category_id": 1,
"due_date": "2025-01-15"
}
Atualizar Tarefa
**Método:** `PUT`
**Endpoint:** /tasks/{id}
**Headers:** `Authorization: Bearer {TOKEN}`


Body:

json
Copiar código
{
"name": "Atualizar tarefa",
"description": "Alterar lógica de autenticação.",
"status_id": 2,
"category_id": 3,
"due_date": "2025-02-01"
}
Deletar Tarefa
**Método:** `DELETE`
**Endpoint:** /tasks/{id}
**Headers:** `Authorization: Bearer {TOKEN}`


Subtarefas
Criar Subtarefa
**Método:** `POST`
**Endpoint:** /tasks/{task_id}/subtasks
**Headers:** `Authorization: Bearer {TOKEN}`


Body:

json
Copiar código
{
"title": "Criar cronograma",
"description": "Definir prazos",
"status_id": 1
}
Atualizar Subtarefa
**Método:** `PUT`
**Endpoint:** /tasks/{task_id}/subtasks/{subtask_id}
**Headers:** `Authorization: Bearer {TOKEN}`


Body:

json
Copiar código
{
"title": "Atualizar cronograma",
"description": "Ajustar prazos",
"status_id": 2
}
Deletar Subtarefa
**Método:** `DELETE`
**Endpoint:** /tasks/{task_id}/subtasks/{subtask_id}
**Headers:** `Authorization: Bearer {TOKEN}`


Logs
Os logs são criados automaticamente e não possuem endpoints dedicados. Todas as ações realizadas são registradas para fins de auditoria.
