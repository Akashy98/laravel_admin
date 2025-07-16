# AstroIndia Backend Documentation

Welcome to the AstroIndia Backend API documentation. This directory contains comprehensive documentation for all aspects of the application.

## 📚 Documentation Index

### 🚀 API Documentation
- **[API Resources Documentation](API_RESOURCES_DOCUMENTATION.md)** - Complete API endpoints and resources
- **[API Response Structure](API_RESPONSE_STRUCTURE.md)** - Standardized API response formats
- **[Astrologer API Documentation](ASTROLOGER_API_DOCUMENTATION.md)** - Astrologer-specific API endpoints
- **[OTP Authentication](OTP_AUTHENTICATION.md)** - OTP-based authentication system

### 🔧 Development & Setup
- **[Admin Setup Guide](ADMIN_SETUP.md)** - Admin panel setup and configuration
- **[Admin JS/CSS Documentation](ADMIN_JS_CSS_README.md)** - Frontend assets and styling guide
- **[API Logging Middleware](API_LOGGING_MIDDLEWARE.md)** - Request/response logging system

## 🏗️ Project Overview

AstroIndia Backend is a Laravel-based REST API that provides:

- **User Management**: Registration, authentication, profile management
- **Astrologer Services**: Astrologer profiles, availability, pricing, reviews
- **Location Services**: Countries, states, cities management
- **Wallet System**: Digital wallet with transactions and offers
- **Admin Panel**: Comprehensive admin interface for management

## 🛠️ Technology Stack

- **Framework**: Laravel 8+
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful API with JSON responses
- **Logging**: Custom API logging middleware with daily rotation

## 📁 Project Structure

```
astroindia_backend/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/           # API Controllers
│   │   └── Admin/         # Admin Controllers
│   ├── Models/            # Eloquent Models
│   ├── Http/Middleware/   # Custom Middleware
│   └── Services/          # Business Logic Services
├── config/
│   ├── api_logging.php    # API Logging Configuration
│   └── logging.php        # Log Channels Configuration
├── database/
│   ├── migrations/        # Database Migrations
│   └── seeds/            # Database Seeders
├── routes/
│   ├── api.php           # API Routes
│   └── web.php           # Web Routes
├── docs/                 # 📚 Documentation (This Directory)
└── storage/logs/         # Application Logs
```

## 🚀 Quick Start

1. **Clone the repository**
2. **Install dependencies**: `composer install`
3. **Configure environment**: Copy `.env.example` to `.env`
4. **Run migrations**: `php artisan migrate`
5. **Seed database**: `php artisan db:seed`
6. **Start server**: `php artisan serve`

## 📊 API Features

### Authentication
- OTP-based authentication
- Token-based API access
- User session management

### User Management
- User registration and login
- Profile management
- Address and contact management
- Birth details tracking

### Astrologer Services
- Astrologer registration and profiles
- Availability management
- Pricing and services
- Reviews and ratings
- Document management
- Bank details

### Location Services
- Countries, states, cities
- Location-based searches
- Address management

### Wallet System
- Digital wallet functionality
- Transaction history
- Wallet offers and promotions

## 🔍 Logging & Monitoring

The application includes a comprehensive API logging system:

- **Daily log rotation**: `storage/logs/api-YYYY-MM-DD.log`
- **Request/response logging**: Complete API activity tracking
- **Security**: Automatic filtering of sensitive data
- **Performance**: Configurable size limits and exclusions

## 📝 Contributing

When adding new features or making changes:

1. **Update relevant documentation** in this `docs/` folder
2. **Follow Laravel conventions** for code structure
3. **Add appropriate logging** for new API endpoints
4. **Update API documentation** for new endpoints

## 🔗 Related Links

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [API Best Practices](https://restfulapi.net/)

---

**Last Updated**: January 2024  
**Version**: 1.0.0 
