Laravel 12 + Vue 3 Project

This is a Laravel 12 project with Vue 3 frontend. Follow these instructions to set up and run the project locally.

Requirements

Before you begin, ensure you have the following software installed:

PHP >= 8.2

Composer

Node.js >= 18

npm >= 8

MySQL or any supported database

Installation & Setup

1. Clone the repository

If you are cloning an existing repository:

git clone <your-repo-url>
cd <your-project-folder>


1b. Getting Started with Git (If starting a new project)

If you have downloaded this code and are initializing a new Git repository, follow these steps:

# 1. Initialize a new Git repository
git init

# 2. Add all files to the staging area
git add .

# 3. Commit the initial files
git commit -m "Initial commit of Laravel 12 + Vue 3 base project"

# 4. Link to your remote repository (replace <your-repo-url>)
git remote add origin <your-repo-url>

# 5. Push the commit to the main branch
git push -u origin main


2. Install PHP dependencies

Use Composer to install the backend dependencies:

composer install


3. Copy the .env file

Create your environment configuration file:

cp .env.example .env


4. Configure the database

Edit the .env file and set your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password


5. Run migrations and seeders

This command will create all necessary database tables and populate them with initial data, including a test admin user:

php artisan migrate --seed


Test User Credentials:

Email: admin@gmail.com

Password: 123456

6. Install Node.js dependencies

Install the frontend dependencies required for Vue and asset compilation:

npm install


Running the Project

7. Run frontend development server

Start the Vite development server to compile and hot-reload your Vue assets:

npm run dev


8. Run Laravel server

Start the local PHP development server:

php artisan serve


The project will now be available in your browser at: http://127.0.0.1:8000

Additional Commands

Command

Description

php artisan migrate:fresh --seed

Reset the database, drop all tables, re-run migrations, and re-seed.

npm run build

Build production-ready frontend assets.
