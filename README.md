# InForge — Enterprise Laravel Starter Kit & CRUD Engine

A modern Laravel admin template with a powerful CRUD generator, built for **Laravel 13** with **Tailwind CSS v4**, **Livewire PowerGrid v6**, and **Spatie Laravel Permission**.

**Unified Command**: All functionality is accessible via `generate:scaffold` (and reversible via `revert:scaffold`).

## Features

### CRUD Generator

- Generate complete CRUD operations with a single command
- **Interactive mode** — enter fields one by one with step-by-step prompts
- **Generate from existing database tables** — auto-detect columns, types, enums, and foreign keys
- **JSON schema support** — define complex field structures in JSON
- **API generation** — use `--api` to generate both web CMS + RESTful API controllers simultaneously
- **Selective scaffolding** — `--only=...` and `--except=...` flags to generate exactly what you need
- **Automatic rollback** — if any generator fails, all previously generated files are rolled back
- **Revert command** — `revert:scaffold {Model}` to cleanly remove all generated files and database entries
- Support for various field types (text, textarea, select, checkbox, date, email, password, number, file, currency, tags)
- Validation rules generation with separate `CreateRequest` and `UpdateRequest` classes
- Optional migration generation (use `--migration` flag)
- Model, Controller, Request, Views, PowerGrid Table, Enum, Factory, Seeder, and Unit Test generation
- Route registration with automatic injection into `routes/web.php`
- Menu and permission auto-registration via `config/menu.php` and Spatie Permission

### Generator Architecture (Modular)

The generator is built with a highly modular, single-responsibility architecture:

| Component                 | Location           | Purpose                                       |
| ------------------------- | ------------------ | --------------------------------------------- |
| `GenerateScaffoldCommand` | `Commands/`        | Main entry point, orchestrates all generators |
| `RevertScaffoldCommand`   | `Commands/`        | Reverses scaffold generation                  |
| `CommandData`             | `Common/`          | Shared data object for all generators         |
| `GeneratorField`          | `Common/`          | Field definition and type mapping             |
| `FieldParser`             | `Services/`        | Parses interactive/CLI field input            |
| `SchemaIntrospector`      | `Services/`        | Reads existing DB table structure             |
| `RouteInjector`           | `Services/`        | Injects routes into `web.php`                 |
| `ModelGenerator`          | `Generators/`      | Generates Eloquent model                      |
| `ControllerGenerator`     | `Generators/`      | Generates web/API controller                  |
| `CreateRequestGenerator`  | `Generators/`      | Generates create form request                 |
| `UpdateRequestGenerator`  | `Generators/`      | Generates update form request                 |
| `MigrationGenerator`      | `Generators/`      | Generates database migration                  |
| `ViewGenerator`           | `Generators/`      | Orchestrates view generation                  |
| `FormFieldRenderer`       | `Generators/View/` | Renders form input fields                     |
| `TableRenderer`           | `Generators/View/` | Renders table columns/display                 |
| `ComponentRenderer`       | `Generators/View/` | Renders Blade components                      |
| `ImportExportRenderer`    | `Generators/View/` | Renders import/export views                   |
| `PowerGridTableGenerator` | `Generators/`      | Generates Livewire PowerGrid table class      |
| `EnumGenerator`           | `Generators/`      | Generates PHP 8.3 native Enum class           |
| `FactoryGenerator`        | `Generators/`      | Generates model factory                       |
| `SeederGenerator`         | `Generators/`      | Generates database seeder                     |
| `UnitTestGenerator`       | `Generators/`      | Generates feature tests                       |
| `MenuGenerator`           | `Generators/`      | Registers menu in `config/menu.php`           |
| `PermissionGenerator`     | `Generators/`      | Registers Spatie permissions                  |

### Optimizations

