# Book Review App

Group project using React + Vite, PHP, and a MySQL-compatible database.
XAMPP supplies Apache, PHP, and MariaDB for local development.

## Current progress

- Homepage displays up to six random books from the database.
- Clicking a book opens its details page.
- Book details include title, cover, author, category, year, and description.
- Missing books display "Book not found."
- Registration and login have been tested successfully.
- Navigation connects Home, Login, and Register.
- Currently contains one sample book: The Hobbit.
- Additional books are awaiting the team's agreed dataset.

## Local setup

1. Install XAMPP, Node.js with npm, and Git.
2. Clone this repository into your XAMPP htdocs folder:

   ```text
   git clone https://github.com/zinko2000/book-review-app.git
   ```

3. Keep the project folder named book-review-app.
4. Start Apache and MySQL in the XAMPP Control Panel.
5. Open http://localhost/phpmyadmin.
6. Create an empty database named book_review_db.
7. Select that database and import these files in order:
   - database/book_review_db.sql
   - database/users_table.sql
8. Check db.php matches your local database settings.
   Current settings: localhost, username root, blank password.
   These settings are for local development only.
9. Open a terminal in the book-review-app folder and run:

   ```text
   cd frontend
   npm.cmd install
   npm.cmd run dev
   ```

10. Open the Local URL printed in the terminal, normally:
    http://localhost:5173/

Keep Apache, MySQL, and the Vite terminal running.

## Starting the app after setup

1. Start Apache and MySQL in XAMPP.
2. Double-click start-app.bat in the project folder.
3. Open the Local URL printed in the terminal.
4. Keep the launcher window open while using the app.

## Pages

- Homepage: http://localhost:5173/
- Sample book: http://localhost:5173/?id=1
- Registration: http://localhost:5173/?page=register
- Login: http://localhost:5173/?page=login

If Vite uses a different port, use the URL shown in its terminal.

## API

- Random books: http://localhost/book-review-app/books.php
- Existing book: http://localhost/book-review-app/book.php?id=1
- Missing book: http://localhost/book-review-app/book.php?id=9999
- Registration: POST to /api/register.php through Vite.
- Login: POST to /api/login.php through Vite.

The Vite development proxy forwards /api requests to
http://localhost/book-review-app.

## Build check

From the frontend folder, run:

```text
npm.cmd run build
```

This generates frontend/dist. Running the complete app still requires
the PHP backend and database.

## Cover image source

The Hobbit cover:
https://www.tolkienbooks.us/wp-content/uploads/2017/10/2007-us-hob-hc-11th-75th.jpg

Cover artwork belongs to its respective rights holders.  