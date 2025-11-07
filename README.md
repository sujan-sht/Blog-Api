# Blog Application Setup

This guide will help you set up and run the Blog Application on your local system.

---

## Requirements

Before installing, ensure you have the following installed on your system:

- PHP >= 8.2
- Composer
- MySQL or any database supported by Laravel

---

## Installation Steps

1. **Clone the Repository**

   ```bash
   git clone <your-repository-url>
   cd <your-project-folder>
   
2. **Install Composer Dependencies**

   ```bash
   composer install

3. **Copy Environment File**

   ```bash
   cp .env.example .env

4. **Generate Application Key**

   ```bash
   php artisan key:generate

5. **Configure Database**
    <br>
    Open the .env file and set your database credentials:
    <br>

   ```php
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

6. **Run Migrations**

   ```bash
   php artisan migrate

7. **Seed the Database**

   ```bash
   php artisan db:seed
   ```
   This will seed two users with admin and author role:
   ```
   email:admin@example.com
   password:password
   ```
   ```
   email:author@example.com
   password:password
   ```
8. **Serve the Application**

   ```bash
   php artisan serve

## Accessing the Application
After completing the above steps, the project is installed successfully. You can log in using the credentials above.

## API Documentation

You can access the API documentation here:
<br>
[View API Documentation](https://documenter.getpostman.com/view/18350422/2sB3WsPeuD)
