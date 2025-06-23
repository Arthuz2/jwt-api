# 🧠 JWT API

API RESTful desenvolvida com Symfony, JWT e Paginator para gerenciamento de cursos, lições e progresso do usuário, seguindo princípios de Clean Architecture.

---

## 👨‍💻 Autor

-   [LinkedIn](https://www.linkedin.com/in/arthurporcino)
-   [GitHub](https://github.com/Arthuz2)

---

## ⚙️ Tecnologias

**Back-end:** Symfony 7, PHP 8.3, Doctrine ORM  
**Autenticação:** LexikJWTAuthenticationBundle (JWT)  
**Banco de dados:** MySQL com UUID  
**Arquitetura:** Clean Architecture (Services, DTOs, Exceptions)

---

## 🚀 Instalação

Clone o projeto:

```bash
git clone https://github.com/Arthuz2/jwt-api.git
cd jwt-api
```

Instale as dependências:

```bash
composer install
```

Copie o `.env` e configure o banco:

```bash
cp .env .env.local
# Edite DATABASE_URL e JWT_PASSPHRASE
```

Crie o banco e rode as migrations:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Gere o par de chaves do JWT:

```bash
php bin/console lexik:jwt:generate-keypair
```

Inicie o servidor:

```bash
php -S 127.0.0.1:8000 -t public/index.php

# ou

symfony server:start
```

---

## 🛠️ Headers necessários

-   Para **todas as requisições com JSON**:

```http
Content-Type: application/json
```

-   Para **rotas protegidas**:

```http
Authorization: Bearer {SEU_TOKEN_JWT}
```

---

## 🌐 URL Base

```
http://localhost:8000/api
```

---

## 🔐 Autenticação

### 📌 Registrar usuário

```http
POST /auth/register
```

```json
{
    "name": "Arthur",
    "email": "arthur@email.com",
    "password": "123456"
}
```

---

### 📌 Login

```http
POST /auth/login
```

```json
{
    "email": "arthur@email.com",
    "password": "123456"
}
```

**Resposta:**

```json
{
    "token": "jwt_token_aqui",
    "user": {
        "id": "uuid",
        "name": "Arthur",
        "email": "arthur@email.com"
    }
}
```

---

### 📌 Obter usuário autenticado

```http
GET /auth/me
Authorization: Bearer {jwt_token}
```

---

## 📘 Cursos (Courses)

### 📌 Criar curso

```http
POST /course
Authorization: Bearer {jwt_token}
```

```json
{
    "title": "Curso de Matemática",
    "description": "Aprenda matemática básica"
}
```

---

### 📌 Listar cursos

```http
GET /courses?page=1
Authorization: Bearer {jwt_token}
```

---

### 📌 Detalhar curso

```http
GET /course/{id}
Authorization: Bearer {jwt_token}
```

---

### 📌 Atualizar curso

```http
PUT /course/{id}
Authorization: Bearer {jwt_token}
```

```json
{
    "title": "Novo Título",
    "description": "Nova descrição"
}
```

---

### 📌 Remover curso

```http
DELETE /courses/{id}
Authorization: Bearer {jwt_token}
```

---

## 📚 Lições (Lessons)

### 📌 Criar lição para um curso

```http
POST /lessons
Authorization: Bearer {jwt_token}
```

```json
{
    "title": "Introdução à Álgebra",
    "content": "Conteúdo da lição",
    "position": 1,
    "courseId": "uuid"
}
```

---

### 📌 Ver todas as lições

```http
GET /lessons?page=1
Authorization: Bearer {jwt_token}
```

---

### 📌 Ver lição

```http
GET /lesson/{id}
Authorization: Bearer {jwt_token}
```

---

### 📌 Atualizar lição

```http
PUT /lesson/{id}
Authorization: Bearer {jwt_token}
```

```json
{
    "title": "Lição atualizada",
    "content": "Novo conteúdo",
    "position": 1,
    "course": "uuid"
}
```

---

### 📌 Remover lição

```http
DELETE /lessons/{id}
Authorization: Bearer {jwt_token}
```

---

## ✅ Progresso (Progress)

### 📌 Marcar lição como concluída

```http
POST /lessons/{lessonId}/progress
Authorization: Bearer {jwt_token}
```

---

### 📌 Listar progresso do usuário autenticado

```http
GET /progress?page=1
Authorization: Bearer {jwt_token}
```

---

## ✅ Tabela-resumo dos endpoints

| Método | Rota                         | Descrição                          |
| ------ | ---------------------------- | ---------------------------------- |
| POST   | /auth/register               | Registrar novo usuário             |
| POST   | /auth/login                  | Fazer login e obter token          |
| GET    | /auth/me                     | Obter dados do usuário autenticado |
| POST   | /course                      | Criar novo curso                   |
| GET    | /courses?page=1              | Listar cursos                      |
| GET    | /course/{id}                 | Ver curso específico               |
| PUT    | /course/{id}                 | Atualizar curso                    |
| DELETE | /courses/{id}                | Deletar curso                      |
| POST   | /lessons/                    | Criar lição para o curso           |
| GET    | /lessons?page=1              | Listar lições                      |
| GET    | /lesson/{id}                 | Ver lição                          |
| PUT    | /lessons/{id}                | Atualizar lição                    |
| DELETE | /lessons/{id}                | Remover lição                      |
| POST   | /lessons/{lessonId}/progress | Marcar lição como concluída        |
| GET    | /progress                    | Listar progresso do usuário        |

---

## Feito por: Arthur Porcino Pereira