- **Statically compiled views** — no runtime loops; form fields and table columns are rendered at generation time
- **Modular traits** — `HasFileUpload` and `HasImportExport` traits keep controllers thin and readable
- **Security** — Spatie Laravel Permission middleware is automatically injected into generated controllers
- **Native Enum support (PHP 8.3+)** — generates Enum classes, `Rule::enum()` validation, and `<x-select>` dropdowns
- **BelongsTo relationships** — auto-generates `belongsTo()` on models and injects foreign data into views
- **Soft Deletes** — `--soft-deletes` flag adds trait and migration column automatically
- **Glassmorphism UI** — generated views use floating input components (`x-input-floating`, `x-select-floating`, `x-textarea-floating`) with dark mode support

### Built-in InForge Features

Beyond the CRUD Generator, this template comes pre-packaged with enterprise-grade features:

| Module                 | Description                                                |
| ---------------------- | ---------------------------------------------------------- |
| **Dashboard**          | Admin overview dashboard                                   |
| **Users**              | Full user CRUD with avatar upload                          |
| **Roles**              | Role management (Spatie)                                   |
| **Permissions**        | Permission management (Spatie)                             |
| **Activity Logs**      | Track all user activities                                  |
| **Server Logs**        | Browse, view, and clear Laravel log files directly from UI |
| **Settings**           | Dynamic app configuration (see below)                      |
| **Profile**            | User profile with password change and avatar               |
| **OTP Login**          | Optional two-factor OTP authentication via email           |
| **Maintenance Mode**   | Toggle maintenance mode with elegant 3D splash page        |
| **Custom Error Pages** | Styled 403, 404, 419, 500, 503 error pages                 |

#### Dynamic Settings (from Admin UI)

- **Theme & Appearance** — Light/Dark/System theme toggle, persistent per-user; dynamic logo and favicon upload
- **Dynamic SMTP** — configure SMTP host, port, username, password, encryption from the UI (no `.env` editing needed in production)
- **Maintenance Mode** — flip a switch to lock out users; admins bypass via IP allow-list

#### Two-Factor OTP Login

- Toggle via `.env` (`ENABLE_OTP_LOGIN=true`)
- Secure 6-digit OTP code sent to user's email
- 30-second resend cooldown (client-side)
- OTP expires after 5 minutes via Laravel Cache (no database bloat)

#### Centralized Notifications

- `<x-toast>` Blade component handles all session success/error/warning flashes automatically

### Blade Components

Pre-built reusable Blade components:

| Component                  | File                            |
| -------------------------- | ------------------------------- |
| `<x-input-floating>`       | Floating label text input       |
| `<x-textarea-floating>`    | Floating label textarea         |
| `<x-select-floating>`      | Floating label select dropdown  |
| `<x-input>`                | Standard text input             |
| `<x-textarea>`             | Standard textarea               |
| `<x-select>`               | Standard select                 |
| `<x-modern-input>`         | Modern styled input             |
| `<x-modern-select>`        | Modern styled select            |
| `<x-modern-textarea>`      | Modern styled textarea          |
| `<x-toggle>`               | Toggle switch for booleans      |
| `<x-button>`               | Styled button                   |
| `<x-datetime>`             | Date/time picker                |
| `<x-filepond>`             | FilePond file upload            |
| `<x-toast>`                | Toast notification              |
| `<x-confirm-delete-modal>` | SweetAlert2 delete confirmation |

## Tech Stack

| Technology                | Version                        |
| ------------------------- | ------------------------------ |
| PHP                       | ^8.3                           |
| Laravel                   | ^13.0                          |
| Tailwind CSS              | ^4.1 (via `@tailwindcss/vite`) |
| Livewire                  | ^4.3                           |
| Livewire PowerGrid        | ^6.10                          |
| Spatie Laravel Permission | ^8.0                           |
| Vite                      | ^7.0                           |
| SweetAlert2               | ^11.26                         |
| PHPUnit                   | ^12.0                          |
| Blade Heroicons           | ^2.7                           |
| OpenSpout                 | ^4.0 (Excel import/export)     |

**Optional (suggested):**

