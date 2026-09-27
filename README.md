# Student Management System — Laravel REST API

A backend system for managing student records, attendance, and grades
through a role-protected REST API, built with Laravel and MySQL.

## Features

- Full CRUD REST API for students, attendance, and grades
- Role-based access control (`admin`, `staff`, `student`) via a custom middleware
- Token-based API authentication with Laravel Sanctum
- Form Request validation with proper error responses
- Auto-calculated grade letters (A+, A, B, C, D, F) via Eloquent model events
- Search, filtering, and pagination on the students endpoint
- Attendance percentage computed as a model accessor
- Feature tests covering auth, role restrictions, and business logic

## Tech Stack

Laravel 11, PHP 8.2+, MySQL, Sanctum, PHPUnit

---

## Setup — run these commands on your laptop (needs internet for Composer)

### 1. Create a fresh Laravel project

```bash
composer create-project laravel/laravel student-management-system
cd student-management-system
```

### 2. Install Sanctum (for API token authentication)

```bash
composer require laravel/sanctum
php artisan install:api
```

### 3. Copy in this project's files

Copy the contents of this package into your new Laravel project,
**overwriting/merging** into the matching folders:

```
app/Models/Student.php              -> app/Models/
app/Models/Attendance.php           -> app/Models/
app/Models/Grade.php                -> app/Models/
app/Http/Controllers/Api/*.php      -> app/Http/Controllers/Api/
app/Http/Middleware/EnsureUserHasRole.php -> app/Http/Middleware/
app/Http/Requests/StoreStudentRequest.php -> app/Http/Requests/
database/migrations/*.php           -> database/migrations/
database/factories/StudentFactory.php -> database/factories/
database/seeders/StudentSeeder.php  -> database/seeders/
routes/api.php                      -> routes/ (replace the default one)
tests/Feature/StudentApiTest.php    -> tests/Feature/
```

### 4. Register the role middleware

In `bootstrap/app.php`, inside `->withMiddleware(function (Middleware $middleware) {...})`, add:

```php
$middleware->alias([
    'role' => \App\Http\Middleware\EnsureUserHasRole::class,
]);
```

### 5. Configure your database

Edit `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_management
DB_USERNAME=root
DB_PASSWORD=
```

Create the database:

```bash
mysql -u root -p -e "CREATE DATABASE student_management;"
```

### 6. Run migrations and seed demo data

```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\StudentSeeder
```

### 7. Create a user for testing (in `php artisan tinker`)

```php
$admin = \App\Models\User::factory()->create(['role' => 'admin', 'email' => 'admin@test.com', 'password' => bcrypt('password')]);
$token = $admin->createToken('test-token')->plainTextToken;
echo $token;
```

### 8. Run the server

```bash
php artisan serve
```

### 9. Test the API (e.g. with Postman or curl)

```bash
curl -H "Authorization: Bearer <token-from-step-7>" \
     -H "Accept: application/json" \
     http://127.0.0.1:8000/api/students
```

### 10. Run the tests

```bash
php artisan test
```

---

## API Endpoints

| Method | Endpoint | Access |
|---|---|---|
| GET | `/api/students` | Any authenticated user |
| GET | `/api/students/{id}` | Any authenticated user |
| POST | `/api/students` | admin, staff |
| PUT | `/api/students/{id}` | admin, staff |
| DELETE | `/api/students/{id}` | admin, staff |
| GET | `/api/students/{id}/attendance` | Any authenticated user |
| POST | `/api/students/{id}/attendance` | admin, staff |
| GET | `/api/students/{id}/grades` | Any authenticated user |
| POST | `/api/students/{id}/grades` | admin, staff |

## Possible Extensions

- Admin web UI (Blade or a separate frontend) on top of this API
- Export attendance/grade reports to PDF/Excel
- Email notifications for low attendance
