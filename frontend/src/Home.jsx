
import { useEffect, useState } from 'react'
import './Home.css'

export default function Home() {
  const [books, setBooks] = useState([])

  useEffect(() => {
    fetch('/api/books.php')
      .then(response => response.json())
      .then(data => setBooks(data))
      .catch(error => console.log(error))
  }, [])

  return (
    <main className="home">
      <h1>Welcome to Shelf Notes</h1>
      <p>Find your next favorite chapter.</p>

      <div className="books">
        {books.map(book => (
          <a
            href={`/?id=${book.book_id}`}
            className="book-card"
            key={book.book_id}
          >
            <img src={`/${book.image_path}`} alt={book.title} />
            <h3>{book.title}</h3>
            <p>{book.author}</p>
          </a>
        ))}
      </div>
    </main>
  )
}