- `doctrine/dbal` ^4.4 — for `--fromTable` schema introspection
- `phpoffice/phpspreadsheet` ^5.2 — for robust Excel import/export

## Installation

### Prerequisites

- **PHP** 8.3 or higher
- **Composer** (PHP dependency manager)
- **Node.js** 18+ and **npm** (for frontend assets)
- **Database** (MySQL, PostgreSQL, or SQLite)

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd inforge
```

### Step 2: Install Dependencies

```bash
composer install
npm install
```

### Step 3: Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

> **Note:** Default locale is `id` (Indonesian) with `Asia/Jakarta` timezone. Adjust `APP_LOCALE`, `APP_TIMEZONE` in `.env` as needed.

### Step 4: Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed
```

### Step 5: Build & Run

```bash
# Development (starts server + queue + vite concurrently)
composer run dev

# Or manually
php artisan serve
npm run dev
```

The app will be available at `http://localhost:8000`.

### Quick Setup (All-in-One)

```bash
composer run setup
```

This will: install Composer deps → create `.env` → generate key → run migrations → install npm deps → build frontend.

### Docker Deployment

```bash
# Build the image
docker build -t admin-template .

# Run the container
docker run -p 80:80 admin-template
```

The Dockerfile uses a multi-stage build (Node → Composer → PHP 8.3 FPM + Nginx Alpine) and is production-ready.

Kubernetes manifests are available in the `kubernetes/` directory (`deployment.yaml`, `pvc.yaml`).

### Troubleshooting

```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache

# Clear all caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear

# Reinstall dependencies
rm -rf vendor node_modules && composer install && npm install
```

## Usage

### Quick Start

**Interactive Mode (Recommended):**

```bash
# Start interactive mode — prompts for model name and fields
php artisan generate:scaffold

# Or with model name — prompts for fields only
php artisan generate:scaffold Product
```

**From Existing Database Table:**

```bash
php artisan generate:scaffold Blog --fromTable --tableName=blog
```

**From Field Definitions:**

```bash
php artisan generate:scaffold Product --fields="name:string:text:required,price:decimal:number:required"
```

**From JSON Schema:**

```bash
php artisan generate:scaffold Product --schema=resources/schemas/product.json
```

**Generate API + Web CMS simultaneously:**

```bash
php artisan generate:scaffold Product --fields="name:string:text:required" --api
```

### Interactive Mode

```bash
$ php artisan generate:scaffold Product

No fields provided. Starting interactive mode...
Format: name:dbType:htmlType:options

Field 1 (or press Enter to finish): name:string:text:required
✓ Added field: name (string, text)
Field 2 (or press Enter to finish): price:decimal:number:required
✓ Added field: price (decimal, number)
Field 3 (or press Enter to finish): [Enter]

Generating CRUD for Product...
✓ Generated: ModelGenerator
✓ Generated: EnumGenerator
✓ Generated: ControllerGenerator
✓ Generated: PowerGridTableGenerator
✓ Generated: CreateRequestGenerator
✓ Generated: UpdateRequestGenerator
...
```

Press Enter immediately (without any field) to generate CRUD with basic structure only (id, timestamps).

### Generate from Existing Database Table

```bash
php artisan generate:scaffold Blog --fromTable --tableName=blog
```

This will:

- Read the table structure from the database
- Auto-detect column types (int, varchar, text, json, date, etc.)
- **Detect ENUM fields and generate select dropdowns with proper options**
- **Detect foreign keys and generate BelongsTo relationships**
- Generate Model, Controller, Requests, Views, PowerGrid Table
- Skip migration by default (use `--migration` to generate)

### Field Syntax

**Format:** `name:dbType:htmlType:options`

**Enum Fields:**

```bash
--fields="status:string:enum=App\Enums\StatusEnum(draft,published)"
```

Generates: Enum class, `Rule::enum()` validation, `<x-select>` dropdown.

**BelongsTo Relations:**

