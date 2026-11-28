# Zin Ko's A3 reviews and ratings

## What this update adds
- Book page: read public reviews, see the average rating and review count, and submit a 1-5 rating with a written review.
- One review per user per book. Edit the existing review rather than posting a duplicate.
- Book page and My Account: edit or delete your own reviews, with a delete confirmation.
- My Account: display only the signed-in user's submitted reviews, with links back to each book.
- Existing PHP sessions identify the reviewer. The browser does not choose the user ID.
- New PHP classes follow the existing Composer App namespace.
- New styles are scoped to reviews and match the existing green and cream design.

## Files
- reviews.php: HTTP endpoint; session and request token checks; method routing; JSON responses.
- backend/src/Controllers/ReviewController.php: validation, ownership checks and summary calculations.
- backend/src/Models/ReviewModel.php: prepared database statements and joins.
- database/reviews_table.sql: new table; preserves existing books/users.
- frontend/src/Reviews.jsx and Reviews.css: reusable review UI.
- frontend/src/App.jsx: includes reviews below the description.
- frontend/src/Account.jsx: includes My reviews. Account save errors keep the form visible.

## Install on the existing project
1. Back up D:\xampp\htdocs\book-review-app by copying the folder somewhere safe.
2. Close the running frontend server if necessary.
3. Copy backend, database, docs, frontend and reviews.php from this update into
   D:\xampp\htdocs\book-review-app. Allow Windows to merge folders and replace
   App.jsx and Account.jsx. Do not delete the existing project.
4. In phpMyAdmin, select book_review_db. Use Import to select database/reviews_table.sql.
   Do not reimport book_review_db.sql: that is the original setup, not this update.
5. The existing account code and this update both need Composer. In the project-root terminal:
   composer install
   If Composer is unavailable, install Composer for Windows using your XAMPP PHP
   (normally D:\xampp\php\php.exe), reopen VS Code, then run composer install.
   Composer generates vendor/autoload.php. If the new classes are not found after an
   existing installation, run composer dump-autoload.
6. Start Apache and MySQL in XAMPP. Run .\start-app.bat and open its URL.
7. Log in, open a book, and test posting a rating and review.
8. Open My Account and test editing/deleting the review.

This package does not change registration, logout, search, filters or favourites.
The team should integrate those separately. Do not overwrite a teammate's newer
App.jsx or Account.jsx without merging the two Reviews additions.

## Local verification checklist (run before committing)
- Logged out: book reviews visible; login link shown instead of the posting form.
- Logged in: select 1-5, write review, post; see it and the updated average.
- Refresh: review remains stored.
- Same user/book: only one review; editing changes text/rating.
- My Account: only your reviews; book title links to its detail page.
- Edit on account, then revisit book: same updated review.
- Delete confirmation: cancel keeps the review; confirm removes it and updates average.
- Two browsers with different users: user B cannot edit/delete user A's review.
- Blank text and invalid rating rejected. Direct API ownership checks must also hold.
- Narrow/mobile window: forms and buttons remain usable.
- Existing account updates and homepage continue working.
- Test on the final hosted website too; a localhost test is not a deployment test.

## Checks performed while preparing the code
- Vite production build and ESLint: passed.
- PHP 8.2 syntax parsing: passed for all three new PHP files.
- PHP controller run in a PHP 8.2 WebAssembly runtime with an in-memory model:
  tested validation, create/update/delete, ownership isolation, IDs and averages.
- React interaction tests could not be completed in this environment;
  verify all frontend workflows using the local checklist.
- No live MariaDB/Apache integration or visual browser test was available here.
  Run the local checklist above; the migration still needs importing on your machine.

## Database explanation for the viva
Existing tables:
- books: book_id primary key, title, author, description, category, publication_year, image_path.
- users: user_id primary key, name, email, password_hash, created_at.

New reviews table:
- review_id: primary key.
- book_id: foreign key to books.
- user_id: foreign key to users.
- rating: whole number 1-5.
- review_text, created_at, updated_at.
- Unique pair (user_id, book_id) prevents duplicate reviews for the same user/book.
- Deleting a parent user/book cascades to the linked reviews.
One book has many reviews. One user has many reviews.
Each review belongs to exactly one book and one user.

## Ready-to-say explanations (learn the logic, not just the wording)
How do you save a review?
"The React form sends the book ID, rating and review text as JSON to reviews.php.
PHP gets the reviewer ID from the session. The controller validates the values,
and the model inserts them using a prepared statement. React then reloads the list."

How do you protect another user's review?
"The server checks both the review ID and the session user ID. The UPDATE and
DELETE statements include both IDs. Hiding buttons is only the interface;
the server checks ownership too."

How is the average calculated?
"The controller totals the ratings for that book, divides by the number of
reviews and rounds to one decimal. With no reviews, it returns null."

How do account reviews work?
"The same React component uses mine=1. The server uses the session user ID to
return only that user's reviews, joined with book titles."

Why Composer and classes?
"Composer maps the App namespace to backend/src. The controller handles
validation and the model contains the database queries, so they have separate jobs."

How do you stop SQL text becoming a query?
"The SQL uses placeholders and bind_param. User values are passed as data."

What is the request token?
"The server creates a random token in the user's session. React gets it when
loading reviews and sends it in the X-CSRF-Token header when changing a review."

How do book details load?
"App.jsx reads the ID in the URL, fetches book.php, and renders the returned JSON.
It passes book_id to Reviews to load that book's reviews."

## Export the final database (after team integration)
In phpMyAdmin select book_review_db, then Export, Quick, SQL, Export.
Save the actual current export as database/shelf_notes_final.sql for the A3 submission.
Check it includes books, users, reviews and any tables added for favourites.
Keep demo accounts only in a shared/submitted export.
The supplied reviews_table.sql is a migration, not an export of your live database.

## Commit after local tests pass
Stage only the changed files for this feature, review the diff and commit on your
feature branch. Coordinate with Juliana and Angi before integrating into main.
Do not commit node_modules or local test credentials.

## A3 scope reminder
This is the reviews/ratings update, not the final A3 submission.
Live hosting, all teammates' features, a fresh SQL export, final README,
the 450-550 word PDF report with title page and team IDs, and the demo/viva
must still be completed and checked.
