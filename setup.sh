#!/bin/bash

echo "Setting up Laravel project with Docker..."

# Build and start Docker containers
docker compose build
docker compose up -d

# Wait for containers to be ready
echo "Waiting for containers to start..."
sleep 10

# Create Laravel project
docker compose exec php composer create-project laravel/laravel:^12.0 temp
docker compose exec php sh -c 'cp -r temp/* . && cp -r temp/.[^.]* . && rm -rf temp'

# Set proper permissions
docker compose exec php chmod -R 775 storage bootstrap/cache
docker compose exec php chown -R laravel:www-data storage bootstrap/cache

# Copy environment files
cp .env.example .env
cp .env.example .env.testing

echo "Setup complete! Update your .env and .env.testing files with the database credentials."
echo "Access your Laravel app at: http://localhost:8080"