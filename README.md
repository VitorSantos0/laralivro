# LaraLivro — Gerenciamento de Livraria

Projeto de cadastro de livros desenvolvido em Laravel + PostgreSQL, com CRUD completo de Livros, Autores e Assuntos, aplicando TDD, arquitetura em camadas MVC e relatório gerencial agrupado por autor.

## Stack

- PHP ^8.2
- Laravel 12
- PostgreSQL
- Bootstrap 5.3.3
- Laravel Dompdf
- PHPUnit

## Passo a passo para implantação

```bash
# 1. Instalar as dependências PHP
composer install

# 2. Copiar o arquivo de variáveis de ambiente
cp .env.example .env

# 3. Gerar a chave da aplicação
php artisan key:generate

# 4. Configurar o acesso ao PostgreSQL no .env
#    DB_CONNECTION=pgsql
#    DB_HOST=127.0.0.1
#    DB_PORT=5432
#    DB_DATABASE=livraria
#    DB_USERNAME=postgres
#    DB_PASSWORD=postgres
#    (crie o banco "livraria" antes de migrar, caso ainda não exista)

# 5. Rodar as migrations (cria as tabelas autor, livro, assunto,
#    livro_autor, livro_assunto e a view vw_relatorio_livros_por_autor)
php artisan migrate

# 6. (Opcional) Popular dados de exemplo
php artisan db:seed

# 7. Subir o servidor de desenvolvimento
php artisan serve
```

A aplicação estará disponível em `http://127.0.0.1:8000`.

### Resetar o banco com as seeders

```bash
php artisan migrate:fresh --seed
```

## Rodando os testes

Os testes de Feature usam `RefreshDatabase` e rodam contra o **mesmo banco PostgreSQL** configurado no `.env`.

```bash
php artisan test
# ou
composer test
```

## Funcionalidades

- **CRUD completo** de Livro, Autor e Assunto (Bootstrap, validação server-side com mensagens em pt-BR, checkboxes para seleção de autores/assuntos do livro, máscara de moeda em tempo real no campo Valor).
- **Exclusão protegida**: tentar excluir um Autor ou Assunto vinculado a algum Livro é bloqueado com uma mensagem específica
- **Relatório "Livros por Autor"** (`/relatorios`): consulta a view SQL `vw_relatorio_livros_por_autor` agrupando os resultados por autor