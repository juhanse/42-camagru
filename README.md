<div align="center">
  <h1>📸 Camagru</h1>
  <p><strong>A lightweight Instagram-like web application built from scratch in Vanilla PHP.</strong></p>

  [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)]()
  [![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)]()
  [![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)]()
  [![Vanilla JS](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)]()
</div>


## 🎯 What is Camagru?

**Camagru** is a full-stack web development project from the **42 School** curriculum. The goal is to build a complete social-media web application where users can take pictures, apply filters (stickers), and interact with others' posts.

The major constraint of this project is that **no frameworks or libraries are allowed** (no Laravel, React, or ORM). Everything, from the MVC architecture to the database routing and security, must be built from scratch. 

This project demonstrates a deep understanding of core web technologies, application architecture, and web security.


## ✨ Features & Functionalities

Here is a quick overview of what the application can do:

- **🔐 User Authentication System:**
  - Secure registration and login.
  - Email verification upon registration.
  - "Forgot Password" functionality with email reset links.
  
- **📷 The Studio (Montage):**
  - Use the webcam to take a picture or upload a local image file.
  - Superpose selectable stickers/filters onto the image.
  - Real-time preview of the resulting photo.
  - Delete past montages.

- **🌍 Public Gallery:**
  - View all user-created photos in a paginated gallery.
  - Like and comment on pictures.
  - Receive an email notification when someone comments on your photo.

- **👤 Profile Management:**
  - Update username, email address, and password.
  - Notification preferences.

- **🛡️ Moderation (Admin):**
  - Users can report inappropriate content.
  - Admin panel to manage reported images.


## 🏗️ Architecture & Stack

To keep the application modular, scalable, and maintainable without a framework, I implemented a custom **Model-View-Controller (MVC)** architecture.

### Technical Stack
* **Backend:** PHP (Vanilla)
* **Frontend:** HTML5, CSS3, Vanilla JavaScript
* **Database:** MariaDB / MySQL
* **Infrastructure:** Docker & Docker Compose
* **Email Testing:** Mailhog

### Core Concepts Handled
* **Routing:** Custom HTTP router to map URLs to Controllers.
* **Database Abstraction:** Custom PDO wrapper for secure database interactions.
* **Templating:** Basic view rendering logic separating PHP backend logic from HTML presentation.
* **Image Processing:** Manipulating and merging images using the PHP GD library.


## 🔒 Security Measures

Since no framework was used, all security vulnerabilities had to be handled manually. The application is secured against common web threats:
- **SQL Injections:** Prevented using strict **PDO Prepared Statements** everywhere.
- **XSS (Cross-Site Scripting):** All user inputs and outputs are sanitized (e.g., using `htmlspecialchars`).
- **CSRF (Cross-Site Request Forgery):** Implemented anti-CSRF tokens for sensitive forms (like password resets).
- **Password Hashing:** Passwords are securely hashed using `bcrypt` (via `password_hash`).


## 🚀 Getting Started

The project is fully dockerized for a seamless setup experience. 

### Prerequisites
Make sure you have **Docker** and **Docker Compose** installed on your machine, along with `make`.

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/juhanse/42-camagru.git
   cd camagru
   ```

2. **Build and start the containers**
   Using the provided Makefile, you can launch the entire stack:
   ```bash
   make build
   ```
   *This command spins up the Web server, Database, and Mailhog containers, and automatically runs the database setup script.*

3. **Access the application**
   - **App:** [http://localhost:8080](http://localhost:8080)
   - **Mailhog (to view sent emails):** [http://localhost:8025](http://localhost:8025)

4. **Useful Commands**
   - `make start`: Start the existing containers.
   - `make clean`: Stop the containers and wipe the database volumes.
   - `make re`: Rebuild the whole environment from scratch.
