
import { useEffect, useState } from 'react'
import './Home.css'

export default function Home() {
  const [books, setBooks] = useState([])

  const [catalogue, setCatalogue] = useState([])
  const [search, setSearch] = useState('')
  const [genre, setGenre] = useState('')

  useEffect(() => {
    fetch('/api/books.php')
      .then(response => response.json())
      .then(data => setBooks(data))
      .catch(error => console.log(error))
    fetch('/api/books.php?catalogue=1')
      .then(response => response.json())
      .then(data => setCatalogue(data))
      .catch(error => console.log(error))
  }, [])

  const genres = [...new Set(catalogue.map(book => book.category))].sort()
  const searchText = search.trim().toLowerCase()
  const visibleBooks = searchText || genre
    ? catalogue.filter(book => {
        const matchesTitle = book.title.toLowerCase().includes(searchText)
        const matchesGenre = !genre || book.category === genre
        return matchesTitle && matchesGenre
      })
    : books

  return (
    <main className="home">
      <h1>Welcome to Shelf Notes</h1>
      <p>Find your next favorite chapter.</p>

      <label>
        Search by title{' '}
        <input type="search" value={search} onChange={event => setSearch(event.target.value)} />
      </label>
      {' '}
      <label>
        Genre{' '}
        <select value={genre} onChange={event => setGenre(event.target.value)}>
          <option value="">All genres</option>
          {genres.map(category => <option key={category}>{category}</option>)}
        </select>
      </label>

      {(searchText || genre) && visibleBooks.length === 0 && <p>No books found.</p>}
      <div className="books">
        {visibleBooks.map(book => (
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
