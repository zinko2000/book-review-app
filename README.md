# Book Review App

Shelf Notes is a group project built with React, Vite, PHP, and MariaDB. It runs locally using XAMPP.

## Current progress

- The database contains 15 books across five categories: Fantasy, Mystery, Science fiction, Romance, and Adventure.
- The homepage displays six randomly selected books.
- Clicking a book shows its cover, author, category, publication year, and description.
- Missing books display "Book not found."
- Registration and login have been tested successfully.
- The navigation connects Home, Login, and Register.

## Local setup

1. Install XAMPP, Node.js with npm, and Git.
2. Open a terminal in your XAMPP `htdocs` folder and clone the repository:

   ```powershell
   git clone https://github.com/zinko2000/book-review-app.git
   ```

3. Start Apache and MySQL in the XAMPP Control Panel.
4. Open [phpMyAdmin](http://localhost/phpmyadmin) and create a database named `book_review_db`.
5. Select `book_review_db` and import these files in order:

   - `database/book_review_db.sql` — creates the books table and adds The Hobbit.
   - `database/users_table.sql` — creates the users table.
   - `database/additional_books.sql` — adds the 14 remaining books.

   If your database already has the books and users tables from the earlier setup, import only `database/additional_books.sql` to add the new books.

6. Check that `db.php` matches your local database settings. The project uses `localhost`, username `root`, and a blank password for local XAMPP development.
7. Open a terminal in the `book-review-app` folder and run:

   ```powershell
   cd frontend
   npm.cmd install
   npm.cmd run dev
   ```

8. Open the local URL printed in the terminal, normally [http://localhost:5173/](http://localhost:5173/).

## Starting the app after setup

Start Apache and MySQL in XAMPP, then double-click `start-app.bat` in the project folder. Open the URL printed in the launcher window and keep that window open while using the app.

## Pages

- [Homepage](http://localhost:5173/)
- [Book details: The Hobbit](http://localhost:5173/?id=1)
- [Registration](http://localhost:5173/?page=register)
- [Login](http://localhost:5173/?page=login)

If Vite uses a different port, use the URL printed in the terminal.

## API

- `GET /api/books.php` — returns up to six random books.
- `GET /api/book.php?id=1` — returns the details for book 1.
- `POST /api/register.php` — creates a user account.
- `POST /api/login.php` — checks login credentials.

The Vite development proxy forwards `/api` requests to `http://localhost/book-review-app`.

## Build check

From the `frontend` folder, run `npm.cmd run build`. This generates `frontend/dist`. Running the complete app also requires the PHP backend and database.

## Book and cover sources

- [Sources for the 14 additional books and covers](docs/book-sources.md)
- [The Hobbit cover — TolkienBooks.US](https://www.tolkienbooks.us/wp-content/uploads/2017/10/2007-us-hob-hc-11th-75th.jpg)

Cover artwork belongs to its respective rights holders.