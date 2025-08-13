
# 📇 PHP Contact Manager

A lightweight **PHP**-based contact manager for storing and managing **📧 emails** and **📱 phone numbers**.  
Perfect for small projects, personal contact lists, or learning basic **CRUD** operations in PHP.

---

## ✨ Features

- ➕ **Add Contacts** – Save name, 📧 email, and 📱 phone number.
- 👀 **View Contacts** – Display all saved contacts in a neat table.
- ✏️ **Edit Contacts** – Update existing contact details.
- ❌ **Delete Contacts** – Remove unwanted entries.
- 🔍 **Search Contacts** – Find contacts by name, email, or phone number.
- 💾 **Persistent Storage** – Stores data in **MySQL** or local file.

---

## 📂 Folder Structure

php-contact-manager/
│
├── 📄 index.php # Main dashboard for viewing contacts
├── ➕ add.php # Form for adding a contact
├── ✏️ edit.php # Edit existing contact
├── ❌ delete.php # Delete contact
├── 🔍 search.php # Search contacts
├── 🗄️ db.php # Database connection file
├── 🎨 style.css # Styling
└── 📜 README.md # Documentation

yaml
Copy code

---

## 🚀 Getting Started

### 1️⃣ Requirements
- 🐘 PHP 7.4+  
- 🗄️ MySQL / MariaDB  
- 🌐 Web server (Apache/Nginx or PHP built-in server)

### 2️⃣ Installation
1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/php-contact-manager.git
   cd php-contact-manager
Create the database

sql
Copy code
CREATE DATABASE contact_manager;
USE contact_manager;

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL
);
Configure database

Edit db.php:

php
Copy code
$host = "localhost";
$username = "root";
$password = "";
$dbname = "contact_manager";
Run the project

bash
Copy code
php -S localhost:8000
Then visit http://localhost:8000 🌍

🛠 Customization
🎨 Edit style.css for colors & theme.

📝 Add extra fields (address, company, notes).

📤 Add CSV export/import for contacts.

📌 Live Demo
(Add link if hosted online)

📄 License
Licensed under the MIT License — ✅ free to use and modify.

💡 Tips
✔️ Validate email & phone number inputs.

🔒 Use prepared statements to prevent SQL injection.

📦 Back up your database regularly.
