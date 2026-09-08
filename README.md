# LaraLivro — Gerenciamento de Livraria

Aplicação Laravel para cadastro de **Livros**, **Autores** e **Assuntos** construído de forma incremental e orientada a testes.

## Stack

- PHP ^8.2
- Laravel 12
- PostgreSQL
- Bootstrap 5.3.3
- Laravel Dompdf
- PHPUnit

## Pré-requisitos

- PHP 8.2 ou superior
- Composer 2.x
- PostgreSQL

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

### Resetar o banco do zero

```bash
php artisan migrate:fresh --seed
```

## Rodando os testes

Os testes de Feature usam `RefreshDatabase` e rodam contra o **mesmo banco PostgreSQL** configurado no `.env` (a suíte migra e limpa as tabelas automaticamente a cada execução — não é necessário um banco `sqlite` separado).

```bash
php artisan test
# ou
composer test
```

Suíte atual: 45 testes de Feature cobrindo CRUD de Livro/Autor/Assunto, regras de validação, bloqueio de exclusão de Autor/Assunto vinculado a Livro, relacionamento N:N, e o relatório (tela HTML e exportação em PDF).

## Estrutura principal do projeto

```
app/
  Http/Controllers/     # AutorController, AssuntoController, LivroController, RelatorioController
  Http/Requests/        # AutorRequest, AssuntoRequest, LivroRequest (validação)
  Services/             # AutorService, AssuntoService, LivroService, RelatorioService (regras de negócio)
  Models/                # Autor, Assunto, Livro, LivroAutor, LivroAssunto
  Exceptions/            # RegistroVinculadoException (violação de FK 23503 tratada especificamente)
database/
  migrations/            # schema das tabelas + criação da view vw_relatorio_livros_por_autor
  seeders/               # dados de exemplo (autores, assuntos, livros já relacionados)
resources/views/
  livros/, autores/, assuntos/   # CRUD de cada entidade
  relatorios/                     # tela do relatório + view PDF-friendly
tests/Feature/           # testes de Feature (TDD), incluindo tests/Feature/Relatorio
```

## Funcionalidades

- **CRUD completo** de Livro, Autor e Assunto (Bootstrap, validação server-side com mensagens em pt-BR, checkboxes para seleção de autores/assuntos do livro, máscara de moeda em tempo real no campo Valor).
- **Exclusão protegida**: tentar excluir um Autor ou Assunto vinculado a algum Livro é bloqueado com uma mensagem específica (`RegistroVinculadoException`, tratando o erro `23503` do PostgreSQL — violação de chave estrangeira — sem usar `catch` genérico).
- **Relatório "Livros por Autor"** (`/relatorios`): consulta a view SQL `vw_relatorio_livros_por_autor` (criada via migration), agrupando os resultados por autor — um livro com múltiplos autores aparece uma vez em cada grupo. Disponível como tela HTML e como PDF (`/relatorios/livros-por-autor/pdf`).

## Rotas principais

| Rota | Descrição |
|---|---|
| `/` | Página inicial com acesso às telas |
| `/livros`, `/autores`, `/assuntos` | CRUD de cada entidade |
| `/relatorios` | Lista de relatórios disponíveis |
| `/relatorios/livros-por-autor` | Relatório de livros agrupados por autor |
| `/relatorios/livros-por-autor/pdf` | Exportação do relatório em PDF |
