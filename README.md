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

2. Build and start the application:

**Option A: Using setup script (recommended for first time)**
```bash
chmod +x setup.sh
./setup.sh
```

This will:
- Build Docker containers
- Install dependencies
- Run migrations
- Start the application

**Option B: Manual setup**
```bash
# Build containers
docker compose build

# Start containers
docker compose up -d

# Install dependencies
docker compose exec php composer install

# Copy environment file
cp .env.example .env

# Generate application key
docker compose exec php php artisan key:generate

# Run migrations
docker compose exec php php artisan migrate
```

3. Access the application at: http://localhost:8080

## How to Use

1. **Register a new user**:
   - Go to http://localhost:8080
   - Fill in username and phone number
   - After registration, you'll receive a unique link

2. **Access Page A**:
   - Use the generated link to access the game page
   - Available actions:
     - "Сгенерировать новый линк" - Generate a new link (deactivates current)
     - "Деактивировать данный линк" - Deactivate current link
     - "I'm feeling lucky" - Play the game
     - "History" - View last 3 game results

3. **Game mechanics**:
   - Click "I'm feeling lucky" to generate a random number (1-1000)
   - Even numbers = Win, Odd numbers = Lose
   - Win amounts vary based on the number value

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

## Troubleshooting

### Port conflicts
If you see errors about ports already in use:
- MySQL (3306): Change `DB_PORT` in `.env` and update `docker-compose.yml`
- Redis (6379): Change `REDIS_PORT` in `.env` and update `docker-compose.yml`
- Nginx (8080): Change port mapping in `docker-compose.yml` (nginx service)

### Container startup issues
```bash
# Clean restart
docker compose down -v  # Remove volumes
docker compose build --no-cache
docker compose up -d
```

### Database connection errors
```bash
# Wait for MySQL to be ready (first time startup can take 30-60 seconds)
docker compose logs mysql

# Manually run migrations if needed
docker compose exec php php artisan migrate:fresh
```

## Project Structure

```
app/
├── Exceptions/          # Custom exceptions
├── Http/Controllers/    # HTTP controllers
├── Models/             # Eloquent models
└── Services/           # Business logic services

tests/
├── Feature/            # Feature tests
└── Unit/              # Unit tests

docker/
├── mysql/             # MySQL configuration
├── nginx/             # Nginx configuration
└── php/               # PHP Dockerfile and config
```

## Key Files

- `app/Http/Controllers/RegisterController.php` - User registration
- `app/Http/Controllers/LinkController.php` - Link management
- `app/Http/Controllers/GameController.php` - Game logic
- `app/Services/GameService.php` - Core game business logic
- `app/Models/Link.php` - Link model with access validation
- `tests/Feature/GameApiTest.php` - Comprehensive API tests
