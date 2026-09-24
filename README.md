# 🏠 Triple K Properties

A modern real estate platform for listing, browsing, and managing properties — built with **PHP**, **MySQL**, and **vanilla JavaScript**.

![Status](https://img.shields.io/badge/status-active-brightgreen)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## 📌 About

**Triple K Properties** is a full-featured real estate platform that connects buyers, sellers, and renters with property listings across Kenya.

For **visitors**, it offers a fast, mobile-friendly way to browse properties for sale or rent, filter by location, price, type, and bedrooms, and enquire about any listing.

For **agents and administrators**, it provides a dashboard to list new properties, manage existing listings, track enquiries, and control site-wide settings.

---

## ✨ Features

### 🏘️ Public-Facing

- **Homepage** — Hero search bar, featured listings, categories, testimonials
- **Property listings** — Grid layout with filters (price, location, type, bedrooms, status)
- **Property details** — Gallery, description, amenities, location map, agent contact
- **Advanced search** — Search by keyword, location, price range
- **Categories** — For sale, for rent, land, commercial
- **Agent profiles** — Contact details for each listing agent
- **Enquiry form** — Send a message about any property
- **Contact form** — General enquiries
- **Chatbot** — AI assistant that answers property questions
- **About page** — Company story, mission, team
- **WhatsApp integration** — Floating button for instant enquiries
- **Fully responsive** — Mobile-first design

### 🛠️ Admin Dashboard

- **Dashboard** — Overview of listings, enquiries, agents
- **Property management** — Add, edit, delete listings; upload images; mark featured
- **Agent management** — Create and manage agents
- **Enquiries** — View messages from interested buyers
- **Settings** — Site name, contact info, social links

### 🔒 Security

- **CSRF protection** on all POST forms
- **PDO prepared statements** — protection against SQL injection
- **HTML escaping** on all user output — protection against XSS
- **Session-based admin authentication**
- **File upload validation** — MIME type and size checks
- **`.htaccess` hardening** — blocks sensitive files, restricts access to `includes/`

---

## 🧰 Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.x (no framework) |
| **Database** | MySQL 8.x / MariaDB |
| **Frontend** | HTML5, CSS3, vanilla JavaScript |
| **Icons** | Custom inline SVG |
| **Chatbot** | Custom PHP-based assistant |
| **Maps** | Google Maps embed |
| **Hosting** | AwardSpace (tested), any LAMP stack |

---

## 🖼️ Screenshots
## 🖼️ Screenshots

### 🏠 Homepage
![Homepage](screenshots/home.png)

### 🏘️ Property Listings
![Properties](screenshots/properties.png)

### 🏡 Property Details
![Property Details](screenshots/details.png)

### 🛠️ Admin Dashboard
![Admin Dashboard](screenshots/admin.png)

### ℹ️ About Page
![About](screenshots/about.png)

### 📞 Contact Page
![Contact](screenshots/contact.png)
---

## 📁 Project Structure
