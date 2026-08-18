# DesignConnect

A niche freelance marketplace built exclusively for design work — logo & branding, web/UI, social media, packaging, illustration, and print. Clients post design requests describing what they need; designers browse open requests and send proposals. No bidding wars, no platform fees — just a direct line between the person who needs design work and the person who does it.

Built as a full-stack portfolio project using **PHP, MySQL, HTML, CSS, and vanilla JavaScript** — no frameworks.

## Features

**Public**
- Landing page with animated hero, category browser, and live open requests
- Unified Browse page — toggle between Designers and Requests, with real server-side filters and search
- Public Designer Profiles and Request Details (proposals stay hidden from everyone except the owning client)
- Contact form, Login, and Registration (role picker: Client or Designer)

**Client Dashboard**
- Post, edit, and manage design requests, with image upload
- View proposals privately per request
- Manually update request status: Open → In Progress → Completed
- Profile, password, notifications, and account deletion

**Designer Dashboard**
- Submit and edit proposals on open requests
- Track sent proposals and live request status
- Manage a public portfolio, separate from proposal-attached samples
- Profile, password, notifications, and account deletion

## Tech Stack

- **Backend:** PHP (MVC-style: Controller / Model / View), PDO with prepared statements
- **Database:** MySQL
- **Frontend:** HTML, CSS (custom design system, no framework), vanilla JavaScript
- **Auth:** Session-based, password hashing via `password_hash()` / `password_verify()`

## Getting Started

1. Clone the repo into your local server's web root (e.g. `htdocs/` for XAMPP/WAMP)
2. Import the database schema: `designconnect.sql`
3. Fill in connxion.php files your own database credentials
4. Visit `http://localhost/DesignConnect/Public/` in your browser



## Author

Built by Kryovelle as a portfolio project.