# AccountPilot
A CRM system for managing browser identities, sessions, and cloud-based profiles using GoLogin Cloud Browser.

# Profile Orchestrator

Profile Orchestrator is a CRM system designed to manage browser identities and cloud-based profiles using GoLogin Cloud Browser.

This system allows teams to organize, monitor, and control multiple browser environments in a structured and scalable way.

---

## 🚀 Features

- Centralized management of GoLogin browser profiles
- Account grouping and tagging
- Profile status tracking (active, idle, blocked, archived)
- Secure credential storage
- Session logging
- Team role management (Admin / Operator / Viewer)
- Usage tracking & activity logs
- API-based automation support

---

## 🧠 Use Cases

- Multi-account management
- Social media operations
- Ad account management
- Testing environments
- Affiliate operations
- Distributed team workflows

---

## 🏗 Architecture

- Backend: Laravel (PHP)
- Frontend: React / Vue (optional)
- Database: MySQL / PostgreSQL
- Integration: GoLogin API
- Authentication: JWT / Laravel Auth
- Queue System: Redis

---

## 🔌 GoLogin Integration

The system connects to GoLogin Cloud Browser via API:

- Create / Delete Profiles
- Start / Stop Sessions
- Retrieve Profile Metadata
- Proxy & Fingerprint Configuration
- Automation hooks

Official API:
https://gologin.com/docs/api-reference/introduction

---

## 🔐 Security

- Encrypted credential storage
- Role-based access control
- Audit logs
- API key isolation
- Optional 2FA support

---

## 📊 Account Structure

Each account contains:

- Profile ID
- Login credentials
- Proxy configuration
- Fingerprint settings
- Status
- Assigned team member
- Notes
- Activity history

---

## 📦 Installation (First Run with Docker Compose)

### 1. Clone repository
```bash
git clone <repository-url>
cd AccountPilot
```

### 2. Prepare environment file
```bash
cp .env.example .env
```

If needed, adjust ports in `.env` (default: `APP_PORT=8099`, `VITE_PORT=5117`).

### 3. Build and start containers

If you use Laravel Sail wrapper:
```bash
./vendor/bin/sail up -d --build
```

If Sail is not yet installed (first run), use Docker Compose directly:
```bash
docker compose up -d --build
```

This will:
- Build PHP container
- Start PostgreSQL
- Start Node container
- Create network and volumes

### 4. Install backend dependencies (inside container)
```bash
docker compose exec account-pilot composer install
```

Or with Sail:
```bash
./vendor/bin/sail composer install
```

### 5. Generate application key
```bash
docker compose exec account-pilot php artisan key:generate
```

### 6. Run database migrations
```bash
docker compose exec account-pilot php artisan migrate
```

### 7. Install frontend dependencies
```bash
docker compose exec account-pilot npm install
```

### 8. Build frontend assets

Production build:
```bash
docker compose exec account-pilot npm run build
```

Development mode (watch):
```bash
docker compose exec account-pilot npm run dev
```

### 9. Open the application

If using Sail helper:
```bash
./vendor/bin/sail open
```

Otherwise visit:
```
http://localhost:8099
```

### Useful Docker Commands

Stop containers:
```bash
docker compose down
```

Stop and remove volumes (fresh start):
```bash
docker compose down -v
```

Rebuild containers:
```bash
docker compose up -d --build
```

---

## ⚙ Environment Variables

```
APP_URL=http://localhost:8099
APP_PORT=8099
VITE_PORT=5117
APP_SERVICE=account-pilot

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

GOLOGIN_API_TOKEN=
```

---

## 🛣 Roadmap

* Automated session rotation
* Proxy health monitoring
* Browser fingerprint analytics
* Telegram integration
* Performance dashboard
* Advanced automation workflows

---

## ⚠️ IMPORTANT DISCLAIMER

**THIS PROJECT IS FOR EDUCATIONAL AND RESEARCH PURPOSES ONLY.**

This software is designed to demonstrate browser profile management and automation concepts. It is NOT intended to:

- Violate terms of service of any platform (LinkedIn, Facebook, Instagram, Twitter, etc.)
- Bypass security measures or authentication systems
- Engage in fraudulent activities, spam, or unauthorized access
- Manipulate platform algorithms or engagement metrics
- Create fake accounts or impersonate individuals

**Legal Notice:**
- Users are solely responsible for ensuring their use complies with all applicable laws and platform terms of service
- Misuse of this software may result in account bans, legal action, or criminal prosecution
- The developers assume NO liability for any misuse or damages resulting from the use of this software
- By using this software, you agree to use it only for legitimate, authorized purposes

**Legitimate Use Cases:**
- Managing your own authorized accounts across different environments
- Testing and development workflows with proper authorization
- Educational research on browser automation and profile management
- Authorized multi-account management for businesses with proper permissions

If you plan to use this software with any third-party platform, ensure you have:
1. Read and understood their Terms of Service
2. Obtained necessary permissions for automation
3. Complied with all applicable laws and regulations

---

## ⚠ Disclaimer

This project is intended for legitimate account management and automation workflows only.

**You must comply with:**
- All applicable laws and regulations in your jurisdiction
- Terms of Service of any platforms you interact with (LinkedIn, Facebook, Instagram, etc.)
- Data protection and privacy regulations (GDPR, CCPA, etc.)
- Anti-fraud and anti-spam legislation

**Prohibited Uses:**
- Creating fake or unauthorized accounts
- Scraping data without permission
- Spamming or mass messaging
- Bypassing platform security measures
- Any activity that violates platform policies

The authors and contributors of this project are not responsible for any misuse or illegal activities conducted with this software.

---

## 📄 License

This project is licensed under the Eclipse Public License 2.0 (EPL-2.0).

See the [LICENSE](LICENSE) file for full license text.
