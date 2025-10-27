# URL Shortener API

API REST para encurtamento de URLs com tracking de clicks.

## 📋 Endpoints Disponíveis

Base URL: `http://localhost`

### 1. Criar URL Encurtada

Cria uma nova URL encurtada a partir de uma URL original.

**Endpoint:** `POST /api/urls`

**Request Body:**
```json
{
  "original_url": "https://example.com"
}
```

**Response:** `201 Created`
```json
{
  "original_url": "https://example.com",
  "short_url": "http://localhost/s/abc123",
  "clicks": 0
}
```

**Validações:**
- `original_url` deve ser uma URL válida (http/https)
- URL já encurtada retorna `422 Unprocessable Entity`

**Rate Limit:** 60 requisições por minuto

---

### 2. Listar URLs Encurtadas

Lista todas as URLs encurtadas com paginação e filtros.

**Endpoint:** `GET /api/urls`

**Query Parameters:**
- `original_url` (opcional): Filtra por URL original
- `per_page` (opcional): Itens por página (padrão: 15)
- `page` (opcional): Número da página

**Exemplos:**
```bash
# Listar todas
GET /api/urls

# Com filtro
GET /api/urls?original_url=example.com

# Com paginação
GET /api/urls?per_page=10&page=2
```

**Response:** `200 OK`
```json
{
  "data": [
    {
      "original_url": "https://example.com",
      "short_url": "http://localhost/s/abc123",
      "clicks": 5
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 10,
    "per_page": 15
  }
}
```

**Rate Limit:** 60 requisições por minuto

---

### 3. Redirecionar para URL Original

Redireciona para a URL original através do código encurtado.

**Endpoint:** `GET /s/{code}`

**Exemplo:**
```bash
GET /s/abc123
```

**Response:** `301 Moved Permanently`
- Redireciona automaticamente para a URL original
- Incrementa o contador de clicks

**Erro - Código não encontrado:** `404 Not Found`
```json
{
  "message": "Short URL not found"
}
```

**Rate Limit:** 60 requisições por minuto

---

## Exemplos de Uso

### cURL

**Criar URL encurtada:**
```bash
curl -X POST http://localhost/api/urls \
  -H "Content-Type: application/json" \
  -d '{"original_url": "https://google.com"}'
```

**Listar URLs:**
```bash
curl http://localhost/api/urls
```

**Acessar URL encurtada:**
```bash
curl -L http://localhost/s/abc123
```