```bash
--fields="category_id:foreignId:belongsTo(Category)"
```

Generates: `belongsTo()` relationship, controller data injection, `<x-select>` dropdown.

**Currency Input:**

```bash
--fields="price:decimal:currency:required"
```

Integrates AutoNumeric.js with Indonesian Rupiah (Rp) formatting.

### Field Types

#### Database Types

| Type        | SQL Equivalent  |
| ----------- | --------------- |
| `string`    | VARCHAR         |
| `text`      | TEXT            |
| `integer`   | INTEGER         |
| `decimal`   | DECIMAL         |
| `boolean`   | BOOLEAN         |
| `date`      | DATE            |
| `datetime`  | DATETIME        |
| `timestamp` | TIMESTAMP       |
| `json`      | JSON            |
| `foreignId` | UNSIGNED BIGINT |

#### HTML Types

| Type                   | Component                              |
| ---------------------- | -------------------------------------- |
| `text`                 | `<x-input-floating>`                   |
| `textarea`             | `<x-textarea-floating>` (with TinyMCE) |
| `select`               | `<x-select-floating>`                  |
| `checkbox` / `boolean` | `<x-toggle>` switch                    |
| `date`                 | `<x-datetime>`                         |
| `email`                | `<x-input-floating type="email">`      |
| `password`             | `<x-input-floating type="password">`   |
| `number`               | `<x-input-floating type="number">`     |
| `file`                 | `<x-filepond>`                         |
| `currency`             | AutoNumeric.js formatted input         |
| `tags`                 | Tagify input                           |

### Field Options

| Option                   | Description                 |
| ------------------------ | --------------------------- |
| `nullable`               | Make field nullable         |
| `required`               | Make field required         |
| `searchable`             | Enable search in PowerGrid  |
| `sortable`               | Enable sorting in PowerGrid |
| `options=a,b,c`          | Options for select fields   |
| `default:value`          | Default value               |
| `validation:rule1,rule2` | Custom validation rules     |

### Command Options

```
php artisan generate:scaffold {model?}
    --fromTable            Generate from existing table
    --tableName=           Existing database table name
    --fields=              Field definitions
    --schema=              Path to JSON schema file
    --api                  Generate API controller alongside web CMS
    --migration            Generate migration file (off by default)
    --soft-deletes         Add SoftDeletes trait and migration column
    --with-factory         Generate factory
    --with-seeder          Generate seeder
    --with-import          Generate import/export feature
    --section-title=       Menu section title
    --only=                Comma-separated: model,migration,controller,view,datatable,request,factory,seeder,test,menu,permission,enum
    --except=              Comma-separated generators to skip
    --no-controller        Skip controller
    --no-model             Skip model
    --no-views             Skip views
    --no-request           Skip request
    --no-routes            Skip routes
    --no-menu              Skip menu
    --no-permissions       Skip permissions
    --no-test              Skip test (tests generated by default)
    --skip-db              Skip inserting permissions to database
    --force                Overwrite existing files
```

### Revert Scaffold

Remove all generated files for a model:

```bash
php artisan revert:scaffold Product

# Skip confirmation prompt
php artisan revert:scaffold Product --force
```

This removes: Model, Controller, API Controller, Requests, Views, PowerGrid Table, Migration, Factory, Seeder, Test, Menu entries, Permissions, and Route entries.

### Examples

```bash
# Interactive mode
php artisan generate:scaffold

# Simple blog post
php artisan generate:scaffold Post \
    --fields="title:string:text:required,content:text:textarea:required,status:string:select:options=published,draft" \
    --migration

# With enum and belongs-to
php artisan generate:scaffold Article \
    --fields="title:string:text:required,category_id:foreignId:belongsTo(Category),status:string:enum=App\Enums\ArticleStatus(draft,published,archived)" \
    --migration --soft-deletes

# From existing table with API
php artisan generate:scaffold Product --fromTable --tableName=products --api

# Only model and migration
php artisan generate:scaffold Setting --fields="key:string:text:required,value:text:textarea" --only=model,migration

# With factory, seeder, and import/export
php artisan generate:scaffold User \
    --fields="name:string:text:required,email:string:email:required" \
    --with-factory --with-seeder --with-import
```

