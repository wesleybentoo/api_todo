# **Planejamento do Banco de Dados**

**Autor:** Wesley Bento

**Descrição:** Este documento descreve o planejamento do banco de dados para a aplicação **To-Do List**. A seguir, apresentamos o Modelo Entidade-Relacionamento (MER), explicamos as tabelas, relacionamentos e as decisões tomadas para atender às funcionalidades propostas.

---

## **Índice**

1. [Modelo Entidade-Relacionamento (MER)](#1-modelo-entidade-relacionamento-mer)
    - [Entidades Principais](#entidades-principais)
2. [Estrutura do Banco de Dados](#2-estrutura-do-banco-de-dados)
    - [Tabela: users](#tabela-users)
    - [Tabela: statuses](#tabela-statuses)
    - [Tabela: tasks](#tabela-tasks)
    - [Tabela: subtasks](#tabela-subtasks)
    - [Tabela: categories](#tabela-categories)
    - [Tabela: activity_logs](#tabela-activity_logs)
    - [Tabela: logs](#tabela-logs)
3. [Relacionamentos entre Tabelas](#3-relacionamentos-entre-tabelas)
4. [Decisões de Design](#4-decisoes-de-design)
    - [4.1 Escalabilidade](#41-escalabilidade)
    - [4.2 Manutenção](#42-manutencao)
    - [4.3 Organização](#43-organizacao)
5. [Conclusão](#5-conclusao)

---

## **1. Modelo Entidade-Relacionamento (MER)**

O MER foi projetado para garantir que todas as funcionalidades do sistema sejam suportadas de forma eficiente e escalável. O diagrama representa as tabelas, seus atributos e os relacionamentos entre elas.

### **Entidades Principais**
- **Usuários:** Armazena informações dos usuários do sistema.
- **Status:** Representa os estados das tarefas e subtarefas, com suporte à ordenação e cores personalizadas.
- **Tarefas:** Representa as tarefas principais criadas pelos usuários.
- **Subtarefas:** Associadas às tarefas, permitem detalhar etapas menores.
- **Categorias:** Permitem organizar tarefas em grupos personalizados, com cores associadas.
- **Histórico de Atividades:** Registra alterações realizadas em tarefas e subtarefas.
- **Logs:** Registra todas as ações do sistema realizadas por usuários.

---

## **2. Estrutura do Banco de Dados**

### **Tabelas e Atributos**
Abaixo, detalhamos cada tabela e sua estrutura:

---

### **Tabela: users**
Armazena informações dos usuários.
- `id`: Chave primária (serial).
- `name`: Nome do usuário.
- `email`: E-mail único.
- `password`: Hash da senha.
- `created_at` e `updated_at`: Auditoria de criação e atualização.
- `deleted_at`: Data de exclusão lógica (SoftDeletes).

---

### **Tabela: statuses**
Define os estados possíveis de tarefas e subtarefas.
- `id`: Chave primária (serial).
- `name`: Nome do status (ex.: pendente, concluído).
- `description`: Detalhes adicionais do status.
- `color`: Cor associada ao status (ex.: #FF5733).
- `order`: Ordem de exibição (não repetível).
- `user_id`: Relaciona o criador do status.
- `is_finalized`: Indica se o status é finalizado.
- `created_at` e `updated_at`: Auditoria de criação e atualização.
- `deleted_at`: Data de exclusão lógica (SoftDeletes).

---

### **Tabela: tasks**
Representa as tarefas criadas pelos usuários.
- `id`: Chave primária (serial).
- `name`: Nome da tarefa.
- `description`: Descrição da tarefa.
- `due_date`: Prazo de conclusão (opcional).
- `status_id`: Relaciona o status da tarefa.
- `user_id`: Relaciona o criador da tarefa.
- `category_id`: Relaciona a categoria da tarefa (opcional).
- `created_at` e `updated_at`: Auditoria de criação e atualização.
- `deleted_at`: Data de exclusão lógica (SoftDeletes).

---

### **Tabela: subtasks**
Representa subtarefas associadas a tarefas principais.
- `id`: Chave primária (serial).
- `title`: Título da subtarefa.
- `description`: Descrição detalhada.
- `status_id`: Relaciona o status da subtarefa.
- `task_id`: Relaciona a tarefa principal.
- `created_at` e `updated_at`: Auditoria de criação e atualização.
- `deleted_at`: Data de exclusão lógica (SoftDeletes).

---

### **Tabela: categories**
Permite organizar tarefas em grupos.
- `id`: Chave primária (serial).
- `name`: Nome da categoria.
- `color`: Cor associada à categoria (ex.: #FF5733).
- `user_id`: Relaciona o criador da categoria.
- `created_at` e `updated_at`: Auditoria de criação e atualização.
- `deleted_at`: Data de exclusão lógica (SoftDeletes).

---

### **Tabela: activity_logs**
Registra alterações realizadas em tarefas e subtarefas.
- `id`: Chave primária (serial).
- `task_id`: Relaciona a tarefa (opcional).
- `subtask_id`: Relaciona a subtarefa (opcional).
- `status_previous_id`: Relaciona o status anterior (opcional).
- `status_new_id`: Relaciona o status atualizado.
- `observation`: Observações sobre a alteração (ex.: "Tarefa atualizada").
- `user_id`: Usuário responsável pela alteração.
- `changed_at`: Data e hora da alteração.
- `created_at` e `updated_at`: Auditoria de criação e atualização.
- `deleted_at`: Data de exclusão lógica (SoftDeletes).

---

### **Tabela: logs**
Registra todas as ações realizadas pelos usuários no sistema.
- `id`: Chave primária (serial).
- `user_id`: Relaciona o usuário responsável pela ação.
- `action`: Descrição da ação (ex.: "Tarefa criada").
- `endpoint`: Rota do sistema utilizada (ex.: "/api/tasks").
- `details`: Detalhes adicionais sobre a ação.
- `created_at` e `updated_at`: Auditoria de criação e atualização.
- `deleted_at`: Data de exclusão lógica (SoftDeletes).

---

## **3. Relacionamentos entre Tabelas**

Os relacionamentos foram definidos para garantir a consistência e a integridade dos dados:

- **Usuários** possuem muitas **Tarefas**, **Categorias**, **Status**, **Subtarefas** e **Histórico de Atividades**.
- **Tarefas** pertencem a um **Usuário**, têm uma **Categoria** e podem conter muitas **Subtarefas** e **Histórico de Atividades**.
- **Subtarefas** pertencem a uma única **Tarefa** e podem ter múltiplos registros no **Histórico de Atividades**.
- **Status** está associado a **Tarefas**, **Subtarefas** e **Histórico de Atividades**.
- **Categorias** organizam várias **Tarefas**.

---

## **4. Decisões de Design**

### **4.1 Escalabilidade**
- O uso de chaves estrangeiras e índices garante desempenho em consultas complexas e um design otimizado para expansão futura.

### **4.2 Manutenção**
- A tabela `activity_logs` facilita a auditoria e o rastreamento de alterações realizadas no sistema.
- A tabela `logs` registra todas as ações realizadas pelos usuários para facilitar a auditoria geral.

### **4.3 Organização**
- O uso de **Categorias** e **Status** torna o sistema mais intuitivo e personalizável para os usuários.
- A organização hierárquica entre **Tarefas** e **Subtarefas** melhora a experiência do usuário.

---

## **5. Conclusão**

O banco de dados foi planejado para suportar todas as funcionalidades propostas, incluindo o gerenciamento de tarefas, subtarefas, histórico de alterações e organização por categorias. O design garante escalabilidade, consistência e eficiência no armazenamento e recuperação de dados.

**Autor:** Wesley Bento
