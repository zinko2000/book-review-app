# Book Review App

Group project using React + Vite, PHP, and a MySQL-compatible database.
XAMPP supplies Apache, PHP, and MariaDB for local development.

## Current progress

- Book details retrieved from the database through a PHP API.
- React page displays the title, cover, author, category, year, and description.
- Missing books display "Book not found."
- One sample book: The Hobbit.
- Homepage, login, registration, and additional books are still pending.

## Local setup

1. Install XAMPP, Node.js with npm, and Git.
2. Clone this repository into your XAMPP htdocs folder:

   git clone https://github.com/zinko2000/book-review-app.git

3. Keep the project folder named book-review-app.
4. Start Apache and MySQL in the XAMPP Control Panel.
5. Open http://localhost/phpmyadmin.
6. Create an empty database named book_review_db.
7. Select that database and import database/book_review_db.sql.
8. Check db.php matches your local database settings.
   Current settings: localhost, username root, blank password.
   These settings are for local development only.
9. Open a terminal in the book-review-app folder and run:

   cd frontend
   npm.cmd install
   npm.cmd run dev

10. Open the Local URL printed in the terminal, normally:
    http://localhost:5173/?id=1

Keep Apache, MySQL, and the Vite terminal running.

## API

- Existing book: http://localhost/book-review-app/book.php?id=1
- Missing book: http://localhost/book-review-app/book.php?id=9999

The Vite development proxy forwards /api requests to
http://localhost/book-review-app.

## Cover image source

The Hobbit cover:
https://www.tolkienbooks.us/wp-content/uploads/2017/10/2007-us-hob-hc-11th-75th.jpg

Cover artwork belongs to its respective rights holders.