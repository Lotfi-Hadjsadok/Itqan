Project Setup Guide

This guide will help you set up and run the project using Docker and PostgreSQL.

---

Step 1: Create and Configure the `.env` File

1. Rename the example environment file:

mv .env.example .env

2. Replace in `.env` file with the following:

DB_CONNECTION=pgsql
DB_HOST=itqan-db
DB_USERNAME=root
DB_PASSWORD=root

---

Step 2: Build and Run Docker Containers

Run this command in your project root directory:

docker compose up --build

---

Step 3: Access the Application and Database

- Website:
  Visit http://localhost:82 in your browser.

- Database Management (Adminer):
  Visit http://localhost:7070 in your browser.

---

Step 4: Login to Adminer

Use the following credentials to connect to the PostgreSQL database:

Field: System
Value: PostgreSQL

Field: Server
Value: itqan-db

Field: Username
Value: root

Field: Password
Value: root

---

Additional Information

- To stop the Docker containers, press CTRL+C in the terminal or run:

docker compose down

- Make sure ports 82 and 7070 are available on your machine.

---

If you encounter any issues or need help, feel free to reach out!
