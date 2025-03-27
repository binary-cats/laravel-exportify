# Laravel Exportify

A Laravel package that provides a unified interface for handling exports (Excel, PDF, etc.) with built-in support for queuing, history tracking, and a beautiful dashboard.

## Features

- 🚀 **Unified Interface**: A single contract for all exports (`download`, `queue`, `raw`, `store`)
- 🛠️ **Artisan Command**: Generate export classes with optional policy, Livewire components, and tests
- 🔒 **Gate Protection**: Built-in authorization support for exports
- 📊 **Dashboard**: A beautiful, Horizon-style dashboard for monitoring exports
- 📝 **History Tracking**: Keep track of all exports with metadata
- 🏷️ **Tagging System**: Organize exports with tags for easy filtering
- ⚡ **Queue Support**: Built-in support for queued exports

## Installation

You can install the package via composer:

```bash
composer require binary-cats/laravel-exportify
```

The package will automatically register its service provider.

You can publish the config file with:

```bash
php artisan vendor:publish --provider="BinaryCats\Exportify\ExportifyServiceProvider" --tag="config"
```

You can publish the views with:

```bash
php artisan vendor:publish --provider="BinaryCats\Exportify\ExportifyServiceProvider" --tag="views"
```

You can publish the migration with:

```bash
php artisan vendor:publish --provider="BinaryCats\Exportify\ExportifyServiceProvider" --tag="migrations"
```

After publishing the migration, you can create the tables by running:

```bash
php artisan migrate
```

## Usage

### Creating an Export

Use the Artisan command to generate a new export:

```bash
php artisan make:exportify UsersExport
```

This will create:
- `app/Exports/UsersExport.php`
- `app/Policies/UsersExportPolicy.php` (optional)
- `app/Livewire/Exports/UsersExportForm.php` (optional)
- `app/Livewire/Exports/UsersExportComponent.php` (optional)
- `tests/Feature/Exports/UsersExportTest.php` (optional)

### Implementing an Export

Here's an example of how to implement an export:

```php
<?php

namespace App\Exports;

use BinaryCats\Exportify\Contracts\Exportable;
use Illuminate\Support\Facades\Storage;

class UsersExport implements Exportable
{
    public function download(array $parameters = []): mixed
    {
        // Generate and return the file
        $path = Storage::disk('exports')->put('users.csv', $this->generateCsv());
        return Storage::disk('exports')->download($path);
    }

    public function queue(array $parameters = []): mixed
    {
        // Queue the export job
        return dispatch(new GenerateUsersExport($parameters));
    }

    public function raw(array $parameters = []): mixed
    {
        // Return raw data
        return $this->generateCsv();
    }

    public function store(array $parameters = []): mixed
    {
        // Store the file
        return Storage::disk('exports')->put('users.csv', $this->generateCsv());
    }

    public function tags(): array
    {
        return ['users', 'reports'];
    }

    protected function generateCsv(): string
    {
        // Generate CSV content
        return "id,name,email\n1,John Doe,john@example.com";
    }
}
```

### Registering Exports

You can register exports in your `AppServiceProvider`:

```php
use BinaryCats\Exportify\Facades\Exportify;

public function boot(): void
{
    Exportify::register('users', new UsersExport());
}
```

### Using the Facade

```php
use BinaryCats\Exportify\Facades\Exportify;

// Get all exports
$exports = Exportify::all();

// Get available exports for current user
$available = Exportify::available();

// Get exports by tag
$userExports = Exportify::byTag('users');

// Find a specific export
$export = Exportify::find('users');
```

### Using Blade Components

```php
<x-exportables>
    {{-- List all available exports --}}
</x-exportables>

<x-exportable name="users">
    {{-- Render a specific export form --}}
</x-exportable>
```

### Dashboard

The dashboard is available at `/exportify` by default. You can configure the path and middleware in the config file.

## Configuration

You can find the configuration file at `config/exportify.php`. Here are the main options:

```php
return [
    'history' => [
        'enabled' => env('EXPORTIFY_HISTORY_ENABLED', true),
        'table' => 'export_history',
    ],
    'storage' => [
        'disk' => env('EXPORTIFY_STORAGE_DISK', 'exports'),
        'path' => env('EXPORTIFY_STORAGE_PATH', 'exports'),
    ],
    'queue' => [
        'connection' => env('EXPORTIFY_QUEUE_CONNECTION', 'default'),
        'queue' => env('EXPORTIFY_QUEUE_NAME', 'default'),
    ],
    'dashboard' => [
        'enabled' => env('EXPORTIFY_DASHBOARD_ENABLED', true),
        'path' => env('EXPORTIFY_DASHBOARD_PATH', 'exportify'),
        'middleware' => ['web', 'auth'],
    ],
];
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email cyrill.kalita@gmail.com instead of using the issue tracker.

## Credits

- [Cyrill N Kalita](https://github.com/binary-cats)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
