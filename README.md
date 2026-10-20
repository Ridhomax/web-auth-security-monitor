<<<<<<< HEAD
# Web Auth Security Monitor

A Laravel-based web application for monitoring login activity and detecting brute-force attacks using IP- and account-based rules.

## Features

- User authentication and login activity logging
- Brute-force detection based on IP address and account
- Rate limiting for login attempts
- Security dashboard with login statistics
- Security event monitoring and severity indicators
- Admin-only access to the security dashboard

## Tech Stack

- PHP
- Laravel
- MySQL
- Blade
- PHPUnit

## Installation

1. Clone this repository.
2. Install dependencies using `composer install`.
3. Copy `.env.example` to `.env`.
4. Generate the application key using `php artisan key:generate`.
5. Configure your database connection in `.env`.
6. Start the development server using `php artisan serve`.

## Testing

Run automated tests using:

```bash
php artisan test
```

## Security Notes

- Never commit `.env` or real credentials.
- Use a unique, strong password for administrator accounts.
- Configure your own database connection before running the application.

## Project Status

This project is under active development.
=======
# web-auth-security-monitor
Laravel-based web application for monitoring login activity and detecting brute-force attacks using IP- and account-based rules. Features include authentication logs, security dashboard, rate limiting, temporary IP blocking, notifications, and detection evaluation using precision, recall, and false positive rate.
>>>>>>> origin/main
