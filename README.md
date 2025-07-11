# TestBetTask

Laravel 12 application with game functionality and clean architecture.

## Features

- User registration with unique link generation
- Link-based access control (Page A)
- "I'm feeling lucky" game with win/loss calculation
- Game history tracking (last 3 records)
- RESTful API endpoints for all functionality
- Docker environment (PHP 8.4, Nginx, MySQL, Redis)
- Service layer architecture following SOLID principles
- Custom exception handling
- Comprehensive feature tests (18 tests, all passing)

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone the repository:
```bash
git clone https://github.com/KhalesArtem/TestBetTask.git
cd TestBetTask
```

2. Run the setup script:
```bash
chmod +x setup.sh
./setup.sh
```

This will:
- Build Docker containers
- Install dependencies
- Run migrations
- Start the application

3. Access the application at: http://localhost:8080

## Services

- **PHP 8.4** with Laravel 12
- **Nginx** - Web server (http://localhost:8080)
- **MySQL** - Main database (port 3306)
- **MySQL Test** - Test database (port 3307)
- **Redis** - Cache and session storage (port 6379)

## API Endpoints

- `POST /api/links/{token}/renew` - Generate new link
- `POST /api/links/{token}/deactivate` - Deactivate link
- `POST /api/game/{token}/play` - Play the game
- `GET /api/game/{token}/history` - Get last 3 game results

## Testing

Run tests with:
```bash
docker compose exec php php artisan test
```

## Docker Commands

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# View logs
docker compose logs -f

# Execute artisan commands
docker compose exec php php artisan [command]

# Access PHP container shell
docker compose exec php bash
```

## Database Credentials

### Development Database
- Host: mysql (or localhost:3306 from host machine)
- Database: laravel
- Username: laravel
- Password: secret

### Test Database
- Host: mysql_test (or localhost:3307 from host machine)
- Database: laravel_test
- Username: laravel
- Password: secret

## Architecture

The application follows clean architecture principles:

- **Controllers**: Thin controllers that delegate to services
- **Services**: Business logic layer (GameService, LinkService, RegistrationService)
- **Models**: Eloquent models with relationships
- **Exception Handling**: Custom LinkNotAccessibleException with global handler
- **Dependency Injection**: RandomNumberGenerator service for testability

## Game Rules

- Random number generated between 1-1000
- Win if number is even
- Win amount calculation:
  - Number > 900: 70% of number
  - Number > 600: 50% of number
  - Number > 300: 30% of number
  - Otherwise: 10% of number

## Environment Files

- `.env` - Development environment
- `.env.testing` - Testing environment

Both files are pre-configured to work with the Docker setup.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
