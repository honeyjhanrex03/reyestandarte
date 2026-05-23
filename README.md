# Reynaldo Estandarte Jr. - Professional Portfolio & CMS

A completely custom-built, database-driven Personal Portfolio website equipped with a secure backend Content Management System (CMS). Built to showcase projects, skills, services, and certificates with a modern, glassmorphic UI.

## 🚀 Features
- **Dynamic Content Management**: Fully functional Admin Dashboard with CRUD capabilities to manage Projects, Skills, Services, and Certificates.
- **Secure Backend**: Built with PHP 8+ and PDO prepared statements to protect against SQL injections.
- **Enterprise Email Routing**: Integrated with the Brevo (Sendinblue) API to handle real-time contact form submissions, including automated professional responses to the sender and instant admin notifications.
- **Modern UI/UX**: Premium aesthetic featuring custom CSS variables, Bootstrap 5 grid systems, smooth image carousels, and responsive design for all devices.
- **Performance Optimized**: Implemented dynamic cache-busting across all stylesheets to guarantee users always receive the latest design updates.

## 💻 Tech Stack
- **Frontend**: HTML5, CSS3, Bootstrap 5, Vanilla JavaScript, SweetAlert2
- **Backend**: PHP 8+, PDO (PHP Data Objects)
- **Database**: MySQL
- **APIs**: Brevo SMTP API

## 🛠️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/honeyjhanrex03/reyestandarte.git
   ```

2. **Database Configuration:**
   - Import the database schema located at `database/rey_portfolio.sql` into your MySQL server.
   - Or, run the `setup_db.php` script if hosting on a live server (Remember to delete it after!).

3. **API Configuration:**
   Create a file named `config.php` in the root directory (this file is git-ignored for security) and add your Brevo API key:
   ```php
   <?php
   define('BREVO_API_KEY', 'your_api_key_here');
   ```

4. **Default Admin Login:**
   - **URL:** `/admin/login.php`
   - **Username:** `admin`
   - **Password:** `admin123` *(Change this immediately upon logging in)*

## 🛡️ Security
This repository utilizes GitHub Push Protection. All sensitive API keys and configuration files are strictily tracked in `.gitignore` and kept out of the version history.
