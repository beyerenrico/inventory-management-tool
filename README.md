# Inventory Management Tool

A comprehensive multi-store inventory management system built with Laravel 11 and Filament 4.

## Features

### Multi-Store Support
- **Store-based multi-tenancy**: Create and manage multiple stores with complete data isolation
- **Role-based access control**: Owner, Admin, and Member roles with granular permissions
- **User management**: Invite users to stores and manage their roles and permissions
- **Secure authorization**: Users can only access stores they belong to

### Inventory Management
- **Product catalog**: Comprehensive product management with SKU, EAN, pricing, and stock tracking
- **Distributor management**: Track suppliers with full contact information and order history
- **Order management**: Complete order lifecycle from creation to delivery
- **Stock monitoring**: Real-time stock levels with low-stock alerts

### Analytics & Reporting
- **Dashboard widgets**: Inventory overview, low stock alerts, recent orders, and monthly spending
- **Price analysis**: Track price history, variance, and trends across distributors
- **Order history**: Complete audit trail of all product orders and transactions
- **Export functionality**: Export data in CSV and Excel formats

### Localization
- **German language support**: Fully localized interface with German translations
- **Multi-language ready**: Translation system ready for additional languages

### Advanced Features
- **Soft deletes**: Safe deletion with recovery options for all major entities
- **Import/Export**: Bulk data operations with progress tracking and error reporting
- **Responsive design**: Mobile-friendly interface built with Filament 4
- **Queue processing**: Background job processing for imports and exports

## Technology Stack

- **Laravel 11**: Modern PHP framework with latest features
- **Filament 4**: Advanced admin panel with modern UI components
- **MariaDB**: Reliable database with full text search capabilities
- **DDEV**: Development environment for consistent local development
- **Queue System**: Background job processing for heavy operations

## Installation

### Prerequisites
- PHP 8.2+
- DDEV installed
- Composer

### Setup
```bash
# Clone the repository
git clone <repository-url>
cd inventory-management-tool

# Start DDEV environment
ddev start

# Install dependencies
ddev composer install

# Set up environment
ddev exec cp .env.example .env
ddev exec php artisan key:generate

# Run migrations and seeders
ddev exec php artisan migrate
ddev exec php artisan db:seed

# Start queue worker (required for imports/exports)
ddev exec php artisan queue:work
```

### Access the Application
- Web interface: `https://inventory-management-tool.ddev.site`
- Admin panel: `https://inventory-management-tool.ddev.site/admin`

## Usage

### Getting Started
1. **Register a user account** or use seeded credentials
2. **Create your first store** through the store registration process
3. **Add distributors** to track your suppliers
4. **Import or create products** to build your inventory catalog
5. **Create orders** to track purchases and update stock levels

### Multi-Store Management
- **Store switching**: Use the tenant switcher to switch between stores
- **User invitations**: Add team members with appropriate roles
- **Permission management**: Control what users can see and edit
- **Data isolation**: Each store's data is completely separate

### Import/Export Operations
- **Bulk imports**: Import products, distributors, and orders from CSV/Excel files
- **Data validation**: Automatic validation with detailed error reporting
- **Progress tracking**: Real-time progress updates during operations
- **Export formats**: Download data in CSV or Excel formats

## Development

### DDEV Commands
```bash
# Database operations
ddev exec php artisan migrate
ddev exec php artisan migrate:fresh --seed

# Queue operations
ddev exec php artisan queue:work
ddev exec php artisan queue:restart

# Filament operations
ddev exec php artisan make:filament-resource
ddev exec php artisan make:filament-page

# Cache operations
ddev exec php artisan config:cache
ddev exec php artisan route:cache
```

### Project Structure
```
app/
├── Filament/
│   ├── Resources/         # Resource definitions for each entity
│   ├── Pages/            # Custom pages and tenant registration
│   ├── Widgets/          # Dashboard widgets and analytics
│   ├── Imports/          # Data import classes
│   └── Exports/          # Data export classes
├── Models/               # Eloquent models with relationships
└── Observers/            # Model observers for business logic

database/
├── migrations/           # Database schema definitions
└── seeders/             # Sample data for development

lang/
├── de/                  # German translations
└── en/                  # English translations (default)
```

## Deployment

### Coolify on Hetzner VPS

This application is designed to deploy easily on Coolify. Here's the configuration needed:

#### Environment Variables
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
QUEUE_CONNECTION=database
```

#### Deployment Configuration
The application includes:
- **HTTPS enforcement**: Automatically forces HTTPS in production environment
- **Proxy trust**: Configured to work with reverse proxies (Coolify/Traefik)
- **Mixed content protection**: Prevents HTTP/HTTPS mixed content issues
- **Queue processing**: Database-based queue system for background jobs

#### Post-Deployment Steps
```bash
# Run migrations
php artisan migrate --force

# Cache configuration for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start queue worker (use process manager like supervisor)
php artisan queue:work --daemon
```

#### Queue Worker Setup
For production, use a process manager like Supervisor to keep the queue worker running:

```ini
[program:inventory-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
directory=/path/to/project
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/inventory-queue.log
```

## Security Features

- **Multi-tenant data isolation**: Complete separation of store data
- **Role-based permissions**: Granular access control at resource and action levels
- **Secure user management**: Users cannot elevate their own permissions
- **Authorization checks**: Comprehensive authorization at model and resource levels
- **Soft deletes**: Safe deletion with audit trail preservation
- **HTTPS enforcement**: Automatic HTTPS redirection in production
- **Proxy trust**: Secure proxy header handling for deployment behind load balancers

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).