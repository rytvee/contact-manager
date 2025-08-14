
# 📇 PHP Contact Manager

A lightweight **PHP**-based contact manager for storing and managing **📧 emails** and **📱 phone numbers**.  
Perfect for small projects, personal contact lists, or learning basic **CRUD** operations in PHP.


## ✨ Features

- ➕ **Add Contacts** – Save name, 📧 email, and 📱 phone number.
- 👀 **View Contacts** – Display all saved contacts in a neat table.
- ✏️ **Edit Contacts** – Update existing contact details.
- ❌ **Delete Contacts** – Remove unwanted entries.
- 🔍 **Search Contacts** – Find contacts by name, email, or phone number.
- 🎨 **Responsive styling** – Adjusts to different screen sizes.
- 🔒 **Secured** – Prepared statements are used to prevent SQL injection.
- 💾 **Persistent Storage** – Stores data in **MySQL** or local file.


## 📂 Folder Structure

```text
php-contact-manager/
│
├── index.php       # Main dashboard for viewing contacts
├── create.php      # Form for adding a contact
├── edit.php        # Edit existing contact
├── delete.php      # Delete contact
├── search.php      # Search contacts
├── setup.php       # Create database and table
├── db.php          # Database connection file
├── css/
│  └── style.css # Styling
└── README.md # Documentation
```


## 🚀 Getting Started

### 1️⃣ Requirements
- 🐘 PHP 7.4+  
- 🗄️ MySQL / MariaDB  
- 🌐 Web server (Apache/Nginx or PHP built-in server)

### 2️⃣ Installation
1. **Clone the repository**
   ```
   git clone https://github.com/yourusername/php-contact-manager.git
   cd php-contact-manager
   ```
2. **Configure the database**
- Edit `setup.php` and `db.php`:

```
$host = "localhost";
$username = "root";
$password = "";
$dbname = "contact_manager";
```

3. **Run the project**

```
-S localhost:8000
```
Then visit http://localhost:8000 🌍


## 📜 License
This project is free to use and modify.
