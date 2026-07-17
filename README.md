# 🍔 Online Canteen Management System

A full-stack **click-and-collect canteen ordering system** that digitizes the entire food ordering workflow — from browsing the menu to real-time order tracking — built with **Core PHP** and **MySQL**.

> Academic Project — GTU Design Engineering

---

## 📌 What This Project Does

This system replaces the traditional **manual token-based** canteen process with a **web-based ordering platform**. It provides two separate interfaces:

- **Students** can browse the menu, add items to a cart, place orders, and track their order status in real time — all from their phone or laptop.
- **Admins** get a dedicated dashboard to manage the menu, view incoming orders, update order statuses (`Pending → Cooking → Completed`), and monitor registered users.

Every order generates a unique **token number**, so students know exactly when their food is ready — no more standing in line.

---

## 🧩 How It Works

```
Student opens menu → Adds items to cart → Places order → Gets a token number
                                                              ↓
                              Admin sees the order on dashboard → Updates status
                                                              ↓
                              Student checks "My Orders" → Picks up food when ready
```

---

## 🚀 Key Features

| Feature | Description |
|---|---|
| **Role-Based Access** | Separate login and UI for Students and Admins |
| **Digital Menu** | Food items with images, descriptions, and prices |
| **Smart Cart** | Add/remove items, adjust quantities before checkout |
| **Token System** | Every order gets a unique token for easy pickup |
| **Live Order Tracking** | Status updates: `Pending → Cooking → Completed` |
| **Admin Dashboard** | Order stats, menu CRUD, user management |
| **Responsive Design** | Works on mobile and desktop |
| **Password Hashing** | Uses `password_hash()` with self-healing for legacy entries |

---

## 🧱 Technology Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3 (Flexbox & Grid), Vanilla JavaScript |
| Backend | PHP (Core PHP, no framework) |
| Database | MySQL (via MySQLi) |
| Server | Apache (XAMPP) |

---

## 🗄️ Database Schema

The system uses **3 core tables**:

```
┌──────────────┐     ┌──────────────────┐     ┌──────────────┐
│    users     │     │     orders       │     │  food_items  │
├──────────────┤     ├──────────────────┤     ├──────────────┤
│ id           │     │ id               │     │ id           │
│ username     │     │ token_number     │     │ name         │
│ email        │     │ student_name     │     │ price        │
│ phone        │     │ items            │     │ description  │
│ password     │     │ total_price      │     │ image        │
│ role (enum)  │     │ status (enum)    │     └──────────────┘
└──────────────┘     │ created_at       │
                     └──────────────────┘
```

- **users** — Stores student and admin accounts with hashed passwords
- **orders** — Tracks every order with its token, items, total, status, and timestamp
- **food_items** — Menu catalog with names, prices, descriptions, and image references

---

## 📂 Project Structure

```
canteen_system/
│
├── admin/                  # Admin panel
│   ├── add_food.php        # Add new food item to menu
│   ├── add_vendor.php      # Register a new vendor/admin
│   ├── edit_food.php       # Edit existing food item
│   ├── food_items.php      # View & manage all food items
│   ├── dashboard.php       # Admin dashboard with stats
│   ├── orders.php          # View & update order statuses
│   └── users.php           # View registered users
│
├── assets/
│   ├── css/style.css       # All styling
│   ├── js/script.js        # Client-side interactions
│   └── images/             # Food item images
│
├── includes/
│   ├── db_connect.php      # MySQL connection config
│   ├── header.php          # Shared navigation & session logic
│   ├── footer.php          # Shared footer
│   └── functions.php       # Reusable PHP helper functions
│
├── canteen_db.sql           # Database dump (import this to set up)
├── index.php                # Landing page
├── login.php                # User login
├── signup.php               # Student registration
├── logout.php               # Session destroy
├── menu.php                 # Browse food menu
├── cart.php                 # Shopping cart
├── checkout.php             # Place order & generate token
└── my_orders.php            # Student order history & tracking
```

---

## 👨‍🎓 Student Module

- Browse the digital menu with food images, descriptions, and prices
- Add items to a smart cart with quantity controls
- Place orders and receive a unique token number
- Track order status (`Pending → Cooking → Completed`) on the My Orders page

## 👮‍♂️ Admin Module

- Dashboard with live order statistics
- Update order status (Cooking / Completed / Cancelled)
- Full CRUD operations on food items (Add, Edit, Delete)
- View all registered users and their details

---

## ▶️ Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP)

### Installation

```bash
git clone https://github.com/kartikjoshi7/canteen-management.git
```

1. Move the `canteen_system` folder to `C:\xampp\htdocs\`
2. Start **Apache** and **MySQL** from XAMPP Control Panel
3. Open [phpMyAdmin](http://localhost/phpmyadmin), create a database named **`canteen_db`**, and import `canteen_db.sql`
4. Open in browser:
```
http://localhost/canteen_system/
```

### Demo Credentials

| Role | Username | Password |
|---|---|---|
| Admin | admin | admin |
| Student | student | 1234 |

---

## 📊 Presentation Topics

Key points to cover when presenting this project:

- **Problem Statement** — Inefficiencies, long queues, and manual errors in traditional token-based canteen systems
- **Proposed Solution** — A click-and-collect web application with real-time ordering and status tracking
- **System Architecture** — Separate modules for Students (menu, cart, tracking) and Admins (dashboard, inventory, orders)
- **Engineering Evolution** — Initially prototyped with JSON file-based storage for rapid development, later migrated to MySQL for relational data integrity and scalability
- **Impact** — Eliminates waiting time for students and gives admins a clear, organized, real-time workflow

---

## 🔮 Future Scope

- Online payment gateway integration (Razorpay / Stripe)
- SMS / WhatsApp notifications when order is ready
- User profile and password management
- Multi-vendor support within a single canteen system

---

## 👨‍💻 Author

**Kartik Joshi**  
B.E. — Information Technology  
Gujarat Technological University (GTU)

---

If you find this project useful, consider giving it a ⭐ on GitHub.