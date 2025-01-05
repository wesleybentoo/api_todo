# Documentação da API ToDo

**Autor:** Wesley Bento  
**Descrição:** Esta documentação cobre todos os endpoints da API ToDo, incluindo criação, leitura, atualização e exclusão de usuários, categorias, status, tarefas e subtarefas.

## **Índice**
1. [Introdução](#introdução)
2. [Configuração Inicial](#configuração-inicial)
5. [Endpoints](#endpoints)
    - [Usuários](#usuários)
    - [Categorias](#categorias)
    - [Status](#status)
    - [Tarefas](#tarefas)
    - [Subtarefas](#subtarefas)
6. [Logs](#logs)
7. [Boas Práticas de Uso](#boas-práticas-de-uso)
8. [Conclusão](#conclusão)

---

## Introdução

A API ToDo foi desenvolvida para gerenciar tarefas organizadas por categorias, status e subtarefas. Esta documentação detalha todos os endpoints disponíveis.

---

## Endpoints

### **Usuários**
1. **Registrar Usuário**  
   **Método:** `POST`  
   **Endpoint:** `/register`  
   **Body:**
   ```json
   {
       "name": "Wesley Bento",
       "email": "wbentto@gmail.com",
       "password": "Senha12345"
   }

2. **Login**  
   **Método:** `POST`  
   **Endpoint:** `/login`  
   **Body:**
   ```json
   {
       "email": "wbentto@gmail.com",
       "password": "Senha12345"
   }

3. **Listar usuários**  
   **Método:** `GET`  
   **Endpoint:** `/users`  
   **Headers:** `Authorization: Bearer {TOKEN}`
   

4. **Obter usuários por ID**  
   **Método:** `GET`  
   **Endpoint:** `/users/{id}`  
   **Headers:** `Authorization: Bearer {TOKEN}`


5. **Atualizar Usuário**  
   **Método:** `PUT`  
   **Endpoint:** `/users/{id}`  
   **Headers:** `Authorization: Bearer {TOKEN}`
   **Body:**
   ```json
   {
       "name": "Novo Nome",
       "email": "novoemail@gmail.com"
   }

6. **Deletar Usuário**  
   **Método:** `DELETE`  
   **Endpoint:** `/users/{id}`  
   **Headers:** `Authorization: Bearer {TOKEN}`


7. **Logout**  
   **Método:** `POST`  
   **Endpoint:** `/logout`  
   **Headers:** `Authorization: Bearer {TOKEN}`

### **Categorias**
1.  **Listar Categorias**   
    **Método**: `GET`   
    **Endpoint**: `/categories`     
    **Headers:** `Authorization: Bearer {TOKEN}`    


2. **Criar Categoria**  
    **Método:** `POST`   
   **Endpoint:** `/categories`  
   **Headers:** `Authorization: Bearer {TOKEN}`
   **Body:**
   ```json
   {
       "name": "Casa",
       "email": "#FFFFFFFF",
   }

3. **Atualizar Categoria**  
   **Método:** `POST`   
   **Endpoint:** `/categories/{id}`  
   **Headers:** `Authorization: Bearer {TOKEN}`
   **Body:**
   ```json
   {
       "name": "Atualizada",
       "email": "#000000",
   }


4. **Deletar Categoria**  
   **Método:** `DELETE`    
   **Endpoint:** /categories/{id}  
   **Headers:** `Authorization: Bearer {TOKEN}`

### **Status**
1. **Listar Status**    
   **Método:** `GET`    
   **Endpoint:** `/statuses` 
   **Headers:** `Authorization: Bearer {TOKEN}` 


2. **Criar Status** 
   **Método:** `POST`   
   **Endpoint:** `/statuses`    
   **Headers:** `Authorization: Bearer {TOKEN}`     
   **Body:**
   ```json
   {
       "name": "Concluido",
       "description": "Indica tarefa finalizada",
       "color": "#000000",
       "order": 1,
       "finish": true,
   }

3. **Atualizar Status**  
   **Método:** `PUT`  
   **Endpoint:** /statuses/{id}  
   **Headers:** `Authorization: Bearer {TOKEN}`  
   **Body:**
   ```json
   {
       "name": "Concluido",
       "description": "Indica tarefa finalizada",
       "color": "#00FF00",
       "order": 3,
       "finish": true,
   }

4. **Deletar Status**  
   **Método:** `DELETE`  
   **Endpoint:** `/statuses/{id}`
   **Headers:** `Authorization: Bearer {TOKEN}`

### **Tarefas**
1. **Listar Tarefas**  
   **Método:** `GET`  
   **Endpoint:** `/tasks`  
   **Headers:** `Authorization: Bearer {TOKEN}`


2. **Criar Tarefa**  
   **Método:** `POST`  
   **Endpoint:** `/tasks`  
   **Headers:** `Authorization: Bearer {TOKEN}`  
   **Body:**
   ```json
    {
         "name": "Fazer o DBA",
         "description": "Criar a documentação final do projeto.",
         "status_id": 1,
         "category_id": 1,
         "due_date": "2025-01-15"
    }
   

3. **Atualizar Tarefa**  
   **Método:** `PUT`  
   **Endpoint:** `/tasks/{id}`  
   **Headers:** `Authorization: Bearer {TOKEN}`  
   **Body:**
   ```json
    {
         "name": "Fazer o DBA",
         "description": "Criar a documentação final do projeto.",
         "status_id": 2,
         "category_id": 1,
         "due_date": "2025-01-15"
    }

4. **Deletar Tarefa**    
   **Método:** `DELETE`  
   **Endpoint:** `/tasks/{id}`   
   **Headers:** `Authorization: Bearer {TOKEN}`

### **Subtarefas**
1. **Criar Subtarefa**  
   **Método:** `POST`  
   **Endpoint:** `/tasks/{task_id}/subtasks`  
   **Headers:** `Authorization: Bearer {TOKEN}`  
   **Body:**
   ```json
    {
         "title": "Criar cronograma",
         "description": "Definir prazos",
         "status_id": 2
    }

2. **Atualizar Subtarefa**  
   **Método:** `PUT`  
   **Endpoint:** `/tasks/{task_id}/subtasks/{subtask_id}`  
   **Headers:** `Authorization: Bearer {TOKEN}`  
   **Body:**
   ```json
    {
         "title": "Atualizar cronograma",
         "description": "ajustar prazos",
         "status_id": 2
    }

3. **Deletar Subtarefa**    
   **Método:** `DELETE`  
   **Endpoint:** `/tasks/{task_id}/subtasks/{subtask_id}`   
   **Headers:** `Authorization: Bearer {TOKEN}`

   
### **Logs**
**Os logs são criados automaticamente e não possuem endpoints dedicados. Todas as ações realizadas são registradas para fins de auditoria.**
