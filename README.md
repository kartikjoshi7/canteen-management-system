# 🍔 Online Canteen Management System

**PHP • JSON • Full-Stack Web Application**

A modern **click-and-collect canteen ordering system** built using **Core PHP and JSON**.  
Designed to eliminate long queues by allowing students to place orders online while admins manage everything in real time.

> Academic Project — GTU Design Engineering (5th Semester)

---

## ✨ Overview

### Problem
Traditional canteen systems rely on manual tokens, leading to:
- Long queues during short breaks  
- Inefficient order handling  
- No real-time visibility for admins  

### Solution
A **web-based canteen system** where:
- Students order food online from their devices  
- Orders are stored digitally using JSON  
- Admins manage menu items and order status from a dashboard  

---

## 🚀 Key Features

- **No Database Required**  
  File-based JSON storage used as a lightweight NoSQL solution

- **Role-Based Access**  
  Separate interfaces for **Students** and **Admins**

- **Responsive UI**  
  Works smoothly on mobile and desktop screens

- **Live Order Workflow**  
  `Pending → Cooking → Completed`

---

## 🧱 Technology Stack

**Frontend**
- HTML5  
- CSS3 (Flexbox & Grid)  
- JavaScript (Vanilla)

**Backend**
- PHP (Core PHP, no framework)

**Data Storage**
- JSON (File-based persistence)

**Server**
- Apache (XAMPP)

---

## 📂 Project Structure

```text
canteen_system/
│
├── admin/
│   ├── add_food.php
│   ├── edit_food.php
│   ├── food_items.php
│   ├── dashboard.php
│   ├── orders.php
│   └── users.php
│
├── assets/
│   ├── css/style.css
│   ├── js/script.js
│   └── images/
│       ├── burger.png
│       ├── coffee.png
│       ├── dosa.png
│       └── sandwich.png
│
├── data/
│   ├── food_items.json
│   ├── orders.json
│   └── mock_data.php
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
│
├── cart.php
├── checkout.php
├── index.php
├── login.php
├── logout.php
├── menu.php
└── my_orders.php
```
## 👨‍🎓 Student Module

- Digital menu with food images and prices

- Smart cart with quantity controls

- Mobile-friendly sticky checkout button

- Order history with live status tracking

## 👮‍♂️ Admin Module

- Dashboard with order statistics

- Update order status (Cooking / Completed / Cancelled)

- Add, edit, and delete food items

- View registered users

## ▶️ Getting Started
### Prerequisites

- XAMPP (Apache + PHP)

### Installation
```bash
git clone https://github.com/kartikjoshi7/canteen-management.git
```
1. Move the project folder to:
```bash
C:\xampp\htdocs\
```
2. Start Apache from XAMPP Control Panel
3. Open in browser:
```bash
http://localhost/canteen_system/
```
## 🔑 Demo Credentials
| Role     | Username | Password |
|----------|----------|----------|
| Admin    | admin    | admin    |
| Student  | student  | 1234     |

## 🔮 Future Enhancements

- MySQL database integration

- Online payment gateway (Razorpay / Stripe)

- SMS / WhatsApp order notifications

- User profile & password management

## ⚠️ Design Choice Note

- JSON storage is intentionally used to avoid database dependency and clearly demonstrate backend logic.
- For production-scale systems, SQL-based persistence is recommended.

## 👨‍💻 Author

**Kartik Joshi**  

B.E. – Information Technology  

Gujarat Technological University (GTU)

## ⭐ Why This Project Stands Out

- End-to-end full-stack implementation

- Clear separation of concerns

- Real-world problem solving

- Suitable for academic evaluation and portfolios
---
If you find this project useful, consider giving it a ⭐ and exploring the code.