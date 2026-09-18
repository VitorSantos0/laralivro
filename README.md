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

## Implantação com Docker (Laravel Sail)

Pré-requisito: Docker instalado e rodando (no Windows, Docker Desktop com integração WSL habilitada para a distro em uso).

```bash
# 1. Instalar as dependências PHP (não precisa de PHP instalado na máquina host)
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html \
  laravelsail/php84-composer:latest composer install

# 2. Copiar o arquivo de variáveis de ambiente
cp .env.example .env

# 3. Subir os containers (app + PostgreSQL)
./vendor/bin/sail up -d

# 4. Gerar a chave da aplicação
./vendor/bin/sail artisan key:generate

# 5. Rodar as migrations (cria as tabelas autor, livro, assunto,
#    livro_autor, livro_assunto e a view vw_relatorio_livros_por_autor)
./vendor/bin/sail artisan migrate

# 6. (Opcional) Popular dados de exemplo
./vendor/bin/sail artisan db:seed

# 7. Instalar dependências JS e buildar os assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

A aplicação estará disponível em `http://localhost`.

### Scripts do dia a dia

```bash
./vendor/bin/sail up -d              # subir os containers em background
./vendor/bin/sail down               # derrubar os containers
./vendor/bin/sail down -v            # derrubar e apagar o volume do banco (reset total)
./vendor/bin/sail logs -f            # acompanhar logs em tempo real
./vendor/bin/sail artisan migrate:fresh --seed   # resetar o banco com as seeders
./vendor/bin/sail npm run dev        # Vite com hot-reload (porta 5173)
./vendor/bin/sail composer <cmd>     # rodar composer dentro do container
```

## Rodando os testes

O projeto tem duas suítes com propósitos diferentes:

- **`tests/Unit`** (Controllers e Services): mockam `*ServiceInterface`/`*RepositoryInterface` com Mockery — não usam `RefreshDatabase` nem precisam de Postgres rodando. Isso só é possível porque Controllers e Services dependem de interfaces (DIP), então a implementação real é trocada por um dublê de teste no container. `sail artisan test --testsuite=Unit` roda em menos de 1s.
- **`tests/Feature`** (`*FeatureTest.php` e `*RelationshipTest.php`): testes de integração ponta a ponta, usam `RefreshDatabase` contra o **mesmo banco PostgreSQL** configurado no `.env`. Cobrem o que fica fora das camadas Service/Repository e não dá para mockar: a regra de validação `exists:` do `LivroRequest` (consulta real ao banco) e o código de erro `23503` do Postgres na exclusão protegida.

```bash
./vendor/bin/sail artisan test
# ou
./vendor/bin/sail composer test

# só a suíte sem banco
./vendor/bin/sail artisan test --testsuite=Unit
```

## Funcionalidades

- **CRUD completo** de Livro, Autor e Assunto (Bootstrap, validação server-side com mensagens em pt-BR, checkboxes para seleção de autores/assuntos do livro, máscara de moeda em tempo real no campo Valor).
- **Exclusão protegida**: tentar excluir um Autor ou Assunto vinculado a algum Livro é bloqueado com uma mensagem específica
- **Relatório "Livros por Autor"** (`/relatorios`): consulta a view SQL `vw_relatorio_livros_por_autor` agrupando os resultados por autor