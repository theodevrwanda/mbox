# 🎬 mbox: Movie Streaming Platform for Rwanda and Beyond

**A.K.A. Agasobanuye Films Rwanda**

> A modern, responsive movie streaming platform for Rwandans and beyond.

![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=flat&logo=php&logoColor=white)
![MySQL Version](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)
![GitHub Issues](https://img.shields.io/github/issues/[your-username]/mbox?style=flat)
![GitHub Stars](https://img.shields.io/github/stars/[your-username]/mbox?style=social)

---

## 🚀 Project Overview

**mbox** is a web application designed as a premier **movie streaming and content hub**, focusing on audiences in Rwanda and Africa. It features **separate client and admin interfaces** for robust content management.

**Mission:** Deliver curated, accessible, and high-quality movies — local and international — supporting translated or regionalized media.

---

## 🛠️ Technology Stack

| Component         | Technology       | Icon                                                                 | Description                              |
|-------------------|------------------|----------------------------------------------------------------------|------------------------------------------|
| Backend           | PHP 7.4+        | ![PHP](https://img.shields.io/badge/-PHP-777BB4?style=flat&logo=php&logoColor=white) | Handles routing, server-side logic, AJAX processing. |
| Database          | MySQL/MariaDB   | ![MySQL](https://img.shields.io/badge/-MySQL-4479A1?style=flat&logo=mysql&logoColor=white) | Stores movies, users, and feedback. |
| Frontend          | HTML/CSS        | ![HTML5](https://img.shields.io/badge/-HTML5-E34F26?style=flat&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/-CSS3-1572B6?style=flat&logo=css3&logoColor=white) | Page structure and styling; Tailwind CSS assumed. |
| DB Connector      | MySQLi (OOP)    | ![MySQLi](https://img.shields.io/badge/-MySQLi-4479A1?style=flat)    | Secure database interaction from PHP. |

---

## 📂 Project Structure
mbox/
├── admin/                # Backend admin panel
├── client/               # Public interface
├── common/               # Shared scripts/pages
├── issets/               # Images, avatars, styles
├── databases.sql         # Database creation script
├── dbconf.php            # DB connection config
├── index.php             # Entry point
└── readme.md             # This file
text---

## 💾 Database Schema

| Table Name | Purpose              | Key Fields                              |
|------------|----------------------|-----------------------------------------|
| movies     | Streaming content    | name, title, description, poster_link, trail_url, stts |
| users      | Platform subscribers | name, email (UNIQUE), password (HASHED) |
| myusers    | Admin accounts       | name, email (UNIQUE), password          |
| feedback   | User comments        | email, feedback, date_submitted         |

---

## ⚙️ Setup & Installation

1. **Clone repository**:
   ```bash
   git clone https://github.com/theodevrwanda/mbox.git
   cd mbox
