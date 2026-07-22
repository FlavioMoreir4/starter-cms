# StarterCms

CMS completo construído com Laravel 13, Filament v5, Inertia v3 e Vue 3.

## Visão Geral

Um CMS modular com admin panel potente, frontend SPA via Inertia, e arquitetura baseada em Domínios. Inclui módulos para Blog, Portfólio, Páginas com blocos de conteúdo dinâmicos, Formulários com schema-driven rendering, e Configurações do site.

## Tech Stack

| Camada | Ferramenta | Versão |
|--------|-----------|--------|
| Backend | Laravel | 13.x |
| PHP | PHP | ^8.3 |
| Admin Panel | Filament (Livewire) | v5 (v4) |
| Frontend SPA | Inertia + Vue | v3 + 3.5 |
| UI | Tailwind CSS + shadcn-vue | v4 |
| SEO | ralphjsmit/laravel-seo + spatie/schema-org | ^1.8 / ^4.0 |
| Mídia | spatie/laravel-medialibrary | ^11.23 |
| RBAC | spatie/laravel-permission | ^8.3 |
| Settings | spatie/laravel-settings | ^3.9 |
| Testes | Pest | v4 |
| Análise Estática | Larastan (PHPStan) | v3 (level 8) |
| Code Style | Laravel Pint + ESLint + Prettier | v1 / v9 / v3 |
| Refactoring | Rector | v2 |
| Bundler | Vite | v8 |
| Types | TypeScript + vue-tsc | v5 |

## Arquitetura

### DDD Pragmático

O projeto usa um padrão de Domain-Driven Design simplificado, sem a complexidade de camadas Application/Infrastructure. A separação é feita por responsabilidade:

```
app/
├── Domain/                         # Lógica de negócio
│   ├── Content/                    # Blog (Posts, Categories, Tags)
│   │   ├── Actions/                # Ações invokable
│   │   ├── Data/                   # DTOs
│   │   ├── Models/                 # Eloquent models (Post, Category, Tag)
│   │   └── Enums/                  # Enums do módulo
│   ├── Portfolio/                  # Portfólio (Projects, Technologies)
│   │   ├── Actions/
│   │   ├── Data/
│   │   ├── Models/                 # Project, Technology
│   │   └── Enums/
│   ├── Pages/                      # Páginas com blocos de conteúdo
│   │   ├── Actions/
│   │   │   └── Blocks/             # Classes de bloco (Filament Builder)
│   │   ├── Data/
│   │   ├── Models/                 # Page
│   │   └── Enums/
│   ├── Forms/                      # Formulários dinâmicos
│   │   ├── Actions/
│   │   │   └── ConvertSchemaToFilamentAction.php
│   │   ├── Data/
│   │   ├── Models/                 # Form, FormSubmission
│   │   └── Enums/
│   └── Settings/                   # Configurações do site
│       ├── Actions/
│       ├── Data/
│       └── Models/
├── Filament/                       # Admin panel (100% CRUD)
│   ├── Content/
│   │   ├── Resources/              # PostResource, CategoryResource, TagResource
│   │   └── Widgets/
│   ├── Portfolio/
│   │   └── Resources/              # ProjectResource
│   ├── Pages/
│   │   └── Resources/
│   ├── Forms/
│   │   └── Resources/              # FormResource
│   ├── Resources/                  # PageResource
│   └── Settings/
│       └── Pages/                  # ManageSiteSettings
├── Http/
│   └── Controllers/
│       └── Public/                 # Controllers para frontend (Inertia)
│           ├── HomeController.php
│           ├── BlogController.php
│           ├── PageController.php
│           ├── PortfolioController.php
│           └── FormController.php
└── Enums/                          # Enums compartilhados (se houver)
```

### Princípios da Arquitetura

