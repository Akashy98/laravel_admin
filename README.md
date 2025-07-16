# AstroIndia Backend API

<p align="center">
<img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400">
</p>

<p align="center">
<strong>Astrology Services API Platform</strong>
</p>

## 📚 Documentation

**📖 [View Complete Documentation](docs/README.md)**

All project documentation has been organized in the `docs/` folder for better structure and accessibility.

## 🚀 Quick Start

1. **Clone the repository**
2. **Install dependencies**: `composer install`
3. **Configure environment**: Copy `.env.example` to `.env`
4. **Run migrations**: `php artisan migrate`
5. **Seed database**: `php artisan db:seed`
6. **Start server**: `php artisan serve`

## 🏗️ Project Overview

AstroIndia Backend is a Laravel-based REST API that provides comprehensive astrology services including:

- **User Management**: Registration, authentication, profile management
- **Astrologer Services**: Profiles, availability, pricing, reviews
- **Location Services**: Countries, states, cities management
- **Wallet System**: Digital wallet with transactions and offers
- **Admin Panel**: Comprehensive admin interface

## 📊 Key Features

- ✅ **OTP Authentication**: Secure OTP-based user authentication
- ✅ **API Logging**: Comprehensive request/response logging with daily rotation
- ✅ **RESTful API**: Standardized JSON responses
- ✅ **Admin Panel**: Full-featured admin interface
- ✅ **Wallet System**: Digital wallet with transaction tracking
- ✅ **Location Services**: Complete location management system

## 🔧 Technology Stack

- **Framework**: Laravel 8+
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful API with JSON responses
- **Logging**: Custom API logging middleware

## 📁 Project Structure

```
astroindia_backend/
├── app/Http/Controllers/Api/     # API Controllers
├── app/Http/Controllers/Admin/   # Admin Controllers
├── app/Models/                   # Eloquent Models
├── app/Http/Middleware/          # Custom Middleware
├── config/                       # Configuration Files
├── database/migrations/          # Database Migrations
├── routes/api.php               # API Routes
├── docs/                        # 📚 Documentation
└── storage/logs/                # Application Logs
```

## 📖 Documentation Index

- **[📚 Complete Documentation](docs/README.md)** - Main documentation index
- **[🚀 API Resources](docs/API_RESOURCES_DOCUMENTATION.md)** - API endpoints
- **[📋 API Response Structure](docs/API_RESPONSE_STRUCTURE.md)** - Response formats
- **[🔮 Astrologer API](docs/ASTROLOGER_API_DOCUMENTATION.md)** - Astrologer endpoints
- **[🔐 OTP Authentication](docs/OTP_AUTHENTICATION.md)** - Authentication system
- **[⚙️ Admin Setup](docs/ADMIN_SETUP.md)** - Admin panel setup
- **[🎨 Admin Assets](docs/ADMIN_JS_CSS_README.md)** - Frontend assets
- **[📝 API Logging](docs/API_LOGGING_MIDDLEWARE.md)** - Logging system

## 🔍 Logging & Monitoring

The application includes a comprehensive API logging system:

- **Daily log rotation**: `storage/logs/api-YYYY-MM-DD.log`
- **Request/response logging**: Complete API activity tracking
- **Security**: Automatic filtering of sensitive data
- **Performance**: Configurable size limits and exclusions

## 🤝 Contributing

When adding new features or making changes:

1. **Update relevant documentation** in the `docs/` folder
2. **Follow Laravel conventions** for code structure
3. **Add appropriate logging** for new API endpoints
4. **Update API documentation** for new endpoints

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)
- [Hyper Host](https://hyper.host)
- [Appoly](https://www.appoly.co.uk)
- [OP.GG](https://op.gg)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