### JSON Schema

Create a schema file (e.g. `resources/schemas/product.json`):

```json
{
    "model": "Product",
    "fields": [
        {
            "name": "name",
            "dbType": "string",
            "htmlType": "text",
            "validation": ["required", "string", "max:255"],
            "searchable": true,
            "sortable": true
        },
        {
            "name": "price",
            "dbType": "decimal",
            "htmlType": "number",
            "validation": ["required", "numeric", "min:0"]
        },
        {
            "name": "category",
            "dbType": "string",
            "htmlType": "select",
            "validation": ["required", "string"],
            "options": ["Electronics", "Clothing", "Books"]
        },
        {
            "name": "is_active",
            "dbType": "boolean",
            "htmlType": "checkbox",
            "validation": ["boolean"],
            "default": true
        }
    ]
}
```

Then generate:

```bash
php artisan generate:scaffold Product --schema=resources/schemas/product.json
```

## Generated Files

| File            | Location                                                                  |
| --------------- | ------------------------------------------------------------------------- |
| Model           | `app/Models/{Model}.php`                                                  |
| Controller      | `app/Http/Controllers/{Model}Controller.php`                              |
| API Controller  | `app/Http/Controllers/Api/{Model}ApiController.php` (with `--api`)        |
| Create Request  | `app/Http/Requests/Create{Model}Request.php`                              |
| Update Request  | `app/Http/Requests/Update{Model}Request.php`                              |
| PowerGrid Table | `app/Livewire/Tables/{Model}Table.php`                                    |
| Migration       | `database/migrations/{ts}_create_{table}_table.php` (with `--migration`)  |
| Views           | `resources/views/admin/{model_snake_plural}/` (index, create, edit, show) |
| Enum            | `app/Enums/{EnumName}.php` (when enum fields are defined)                 |
| Factory         | `database/factories/{Model}Factory.php` (with `--with-factory`)           |
| Seeder          | `database/seeders/{Model}Seeder.php` (with `--with-seeder`)               |
| Test            | `tests/Feature/{Model}Test.php` (generated by default)                    |
| Menu            | Injected into `config/menu.php`                                           |
| Permissions     | Seeded into `permissions` table via Spatie                                |
| Routes          | Injected into `routes/web.php`                                            |

## Unit Testing

Tests are generated by default for all CRUD operations.

```bash
# Run all tests
php artisan test

# Run specific model tests
php artisan test --filter ProductTest

# Run with coverage
php artisan test --coverage
```

### Generated Test Coverage

- ✅ Index page accessibility
- ✅ Create page accessibility
- ✅ Store method with validation
- ✅ Show page
- ✅ Edit page accessibility
- ✅ Update method with validation
- ✅ Destroy method
- ✅ Required field validation
- ✅ Authorization checks

Skip test generation with `--no-test`.

## Customization

### Stub Templates

Customize generated code by modifying stubs in `resources/stubs/`:

| Stub                           | Purpose                                      |
| ------------------------------ | -------------------------------------------- |
| `model.stub`                   | Eloquent model                               |
| `controller.stub`              | Web controller                               |
| `controller-api.stub`          | API controller                               |
| `powergrid-table.stub`         | Livewire PowerGrid table                     |
| `request/create.stub`          | Create form request                          |
| `request/update.stub`          | Update form request                          |
| `migration.stub`               | Database migration                           |
| `enum.stub`                    | PHP Enum class                               |
| `factory.stub`                 | Model factory                                |
| `seeder.stub`                  | Database seeder                              |
| `service.stub`                 | Service class                                |
| `test.stub`                    | Feature test                                 |
| `view/index.stub`              | Index page (with PowerGrid)                  |
| `view/create.stub`             | Create form                                  |
| `view/edit.stub`               | Edit form                                    |
| `view/show.stub`               | Detail page                                  |
| `view/import.stub`             | Import/export page                           |
| `view/datatables_actions.stub` | Table action buttons                         |
| `fields/*.stub`                | Individual field type stubs                  |
| `js/*.stub`                    | JavaScript stubs (password strength, tagify) |

