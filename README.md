# URL Shortener PHP Application

**A clean, fast, and fully functional URL shortening service built with PHP, Bootstrap, and vanilla JavaScript.**

## 🚀 Quick Overview

This project provides the frontend interface (`index.php`) and backend logic (`shorten.php`, `redirect.php`) for a basic URL shortener.

**⚠️ IMPORTANT NOTE ON DEPLOYMENT:**
This application is **not** a static site. It relies on **PHP** to execute server-side logic (database connection, link generation, and redirection). Therefore, **it cannot be hosted on platforms like GitHub Pages, Netlify, or Vercel.**

To run this application, you must use a hosting service that supports **PHP** and provides a **MySQL or MariaDB** database (e.g., shared hosting, VPS, or a local server like XAMPP/WAMP/MAMP).

---

## ✨ Features

* **URL Shortening:** Converts long URLs into concise, unique short codes.
* **Redirection:** Handles short code lookups and redirects users to the original long URL.
* **Responsive UI:** Modern, fully responsive interface using Bootstrap 5.
* **Creative Error Handling:** Custom, engaging error messages for invalid or missing links.
* **Copy Functionality:** One-click copy for the generated short URL.

---

## 🛠️ Prerequisites

Before deploying, ensure your environment meets these requirements:

1.  **PHP** (version 7.4 or higher recommended)
2.  **MySQL** or **MariaDB** database.
3.  A Web Server (Apache or Nginx).

---

## ⚙️ Setup and Configuration

Follow these steps to get your URL shortener running on a PHP-enabled web server.

### Step 1: Database Setup

You will need a database named, for example, `url_shortener`. Inside this database, create a table named `short_urls`.

This table is essential as it stores the relationship between the short codes and the long URLs.

```sql
CREATE TABLE short_urls (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    short_code VARCHAR(10) NOT NULL UNIQUE,
    long_url TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
````

### Step 2: Configure `config.php` (CRITICAL)

The application relies on the `config.php` file to connect to your database and define the base URL for the shortened links. You **MUST** edit the following placeholders in your local `config.php` file with your actual credentials and domain information.

| Constant | Description | Line to Edit | Example Value |
| :--- | :--- | :--- | :--- |
| **`DB_HOST`** | The host address for your database server (e.g., `localhost`). | **Line 11** | `'localhost'` |
| **`DB_NAME`** | The database name you created in Step 1. | **Line 12** | `'DatabaseName'` |
| **`DB_USER`** | The username used to access your database. | **Line 13** | `'root'` |
| **`DB_PASS`** | The password for the database user. | **Line 14** | `'Password'` |
| **`BASE_URL`** | The public, root URL where the application will be accessed (e.g., `http://localhost/LinkShortner`). **This is crucial for generating working short links.** | **Line 22** | `'http://localhost/LinkShortner'` |

### Step 3: Deployment

1.  Upload all four files (`index.php`, `shorten.php`, `redirect.php`, `config.php`) to the root directory of your web server (e.g., `public_html`).
2.  Ensure that the PHP code can connect to the database using the credentials you provided in Step 2.
3.  Navigate to your public URL (`BASE_URL`) in a web browser to test the shortener.

-----

## 🤝 Contribution & Credit

This project was developed and maintained by **Vihanga Nethmaka** <https://github.com/VihangaNethmaka>

-----

### License **MIT**

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
