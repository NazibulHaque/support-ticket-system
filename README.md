Support Ticket System
Welcome to the Support Ticket System — a Laravel-based application designed to manage user support tickets efficiently. This system includes secure authentication, a modular ticket flow, and Laravel's Event-Listener architecture to ensure maintainable and scalable code.
🧪 Deliverables

- ✅ GitHub Repository or ZIP File: Contains the full Laravel project.
- ✅ SQL Dump File (optional): For quick database setup (`/database/dump.sql`).
- ✅ README.md (this file), which includes:
  - Setup instructions
  - Queue setup instructions
  - Explanations for:
    - Observer
    - Event
    - Listener
    - Job
    - Raw SQL
    - jQuery logic

⚙️ Project Setup
🛠️ Requirements

- PHP 8.2 or higher
- Composer
- MySQL or SQLite
- Node.js and npm (for frontend, optional)
- Laravel CLI (optional)

📦 Installation Steps
1. Clone or Download the Project
2. Install PHP Dependencies 
3. Copy and Configure. Env
4.Run composer.update
5. Run Migrations
6. php artisan db:seed
6. Run the Development Server
🔁 Queue Setup Instructions

1. Set Queue Driver to `database` in `.env`.
2. Generate the Queue Table using artisan commands.
3. Start the Worker using `php artisan queue:work`.

🧠 Feature Implementations
Observer
Used to automatically generate a ticket ID when a new ticket is created.
Event
Fired when a user submits a new ticket.
Listener
Listens for TicketCreated and assigns the ticket to a default support agent.
Job
Handles background to users about their ticket.
Raw SQL
Used for direct queries 
jQuery Logic
Used to enhance the UX in the ticket form.
🔐 Authentication

Custom login system using LoginController to simplify authentication.

- Login URL: /login
- Logout URL: /logout (POST)
- Middleware used:
  - guest: Applied to login routes
  - auth: Applied to logout and ticket routes

✅ Testing Credentials
For User
- Email: user@user.com
- Password: password
For Agent
- Email: agent@agent.com
- Password: password

