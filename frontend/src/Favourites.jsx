import { useEffect, useState } from 'react'

export default function Favourites({ bookId }) {
  const [books, setBooks] = useState(null)
  const [message, setMessage] = useState('')
  const [saving, setSaving] = useState(false)

  useEffect(() => {
    async function loadFavourites() {
      try {
        const response = await fetch('/api/favourites.php')
        const data = await response.json()
        if (!response.ok) throw new Error(data.error)
        setBooks(data)
      } catch (error) {
        setMessage(error.message === 'Log in to use favourites.'
          ? error.message
          : 'Could not load favourites.')
      }
    }

    loadFavourites()
  }, [])

  async function updateFavourite(id, action) {
    setSaving(true)
    setMessage('')

    try {
      const response = await fetch('/api/favourites.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ book_id: id, action }),
      })
      if (!response.ok) throw new Error('Could not update favourites.')

      if (action === 'add') {
        setBooks([...books, { book_id: id }])
      } else {
        setBooks(books.filter(book => book.book_id !== id))
      }
    } catch {
      setMessage('Could not update favourites.')
    } finally {
      setSaving(false)
    }
  }

  if (bookId) {
    const isFavourite = books?.some(book => Number(book.book_id) === Number(bookId))
    return (
      <>
        {books && (
          <button disabled={saving} onClick={() => updateFavourite(bookId, isFavourite ? 'remove' : 'add')}>
            {isFavourite ? 'Remove from favourites' : 'Add to favourites'}
          </button>
        )}
        {message && <p role="status">{message}</p>}
      </>
    )
  }

  return (
    <main>
      <h1>Favourites</h1>
      {message && <p role="status">{message}</p>}
      {books?.length === 0 && <p>No favourites yet.</p>}
      <ul>
        {books?.map(book => (
          <li key={book.book_id}>
            <a href={`/?id=${book.book_id}`}>{book.title}</a>{' '}
            <button disabled={saving} onClick={() => updateFavourite(book.book_id, 'remove')}>Remove</button>
          </li>
        ))}
      </ul>
    </main>
  )
}
