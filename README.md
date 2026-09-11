# LaraLivro — Gerenciamento de Livraria

Projeto de cadastro de livros desenvolvido em Laravel + PostgreSQL, com CRUD completo de Livros, Autores e Assuntos, cobertura de testes em camadas (unidade + integração), arquitetura em camadas (Controller → Service → Repository) orientada pelos princípios SOLID.

## Stack

- PHP ^8.2
- Laravel 12
- PostgreSQL
- Bootstrap 5.3.3
- Laravel Dompdf
- PHPUnit

## Arquitetura

O projeto segue uma arquitetura em camadas, todas ligadas por interface:

```
Controller → ServiceInterface → RepositoryInterface → Eloquent Model
```

- **Controllers** (`app/Http/Controllers`) só orquestram request → service → resposta HTTP. Não acessam Eloquent nem a facade `DB` diretamente.
- **Services** (`app/Services`) concentram a regra de negócio: transação, tradução de erro de FK do Postgres (`QueryException` código `23503`) em `RegistroVinculadoException`, e orquestração de operações compostas (ex.: criar um Livro e sincronizar autores/assuntos na mesma transação). A classe abstrata `Service` centraliza o helper `transactional()`, reaproveitado por `AutorService`, `AssuntoService` e `LivroService`.
- **Repositories** (`app/Repositories`, implementações em `app/Repositories/Eloquent`) isolam o acesso a dados — são as únicas classes que falam diretamente com o Eloquent.
- Toda dependência entre camadas é injetada por construtor via **interface**, resolvida no container em `app/Providers/AppServiceProvider.php`. Não há `new` de colaboradores nem chamada estática de Model dentro de Controller/Service.

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

O projeto tem duas suítes com propósitos diferentes:

- **`tests/Unit`** (Controllers e Services): mockam `*ServiceInterface`/`*RepositoryInterface` com Mockery — não usam `RefreshDatabase` nem precisam de Postgres rodando. Isso só é possível porque Controllers e Services dependem de interfaces (DIP), então a implementação real é trocada por um dublê de teste no container. `php artisan test --testsuite=Unit` roda em menos de 1s.
- **`tests/Feature`** (`*FeatureTest.php` e `*RelationshipTest.php`): testes de integração ponta a ponta, usam `RefreshDatabase` contra o **mesmo banco PostgreSQL** configurado no `.env`. Cobrem o que fica fora das camadas Service/Repository e não dá para mockar: a regra de validação `exists:` do `LivroRequest` (consulta real ao banco) e o código de erro `23503` do Postgres na exclusão protegida.

```bash
php artisan test
# ou
composer test

# só a suíte sem banco
php artisan test --testsuite=Unit
```

## Funcionalidades

- **CRUD completo** de Livro, Autor e Assunto (Bootstrap, validação server-side com mensagens em pt-BR, checkboxes para seleção de autores/assuntos do livro, máscara de moeda em tempo real no campo Valor).
- **Exclusão protegida**: tentar excluir um Autor ou Assunto vinculado a algum Livro é bloqueado com uma mensagem específica
- **Relatório "Livros por Autor"** (`/relatorios`): consulta a view SQL `vw_relatorio_livros_por_autor` agrupando os resultados por autor