### Adding New Field Types

Modify `GeneratorField` in `app/Generators/Common/GeneratorField.php` and add HTML generation logic in `FormFieldRenderer`.

## Project Structure

```
inforge/
├── app/
│   ├── Console/               # Console kernel
│   ├── DataTables/            # (legacy, unused)
│   ├── Enums/                 # PHP Enums (HttpStatusCode, ResponseStatus)
│   ├── Generators/            # ⭐ CRUD Generator engine
│   │   ├── Commands/          # generate:scaffold, revert:scaffold
│   │   ├── Common/            # CommandData, GeneratorField
│   │   ├── Generators/        # All generator classes
│   │   │   └── View/          # FormFieldRenderer, TableRenderer, etc.
│   │   ├── Services/          # FieldParser, RouteInjector, SchemaIntrospector
│   │   └── Utils/             # FileUtil, GeneratorFieldsInputUtil
│   ├── Helpers/               # MenuHelper, ApiResponseHelper
│   ├── Http/
│   │   ├── Controllers/       # Admin controllers (Auth, User, Role, etc.)
│   │   │   ├── Admin/         # (for generated admin controllers)
│   │   │   └── Api/           # (for generated API controllers)
│   │   └── Requests/          # Form request classes
│   ├── Jobs/                  # ExportJob (async export)
│   ├── Livewire/
│   │   ├── Tables/            # PowerGrid table components
│   │   └── PowerGridTheme.php # Custom PowerGrid theme
│   ├── Mail/                  # LoginOtpMail
│   ├── Models/                # User, Role, Permission, Setting, ActivityLog
│   ├── Providers/             # AppServiceProvider, GeneratorServiceProvider
│   ├── Services/              # ActivityLogService, FileUploadService, LaravelLogService
│   └── Traits/                # HasFileUpload, HasImportExport
├── config/
│   ├── menu.php               # Sidebar menu configuration
│   ├── permission.php         # Spatie permission config
│   └── livewire-powergrid.php # PowerGrid config
├── database/migrations/       # Base migrations (users, permissions, settings, etc.)
├── kubernetes/                # K8s deployment & PVC manifests
├── resources/
│   ├── stubs/                 # ⭐ Code generation templates
│   ├── views/
│   │   ├── admin/             # Admin InForge views
│   │   │   ├── auth/          # Login, OTP pages
│   │   │   ├── layouts/       # Admin layout
│   │   │   ├── pages/         # Dashboard, users, roles, settings, etc.
│   │   │   ├── partials/      # Sidebar, header, etc.
│   │   │   └── emails/        # Email templates
│   │   ├── components/        # ⭐ Reusable Blade components
│   │   ├── errors/            # Custom error pages (403, 404, 419, 500, 503)
│   │   └── maintenance.blade.php
│   └── css/, js/              # Frontend assets
├── routes/
│   ├── web.php                # Web routes (with ADMIN_ROUTES_MARKER)
│   └── console.php            # Console routes
├── server/                    # Nginx config for Docker
├── tests/                     # Feature & Unit tests
├── Dockerfile                 # Multi-stage production build
├── .intechstudio-ci.yaml           # CI/CD pipeline config
└── vite.config.js             # Vite + Tailwind CSS v4
```

## Composer Scripts

| Command              | Description                                            |
| -------------------- | ------------------------------------------------------ |
| `composer run setup` | Full project setup (install deps, env, migrate, build) |
| `composer run dev`   | Start dev server + queue worker + Vite concurrently    |
| `composer run test`  | Clear config cache and run tests                       |

## License

MIT License
