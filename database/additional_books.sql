-- Shelf Notes: add 14 books to the existing books table.
-- Select book_review_db in phpMyAdmin before importing.
-- Does not delete or replace books, users, or tables.
-- Repeat imports skip a book when its title and author already exist.
SET NAMES utf8mb4;
START TRANSACTION;

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'Alice''s Adventures in Wonderland', 'Lewis Carroll', 'Alice follows a white rabbit into a strange underground world. Her encounters with talking animals, puzzling rules and an unpredictable queen turn an ordinary afternoon into an extraordinary adventure.', 'Fantasy', 1865, 'images/alice-wonderland.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'Alice''s Adventures in Wonderland' AND author = 'Lewis Carroll');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'The Wonderful Wizard of Oz', 'L. Frank Baum', 'Carried away from Kansas by a cyclone, Dorothy sets out through Oz to find a way home. A scarecrow, a tin woodman and a lion join her journey to meet the mysterious wizard.', 'Fantasy', 1900, 'images/wizard-of-oz.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'The Wonderful Wizard of Oz' AND author = 'L. Frank Baum');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'The Adventures of Sherlock Holmes', 'Arthur Conan Doyle', 'Dr Watson recounts twelve investigations led by Sherlock Holmes. From unusual visitors to carefully concealed crimes, the detective uses close observation and reasoning to uncover what others overlook.', 'Mystery', 1892, 'images/sherlock-holmes.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'The Adventures of Sherlock Holmes' AND author = 'Arthur Conan Doyle');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'The Hound of the Baskervilles', 'Arthur Conan Doyle', 'A death on Dartmoor raises fears of a terrifying family curse. Holmes and Watson investigate the threats surrounding the Baskerville heir and search for a human explanation behind a seemingly supernatural danger.', 'Mystery', 1902, 'images/hound-baskervilles.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'The Hound of the Baskervilles' AND author = 'Arthur Conan Doyle');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'The Moonstone', 'Wilkie Collins', 'A valuable diamond disappears after arriving at an English country house. Accounts from several people reveal conflicting clues, hidden motives and the consequences of the jewel''s troubled history.', 'Mystery', 1868, 'images/moonstone.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'The Moonstone' AND author = 'Wilkie Collins');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'The War of the Worlds', 'H. G. Wells', 'An invasion from Mars overturns everyday life in England. An unnamed narrator struggles to survive as unfamiliar machines and weapons expose how vulnerable human society can be.', 'Science fiction', 1898, 'images/war-of-worlds.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'The War of the Worlds' AND author = 'H. G. Wells');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'The Time Machine', 'H. G. Wells', 'An inventor travels far into the future and discovers a world unlike his own. His encounter with the Eloi and Morlocks becomes an unsettling exploration of humanity, change and social divisions.', 'Science fiction', 1895, 'images/time-machine.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'The Time Machine' AND author = 'H. G. Wells');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'Journey to the Centre of the Earth', 'Jules Verne', 'A decoded message leads a professor, his nephew and their guide into an Icelandic volcano. Their expedition reveals vast underground spaces and unexpected dangers beneath the Earth''s surface.', 'Science fiction', 1864, 'images/journey-centre-earth.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'Journey to the Centre of the Earth' AND author = 'Jules Verne');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'Pride and Prejudice', 'Jane Austen', 'Elizabeth Bennet and Mr Darcy misjudge each other amid family expectations and pressure to marry. As their understanding changes, both must reconsider the assumptions that shape their choices.', 'Romance', 1813, 'images/pride-prejudice.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'Pride and Prejudice' AND author = 'Jane Austen');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'Jane Eyre', 'Charlotte Brontë', 'After a difficult childhood, Jane becomes a governess at Thornfield Hall. Her growing attachment to Mr Rochester tests her independence when the house''s secrets challenge the future she imagines.', 'Romance', 1847, 'images/jane-eyre.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'Jane Eyre' AND author = 'Charlotte Brontë');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'Sense and Sensibility', 'Jane Austen', 'The Dashwood sisters face reduced circumstances and uncertain romantic prospects. Elinor''s restraint and Marianne''s openness lead them through disappointment as they learn to balance feeling with judgment.', 'Romance', 1811, 'images/sense-sensibility.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'Sense and Sensibility' AND author = 'Jane Austen');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'Treasure Island', 'Robert Louis Stevenson', 'A map draws young Jim Hawkins into a voyage in search of buried treasure. Mutiny and shifting loyalties force him to navigate the dangers posed by Long John Silver and his fellow pirates.', 'Adventure', 1883, 'images/treasure-island.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'Treasure Island' AND author = 'Robert Louis Stevenson');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'The Call of the Wild', 'Jack London', 'Taken from his comfortable home, Buck is sold as a sled dog in the North. Harsh conditions and new loyalties draw him toward the wilderness and a life beyond domestication.', 'Adventure', 1903, 'images/call-of-wild.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'The Call of the Wild' AND author = 'Jack London');

INSERT INTO books (title, author, description, category, publication_year, image_path)
SELECT 'Around the World in Eighty Days', 'Jules Verne', 'Phileas Fogg and his companion Passepartout attempt to circle the globe within eighty days. Their wager becomes a race through delays, misunderstandings and unexpected encounters across continents.', 'Adventure', 1873, 'images/around-world-eighty-days.jpg'
WHERE NOT EXISTS (SELECT 1 FROM books WHERE title = 'Around the World in Eighty Days' AND author = 'Jules Verne');

COMMIT;
