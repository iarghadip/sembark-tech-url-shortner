<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Project Setup Guide

### 1. Create Environment File
Copy the example environment file to create your own `.env`:

	cp .env.example .env

### 2. Generate Application Key
Generate the Laravel app key:

	php artisan key:generate

### 3. Run Migrations and Seed Data
Create the database tables and seed initial data:

	php artisan migrate --seed

### 4. Install PHP Dependencies
Install project dependencies via Composer:

	composer install

### 5. Install Node Dependencies
Install frontend dependencies:

	npm install

### 6. Build Assets
Build the frontend assets using Vite:

	npm run build

### 7. Login Credentials
Use the following credentials to login as a Super Admin:

- Email: super@sembark.com
- Password: sembark@123

# Project Insight

**Roles**

- SuperAdmin
- Admin
- Member

**Permissions**
- can-send-invite
- can-accept-invite
- can-short-url
- can-see-all-org
- can-see-self-org
- can-see-all-url
- can-see-org-url
- can-see-self-url

**SuperAdmin**
- can-send-invite
- can-see-all-org
- can-see-all-url

**Admin**
- can-send-invite
- can-accept-invite
- can-short-url
- can-see-self-org
- can-see-org-url

**Member**
- can-accept-invite
- can-short-url
- can-see-self-url

---

**References**

- Spatie Permission Class Resolved: https://stackoverflow.com/a/78532053
- Controller Class Setup: https://stackoverflow.com/a/79636097
- Bootstrap/app.php Setup for Spatie: https://stackoverflow.com/a/78532490
- Database Seeder Lookup: https://laravel.com/docs/12.x/seeding