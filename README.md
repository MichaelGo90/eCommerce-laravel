# Laravel Application Setup with Laravel Sail

This is a Laravel project built using [Laravel Sail](https://laravel.com/docs/8.x/sail), a convenient way to run a Laravel application inside a Docker environment.

## Prerequisites

Ensure the following is installed before starting:
- [Docker](https://www.docker.com/get-started) (required for running the application with Sail)

## Getting Started

### 1. Clone the Repository

Clone this repository to your local environment:
```bash
git clone git@github.com:MichaelGo90/eCommerce-laravel.git  
cd eCommerce-laravel
```

### 2. Install Composer Dependencies
```composer install```

### 3. Set up the Environment File
```cp .env.example .env```

### 4. Run Migrations
```./vendor/bin/sail artisan migrate```

### 5. Run the Seeders
```./vendor/bin/sail db:seed```

### 6. Run the Queue Worker
```./vendor/bin/sail queue:work```

### 7. Install Composer Dependencies
```./vendor/bin/sail up```

### 6. Access the Application
Once the application is up, visit http://localhost in your browser to access the Laravel application.


