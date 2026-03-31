# Eventigo 🎟️

Eventigo is an event and ticketing platform built with Laravel, where visitors can discover events and hosts can create, manage, publish, and sell their own events

 🚧 This project is currently a work in progress.

---

## ✨ Features

### For visitors
- View featured events
- Search for events by name, location, and date
- Filter events by category
- View detailed event pages
- Receive your ticket via email as a QR code

### For hosts
- Register as a host
- Choose between different subscription plans
- Create your own company / organization
- Create and manage events
- Add staff members
- Access a dashboard with event insights

### Platform features
- Pricing plans (Free / Premium Monthly / Premium Yearly)
- QR-code generated tickets
- Company / organization dashboard

## 📌 Future Improvements

Planned features and improvements include:

- Payment integration
- Event analytics
- Ticket scanning
- Event approval flow
- Multilingual support
- Multi-currency pricing and ticket display

---

## 🛠️ Tech Stack

- **Backend:** Laravel
- **Frontend:** Blade + Tailwind CSS + Alpine.js
- **Database:** SQLite

---

## ⚙️ Installation
1. Clone the repository

```bash
git clone https://github.com/rickgianotten/Eventigo.git
cd Eventigo
```
2. Install dependencies
```bash
composer install
npm install
```
3. Create environment file
```bash
cp .env.example .env
```
4. Generate the application key
```bash
php artisan key:generate
```
5. Configure your database

Example for SQLite:

Update your .env file

```bash
DB_CONNECTION=sqlite
```
If you're using SQLite, make sure the database file exists:
```bash
touch database/database.sqlite
```

6. Run migrations & seeders
```bash
php artisan migrate --seed
```
7. Create the symbolic link
```bash
php artisan storage:link
```

8. Start the development server
```bash
composer run dev
```
## NOTE!
# 🌱 Seeded Data

The application includes seeders for essential system data such as:

- Categories
- Pricing plans

These are required for the platform to function correctly in both development and production.
