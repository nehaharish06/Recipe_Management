# 🍳 Recipe Management System

A web-based Recipe Management System built with **PHP, MySQL, HTML, CSS, and JavaScript**.  
This project allows users to manage recipes, ingredients, rate recipes, and explore stepwise cooking instructions with attached video links.

---

## 🚀 Features

- 🔑 **User Authentication** – Login/Registration with session handling.  
- 📖 **Recipe CRUD** – Add, update, delete, and view recipes.  
- 🥗 **Ingredients Management** – Link recipes with ingredients.  
- 📝 **Stepwise Instructions** – Recipes displayed step by step.  
- 🎥 **Video Links** – Attach cooking videos for guidance.  
- ⭐ **Recipe Ratings** – Users can rate recipes.  
- 📊 **Triggers & Avg Rating** – Automatic calculation of average ratings using SQL triggers.  
- 👤 **User Profile** – Profile with profile picture.  

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL (via XAMPP)  

---

## ⚙️ Setup Instructions

1. **Install XAMPP** and start Apache + MySQL.  
2. Place the project folder inside the `htdocs` directory (e.g., `C:\xampp\htdocs\rm`).  
3. Open **phpMyAdmin** and create the database:  

   ```sql
   CREATE DATABASE recipe_management;
4. Import the SQL schema from the included file:

   - Go to phpMyAdmin → Import

   - Choose query.sql

   - Import into recipe_management

5. Update db_connection.php with your MySQL credentials (default: root with no password).

6. Start the server and open the project in your browser:

---

## 🌐 API Usage

This project includes a basic API to fetch recipes from the recipe_management database.

Endpoint:

http://localhost/rm/api/get_recipes.php