- **Domain/**: Toda lógica de negócio vive aqui. Cada módulo é independente com seu próprio Actions/, Data/, Models/ e Enums/.
- **Filament/**: Resources do admin panel. Cada resource é 100% CRUD, sem lógica de negócio.
- **Http/Controllers/Public/**: Controllers para o frontend público. Somente leitura, exceto FormController que processa envios.
- **Sem repositórios**: Usa Eloquent diretamente com scopes nos models.
- **Sem Application/Infrastructure**: Padrão pragmático. Menos boilerplate, mais produtividade.
- **Models com casts**: O campo `content` da Page é castado para `array`, permitindo blocos de conteúdo estruturados em JSON.

## Blocos de Conteúdo

O sistema de blocos permite construir páginas com seções reutilizáveis. Cada bloco tem uma classe PHP no backend (schema do Filament) e um componente Vue no frontend.

### Blocos Disponíveis

| Bloco | Tipo | Descrição |
|-------|------|-----------|
| HeroBlock | `hero` | Hero section com título, subtítulo, imagem de fundo e botão |
| RichTextBlock | `rich_text` | Texto rico com formatação |
| GalleryBlock | `gallery` | Galeria de imagens |
| FeaturesBlock | `features` | Lista de características |
| TestimonialsBlock | `testimonials` | Depoimentos |
| CTABlock | `cta` | Call to action |
| FAQBlock | `faq` | Perguntas frequentes |
| ContactFormBlock | `contact_form` | Formulário de contato embutido |
| FooterBlock | `footer` | Bloco de rodapé |

### Como Adicionar um Novo Bloco

**Passo 1**: Criar a classe PHP do bloco em `app/Domain/Pages/Actions/Blocks/`:

```php
<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class MeuBloco extends Block
{
    public static function type(): string
    {
        return 'meu_bloco';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            TextInput::make('titulo')
                ->label('Título')
                ->required(),
            TextInput::make('descricao')
                ->label('Descrição'),
        ];
    }
}
```

**Passo 2**: Criar o componente Vue em `resources/js/components/blocks/MeuBloco.vue`:

```vue
<script setup lang="ts">
defineProps<{ data: Record<string, unknown> }>();
</script>

<template>
    <section class="py-12">
        <h2 class="text-3xl font-bold">{{ data.titulo }}</h2>
        <p v-if="data.descricao" class="mt-4 text-lg">{{ data.descricao }}</p>
    </section>
</template>
```

**Passo 3**: Registrar o componente no `BlockRenderer.vue` (`resources/js/components/BlockRenderer.vue`):

```typescript
import MeuBloco from '@/components/blocks/MeuBloco.vue';

const blockComponents: Record<string, Component> = {
    // ... blocos existentes
    meu_bloco: MeuBloco,
};
```

**Passo 4**: Adicionar o bloco no `PageResource.php` (`app/Filament/Resources/PageResource.php`):

```php
use App\Domain\Pages\Actions\Blocks\MeuBloco;

// Dentro do método form():
Builder::make('content')->blocks([
    HeroBlock::class,
    // ... outros blocos
    MeuBloco::class,
])
```

### Estrutura de uma Classe de Bloco

Toda classe de bloco herda de `App\Domain\Pages\Actions\Blocks\Block` e implementa dois métodos obrigatórios:

- `type(): string` — identificador único do bloco (snake_case)
- `getFilamentSchema(): array` — array de componentes Filament para o formulário do admin

O método `getLabel()` retorna o nome da classe por padrão, mas pode ser sobrescrito.

O método estático `toFilamentBlock()` converte automaticamente para um `Filament\Forms\Components\Builder\Block` pronto para uso.

## Formulários Dinâmicos

O sistema de formulários usa JSON Schema para definir campos, que são renderizados tanto no admin (Filament) quanto no frontend (Vue).

### Tipos de Campo Suportados

| Tipo | Schema | Componente Filament | Componente Vue |
|------|--------|-------------------|----------------|
| Texto | `type: string` | `TextInput` | `Input` |
| Textarea | `x-filament.type: textarea` | `Textarea` | `<textarea>` |
| Número | `type: number/integer` | `TextInput::numeric()` | `Input[type=number]` |
| Checkbox | `type: boolean` | `Checkbox` | `Checkbox` |
| Select | `x-filament.type: select` | `Select` | `Select` |
| Radio | `x-filament.type: radio` | `Radio` | `<input[type=radio]>` |
| Data | `x-filament.type: date` | `DatePicker` | `Input[type=date]` |

### Como Adicionar um Novo Tipo de Campo

**Passo 1**: Adicionar o tipo em `ConvertSchemaToFilamentAction.php` (`app/Domain/Forms/Actions/ConvertSchemaToFilamentAction.php`):

```php
case 'novo_tipo':
    return NovoComponent::make($name)->label($label);
```

**Passo 2**: Adicionar a renderização no `DynamicForm.vue` (`resources/js/components/DynamicForm.vue`):

```vue
<template v-else-if="getFieldType(field) === 'novo_tipo'">
    <Label>{{ field.title || name }}</Label>
    <NovoComponent v-model="form.payload[name]" />
</template>
```

### Exemplo de JSON Schema

```json
{
    "type": "object",
    "properties": {
        "nome": {
            "type": "string",
            "title": "Seu Nome"
        },
        "mensagem": {
            "type": "string",
            "title": "Mensagem",
            "x-filament": {
                "type": "textarea",
                "placeholder": "Digite sua mensagem..."
            }
        },
        "assunto": {
            "type": "string",
            "title": "Assunto",
            "x-filament": {
                "type": "select",
                "options": {
                    "suporte": "Suporte",
                    "vendas": "Vendas"
                }
            }
        }
    },
    "required": ["nome", "mensagem"]
}
```

## Estrutura de Páginas Vue

```
resources/js/pages/
├── Home.vue                     # Página inicial
├── Page.vue                     # Lista de páginas
├── Dashboard.vue                # Dashboard do usuário
├── Page/
│   └── Show.vue                 # Exibição de página com blocos
├── Blog/
│   ├── Index.vue                # Lista de posts
│   └── Show.vue                 # Exibição de post
├── Portfolio/
│   ├── Index.vue                # Lista de projetos
│   └── Show.vue                 # Exibição de projeto
├── Forms/
│   └── Show.vue                 # Exibição de formulário dinâmico
├── auth/
│   ├── Login.vue
│   ├── Register.vue
│   ├── ForgotPassword.vue
│   ├── ResetPassword.vue
│   ├── VerifyEmail.vue
│   ├── ConfirmPassword.vue
│   └── TwoFactorChallenge.vue
└── settings/
    ├── Profile.vue
    ├── Security.vue
    └── Appearance.vue
```

## Instalação

### Pré-requisitos

- PHP ^8.3 com extensões: mbstring, xml, ctype, json, bcmath, pdo, sqlite3
- Node.js 20+
- Composer 2+

### Passo a Passo

```bash
# Clonar o repositório
git clone <repo-url>
cd starter-cms

# Instalar dependências PHP
composer install

# Instalar dependências JS
npm install

# Configurar ambiente
cp .env.example .env
php artisan key:generate

# Configurar banco de dados (SQLite por padrão)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Iniciar servidores de desenvolvimento
php artisan serve    # Backend em http://localhost:8000
npm run dev          # Frontend com HMR em http://localhost:5173
```

### Usando MySQL/PostgreSQL

Se preferir MySQL ou PostgreSQL, altere as variáveis no `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=starter_cms
DB_USERNAME=root
DB_PASSWORD=secret
```

Depois execute `php artisan migrate` e `php artisan db:seed`.

### Usando Laravel Sail (Docker)

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail npm run dev
```

## Comandos Úteis

### Desenvolvimento

```bash
composer run dev          # Inicia artisan + vite juntos
composer run setup        # Setup completo (install + migrate + seed + build)
composer run lint         # Corrige código com Pint
composer run lint:check   # Verifica estilo sem alterar
composer run types:check  # Roda PHPStan (level 8)
```

### Testes

```bash
vendor/bin/pest                       # Roda todos os testes
vendor/bin/pest --filter=Post         # Filtra por modelo/feature
php artisan test --compact            # Modo compacto
composer run test                     # Pint + PHPStan + testes
composer run ci:check                 # CI completo (lint + types + test)
```

### Análise de Código

```bash
vendor/bin/phpstan analyse            # Análise estática (level 8)
vendor/bin/pint --test                # Verifica formatação
vendor/bin/pint --dirty --format agent # Corrige arquivos modificados
vendor/bin/rector process             # Refactoring automático
```

### Frontend

```bash
npm run dev          # Servidor com HMR
npm run build        # Build de produção
npm run lint         # ESLint com autofix
npm run lint:check   # Verifica lint sem alterar
npm run format       # Prettier formata tudo
npm run format:check # Verifica formatação
npm run types:check  # TypeScript check
```

## Seeders e Factories

O projeto inclui 9 seeders para popular o banco com dados de exemplo:

| Seeder | Descrição |
|--------|-----------|
| `DatabaseSeeder` | Orquestra todos os seeders |
| `RolesAndPermissionsSeeder` | Cria roles e permissions do RBAC |
| `SiteSettingsSeeder` | Configurações gerais do site |
| `CategoriesSeeder` | Categorias do blog |
| `TagsSeeder` | Tags para posts |
| `PostsSeeder` | Posts de exemplo |
| `PagesSeeder` | Páginas com blocos de conteúdo |
| `ProjectsSeeder` | Projetos do portfólio |
| `FormsSeeder` | Formulários dinâmicos |

Factories disponíveis para criação de dados em testes.

## Módulos

### Blog (Content)

Posts com categorias e tags. Suporta SEO via `ralphjsmit/laravel-seo` com Schema.org integrado.

- **Models**: Post, Category, Tag
- **Resources**: PostResource, CategoryResource, TagResource
- **Controller**: BlogController (público)

### Portfólio

Projetos com tecnologias associadas.

- **Models**: Project, Technology
- **Resources**: ProjectResource
- **Controllers**: PortfolioController (público)

### Páginas

Páginas construídas com blocos de conteúdo arrastáveis. O campo `content` é salvo como JSON e renderizado pelo `BlockRenderer.vue`.

- **Models**: Page
- **Resources**: PageResource
- **Controller**: PageController (público)

### Formulários

Formulários dinâmicos definidos via JSON Schema. Os campos são gerados automaticamente tanto no admin quanto no frontend.

- **Models**: Form, FormSubmission
- **Resources**: FormResource
- **Controller**: FormController (público, processa envios)

### Configurações

Configurações gerais do site via `spatie/laravel-settings`.

- **Resource**: ManageSiteSettings (Filament)

## Licença

MIT
