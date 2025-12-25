<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Project Setup Guide

### 1. Create Environment File
Copy the example environment file to create your own `.env`:

	cp .env.example .env

---

### 2. Generate Application Key
Generate the Laravel app key:

	php artisan key:generate

---

### 3. Run Migrations and Seed Data
Create the database tables and seed initial data:

	php artisan migrate --seed

---

### 4. Install PHP Dependencies
Install project dependencies via Composer:

	composer install

---

### 5. Install Node Dependencies
Install frontend dependencies:

	npm install

---

### 6. Build Assets
Build the frontend assets using Vite:

	npm run build

---

### 7. Login Credentials
Use the following credentials to login as a Super Admin:

- Email: super@sembark.com
- Password: sembark@123