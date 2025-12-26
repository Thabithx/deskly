# Deskly - Premium Desk Wellness E-Commerce Platform

<div align="center">

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://www.w3.org/Style/CSS/)

</div>

<br />

<div align="center">
  <p align="center">
    A full-stack e-commerce ecosystem built for workspace wellness and ergonomic excellence.
    <br />
    <a href="#key-features"><strong>Explore the features »</strong></a>
    <br />
    <br />
    <a href="#demo">View Demo</a>
    ·
    <a href="https://github.com/Thabithx/deskly/issues">Report Bug</a>
    ·
    <a href="https://github.com/Thabithx/deskly/issues">Request Feature</a>
  </p>
</div>

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#key-features">Key Features</a>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#admin-panel">Admin Panel</a></li>
    <li><a href="#contributing">Contributing</a></li>
  </ol>
</details>

## About The Project

Deskly isn't just another online store, it's a e-commerce platform built to revolutionize how people approach their workspace wellness. You dont have to waste time searching for the right products and end up wasting time and money buying low quality products, Deskly has the right high quality products that help to improve your desk health.

I engineered a **premium dark-themed UI** using Vanilla CSS with glassmorphism effects, creating a visually stunning interface that rivals industry-leading e-commerce platforms. The backend architecture features a robust RESTful API system with role-based access control, ensuring secure transactions and seamless data flow between frontend and database.

Behind the premium aesthetics lies a high-performance database architecture with optimized queries that handle complex product catalogs, user sessions, real-time cart management, and order processing with sub-second response times.

### Built With

*   **Backend:** PHP 8+ (RESTful API Architecture)
*   **Frontend:** Vanilla CSS, JavaScript ES6+ (No framework dependencies)
*   **Database:** MySQL (Optimized Schema with Relational Integrity)
*   **Security:** Role-Based Access Control (RBAC), Session Management, SQL Injection Prevention
*   **Design:** Custom CSS Design System, Glassmorphism UI, Responsive Grid Layouts

<p align="right">(<a href="#top">back to top</a>)</p>

## Key Features

### 🎨 Premium Dark Theme UI
A meticulously crafted design system featuring glassmorphism effects, smooth animations, and a professional dark aesthetic that creates an immersive shopping experience rivaling premium e-commerce platforms.

### 🔐 Enterprise-Grade Security
Comprehensive security framework with role-based access control (Admin/User), secure session management, password hashing, and SQL injection protection ensuring customer data integrity.

### 🛒 Advanced Cart Management
Intelligent shopping cart system with persistent storage, real-time quantity updates, dynamic pricing calculations, and seamless checkout flow—all powered by optimized backend APIs.

### 📦 Smart Product Catalog
Dynamic product categorization (Ergonomics, Wellness, Decor, Accessories) with featured products, advanced filtering, and intuitive navigation designed to maximize conversion rates.

### 🔔 Real-Time Notification System
Intelligent alert mechanism that keeps users informed of order status updates, admin responses, and important account activities through a centralized notification hub.

### 👤 User Profile & Order Tracking
Comprehensive user dashboard featuring profile management, order history, real-time order tracking, and personalized notification preferences for a tailored experience.

### 🎯 Admin Command Center
Powerful administrative panel providing complete control over products, users, orders, FAQs, and site content—all through an intuitive, dashboard-style interface.

### 📊 Order Management System
End-to-end order processing pipeline with status tracking (Pending, Approved, Shipped, Delivered, Cancelled), detailed order views, and streamlined fulfillment workflows.

### 💬 Customer Support Integration
Built-in contact system with FAQ management, allowing administrators to curate helpful resources while maintaining direct communication channels with customers.

### 🚀 Performance Optimized
Clean, efficient codebase with optimized database queries, minimal dependencies, and fast page load times—delivering a smooth experience even under high traffic.

<p align="right">(<a href="#top">back to top</a>)</p>

## Getting Started

Follow these steps to get a local copy up and running.

### Prerequisites

*   **PHP 8.0** or higher with MySQL extensions enabled
*   **MySQL Server 8.0** or higher
*   **Apache/Nginx** web server (XAMPP, WAMP, or MAMP recommended for local development)
*   **Modern Web Browser** (Chrome, Firefox, Safari, or Edge)

### Installation

1.  **Clone the Repository**
    ```
    git clone https://github.com/Thabithx/deskly.git
    ```

2.  **Move to Web Server Directory**
    *   For XAMPP: Move the `deskly` folder to `C:/xampp/htdocs/` (Windows) or `/Applications/XAMPP/htdocs/` (Mac)
    *   For WAMP: Move to `C:/wamp64/www/`
    *   For MAMP: Move to `/Applications/MAMP/htdocs/`

3.  **Database Configuration**
    *   Start your MySQL server
    *   Create a new database named `deskly`
    *   Import the database schema
    
4.  **Configure Database Connection**
    *   Navigate to `/backend/controllers/db.php`
    *   Update the database credentials:
        ```php
        $host = 'localhost';
        $dbname = 'deskly';
        $username = 'root';
        $password = 'your_password';
        ```

5.  **Environment Setup** (Optional)
    *   Create a `.env` file in the root directory if needed
    *   Configure any environment-specific variables

6.  **Launch the Application**
    *   Start your web server (Apache/Nginx)
    *   Access the application at: `http://localhost/deskly`

7.  **Admin Account Setup**
    *   Register a new account through the UI
    *   Manually update the user role in the database to 'Admin' for administrative access
    *   Or use the "Add Admin" feature from an existing admin panel

<p align="right">(<a href="#top">back to top</a>)</p>

## Usage

### Customer Journey

1.  **Browse Products:** Navigate through categories (Ergonomics, Wellness, Decor, Accessories)
2.  **Product Details:** View detailed product information, pricing, and availability
3.  **Shopping Cart:** Add items, adjust quantities, and proceed to checkout
4.  **User Registration:** Create an account or log in to complete purchases
5.  **Checkout:** Enter shipping details and place orders
6.  **Order Tracking:** Monitor order status and delivery updates from your profile
7.  **Notifications:** Receive updates on order status and admin responses

### Admin Workflow

1.  **Admin Login:** Access the admin panel at `/admin/` with admin credentials
2.  **Product Management:** Add, edit, or remove products from the catalog
3.  **Order Processing:** Review orders, update statuses, and manage fulfillment
4.  **User Management:** View registered users and manage permissions
5.  **Content Management:** Update FAQs, site policies, and page content
6.  **Notifications:** Respond to customer inquiries and send updates

<p align="right">(<a href="#top">back to top</a>)</p>

## Admin Panel

The admin panel (`/admin/`) provides comprehensive management capabilities:

*   **Dashboard:** Overview of key metrics and recent activities
*   **Products:** Full CRUD operations for product catalog
*   **Orders:** Order management with status tracking and detailed views
*   **Users:** Customer account management and role assignments
*   **FAQs:** Frequently asked questions management
*   **Profile:** Admin account settings and security

Access requires admin role privileges assigned in the database.

<p align="right">(<a href="#top">back to top</a>)</p>

## Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>

---

<div align="center">

**Built with ❤️ for workspace wellness enthusiasts everywhere**

[Report Bug](https://github.com/Thabithx/deskly/issues) · [Request Feature](https://github.com/Thabithx/deskly/issues)

</div>
