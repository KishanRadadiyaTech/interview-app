# Interview Application

<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Interview Application

This is a comprehensive interview management system built with Laravel. It provides features for scheduling interviews, managing candidates, and reviewing interview feedback.

### Features

- User authentication and authorization
- Interview scheduling and management
- Candidate profile management
- Interview feedback and evaluation
- Role-based access control
- Responsive design for all devices

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP 8.2 or higher
- Composer (https://getcomposer.org/)
- Node.js and NPM (https://nodejs.org/)
- SQLite (or MySQL/PostgreSQL if preferred)
- Git

## Installation

1. **Clone the repository**
   ```bash
   git clone [your-repository-url]
   cd interview-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Create and configure the environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database**
   Update the `.env` file with your database credentials:
   ```
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database.sqlite
   ```
   Then create the SQLite database file:
   ```bash
   touch database/database.sqlite
   ```

6. **Run database migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

## Running the Application

### Development Server
Start the development server:
```bash
php artisan serve
```

For development with hot-reloading (frontend):
```bash
npm run dev
```

Access the application at: http://localhost:8000

### Default Credentials
After running the seeders, you can log in with these default accounts:

**Admin User**
- Email: admin@example.com
- Password: password

**Interviewer**
- Email: interviewer@example.com
- Password: password

**Candidate**
- Email: candidate@example.com
- Password: password

## Development

### Environment Variables
Key environment variables you might want to configure:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Testing
Run the tests with:
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan tests/Feature/ExampleTest.php

# Run tests with coverage (requires Xdebug)
XDEBUG_MODE=coverage php artisan test --coverage
```

### Code Style
This project follows PSR-12 coding standards. You can fix code style issues with:
```bash
# Check code style
composer pint --test

# Fix code style issues
composer pint
```

### Database Management

Run migrations:
```bash
php artisan migrate
```

Rollback the last migration:
```bash
php artisan migrate:rollback
```

Run seeders:
```bash
php artisan db:seed
```

### Frontend Development

Compile assets for production:
```bash
npm run build
```

Watch for changes during development:
```bash
npm run dev
```

## Deployment

### Production Setup
1. Set up your production environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

2. Update `.env` for production:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. Install dependencies:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```

4. Optimize the application:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. Set up the scheduler (add to crontab):
   ```
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

6. Set proper permissions:
   ```bash
   chown -R www-data:www-data /path/to/your/project
   chmod -R 755 storage bootstrap/cache
   ```

### Deployment with Laravel Forge/Envoyer
1. Set up your server in Forge
2. Configure your deployment script to include:
   ```
   cd /home/forge/your-site.com
   git pull origin main
   composer install --no-interaction --prefer-dist --optimize-autoloader
   npm install && npm run prod
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Security Vulnerabilities

If you discover a security vulnerability, please send an e-mail to your email address. All security vulnerabilities will be promptly addressed.

### Security Best Practices
- Always keep your dependencies updated
- Run `composer audit` to check for known security vulnerabilities
- Never commit `.env` files to version control
- Use strong passwords and API keys
- Enable HTTPS in production
- Regularly backup your database

## Troubleshooting

### Common Issues

**1. Permission Denied Errors**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

**2. Class Not Found Errors**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

**3. Database Connection Issues**
- Verify your `.env` database credentials
- Ensure the database server is running
- Check if the database exists and is accessible

**4. Node.js/NPM Issues**
```bash
rm -rf node_modules/
npm cache clean --force
npm install
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code of Conduct

Please review our [Code of Conduct](CODE_OF_CONDUCT.md) before contributing.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Learning Laravel

This application is built on the Laravel framework. To learn more about Laravel, check out the following resources:

- [Laravel Documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com) - Video tutorials on Laravel, PHP, and JavaScript
- [Laravel News](https://laravel-news.com) - News and articles about Laravel
- [Laravel Bootcamp](https://bootcamp.laravel.com) - A guided tutorial for building your first Laravel application

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
