# Data Importer

A Laravel project designed for data imports with dynamic file support, user roles, and permissions. This project uses Laravel AdminLTE for UI and Maatwebsite Excel for handling Excel and CSV files.

## Requirements

To set up and run this project locally, ensure you have the following installed:

- **PHP**: ^8.2
- **Composer**: Latest version
- **Node.js**: >= 18.6.0
- **NPM**: Latest version
- **MySQL**: For database setup

## Installation Guide

Follow these steps to set up the project:

### Step 1: Clone the Repository

```bash
git clone https://github.com/mirkodzudzar/data-importer.git
cd data-importer
```

### Step 2: Install PHP Dependencies

Run the following command to install the required PHP packages:

```bash
composer install
```

### Step 3: Configure the `.env` File

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Update the `.env` file with your configuration:

- Set up your MySQL database:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=data_importer
  DB_USERNAME=root
  DB_PASSWORD=your_password
  ```
- Set the mail driver to log or configure an email service:
  ```
  MAIL_MAILER=log
  ```

- Optionally, set the `APP_URL` to match your local development server:
  ```
  APP_URL=http://localhost
  ```

### Step 4: Generate Application Key

Run the following command to generate the application key:

```bash
php artisan key:generate
```

### Step 5: Install Node.js Dependencies

Run the following commands to install and build the frontend assets:

```bash
npm install
npm run build
```

> If you're running the project in development mode, you can use `npm run dev` to enable live reloading.

### Step 6: Set Up the Database

Run the database migrations and seed the database:

```bash
php artisan migrate:fresh --seed
```

This will create the necessary tables and seed some example data, including:

- A user with full permissions:
  - **Email**: `john1@doe.com`
  - **Password**: `password`
- A user with no permissions:
  - **Email**: `john2@doe.com`
  - **Password**: `password`

### Step 7: Start the Queue Worker

Ensure the queue worker is running as the project uses queues for processing imports:

```bash
php artisan queue:work --tries=3
```

### Step 8: Serve the Application

Run the development server:

```bash
php artisan serve
```

Visit the application at the URL specified in your terminal (e.g., `http://127.0.0.1:8000`).

## Features

- Dynamic file imports for multiple types and formats (Excel, CSV)
- Laravel AdminLTE UI integration
- Role-based access control with permissions
- Queue-based import processing
- Comprehensive error logging for import failures

## Development Commands

 **Run Vite Dev Server**: For live reloading:
  ```bash
  npm run dev
  ```

## Notes

- Ensure that the database is created and credentials are correct before running migrations.
- If the mail configuration isn't set, email notifications for import failures will be logged to the `laravel.log` file.

---

For any further questions or issues, please feel free to contact mirkodzudzar@gmail.com
