# PHP_Laravel12_Dynamic_Error_Pages_Using_React
## Overview

This project demonstrates how to build dynamic and modern error pages in Laravel 12 using React.js and Vite.
Instead of Laravel’s default static error pages, this system renders React components for different HTTP errors such as 404, 403, and 500.

The goal of this project is to help developers understand how to integrate React with Laravel for UI rendering and handle exceptions with custom designs.

---

## Features

* React‑based dynamic error pages
* Custom pages for 404, 403, and 500 errors
* Laravel Exception Handler integration
* Vite fast development environment
* Clean project structure
* Beginner friendly setup
* Responsive UI layout

---

## Technology Stack

* PHP 8 or Higher
* Laravel 12
* React 18
* Vite
* JavaScript (ES6+)
* Blade Templates

---

## Installation Steps

### Step 1: Create Project

```bash
composer create-project laravel/laravel laravel-react-error
cd laravel-react-error
```

### Step 2: Install React and Dependencies

```bash
npm install
npm install react react-dom
npm install @vitejs/plugin-react --save-dev
```

---

### Step 3: Configure Vite

Create or update `vite.config.js`:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
});
```

---

### Step 4: React Entry File

Create `resources/js/app.jsx` and mount error pages based on HTTP code.

---

### Step 5: Create React Error Pages

Create directory:

```bash
mkdir -p resources/js/pages
```

Create the following components:

* `Error404.jsx`
* `Error403.jsx`
* `Error500.jsx`

Each component contains its own inline styling and UI logic.

---

### Step 6: Create Blade Layout

Directory:

```bash
mkdir -p resources/views/errors
```

File: `resources/views/errors/layout.blade.php`

This layout loads Vite assets and passes the HTTP error code to React using `data-page` attribute.

---

### Step 7: Create Error Blade Views

Files:

```
resources/views/errors/404.blade.php
resources/views/errors/403.blade.php
resources/views/errors/500.blade.php
```

Each file simply sets the code and includes the layout.

Optional generic view:

```
resources/views/errors/generic.blade.php
```

---

### Step 8: Update Exception Handler

Modify `app/Exceptions/Handler.php` to detect HTTP exceptions and load the proper Blade error view or fallback generic view.

---

### Step 9: Update Routes

Update `routes/web.php` to add test routes for 403, 404, and 500 errors and a fallback route for undefined URLs.

---

### Step 10: Create Welcome Page

Create `resources/views/welcome.blade.php` with buttons to simulate error pages and demonstrate functionality.

---

### Step 11: Update package.json Scripts

```json
{
  "private": true,
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "watch": "vite build --watch"
  },
  "devDependencies": {
    "@vitejs/plugin-react": "^4.0.0",
    "laravel-vite-plugin": "^1.0.0",
    "vite": "^5.0.0"
  },
  "dependencies": {
    "react": "^18.2.0",
    "react-dom": "^18.2.0"
  }
}
```

---

### Step 12: Run the Project

Terminal 1:

```bash
php artisan serve
```

<img width="954" height="707" alt="image" src="https://github.com/user-attachments/assets/9f5a51e2-cf35-4154-9c78-2cd9e0ac1cb5" />

<img width="1908" height="963" alt="image" src="https://github.com/user-attachments/assets/8da644ee-a3a9-4da2-bb01-8dc924782109" />

<img width="1188" height="511" alt="image" src="https://github.com/user-attachments/assets/426b1c33-5e12-4d5e-a00e-34b5c3eb891c" />

Terminal 2:

```bash
npm run dev
```

---

## Project Structure

```
laravel-react-error/
app/
  Exceptions/
    Handler.php
resources/
  js/
    app.jsx
    pages/
      Error404.jsx
      Error403.jsx
      Error500.jsx
  views/
    welcome.blade.php
    errors/
      layout.blade.php
      404.blade.php
      403.blade.php
      500.blade.php
      generic.blade.php
routes/
  web.php
vite.config.js
package.json
composer.json
```

---

## How It Works

1. Laravel catches an exception
2. Exception Handler checks HTTP status code
3. Blade layout loads React entry file
4. React renders the matching error component
5. User sees a dynamic styled error page

---

## Use Cases

* Portfolio Projects
* MCA / BCA Final Year Projects
* Interview Demonstrations
* Learning Laravel + React Integration

---

## Future Improvements

* Add animations
* Add multi‑language support
* Dark mode support
* Role‑based error screens

---

## Requirements

* PHP 8 or Higher
* Composer
* Node.js
* NPM
* Internet Connection

---

## Author

Mihir Mehta

---

## License

MIT License

