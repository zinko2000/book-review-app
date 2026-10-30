import { useEffect, useState } from 'react'

export default function App() {
  const [book, setBook] = useState(null)
  const [error, setError] = useState('')

  useEffect(() => {
    const controller = new AbortController()
    const id = new URLSearchParams(window.location.search).get('id') ?? '1'

    async function loadBook() {
      try {
        const response = await fetch(
          `/api/book.php?id=${encodeURIComponent(id)}`,
          { signal: controller.signal }
        )
        const data = await response.json()

        if (!response.ok) {
          throw new Error(data.error || 'Could not load book.')
        }

        setBook(data)
      } catch (err) {
        if (err.name !== 'AbortError') {
          setError(err.message)
        }
      }
    }

    loadBook()
    return () => controller.abort()
  }, [])

  if (error) return <p role="alert">{error}</p>
  if (!book) return <p>Loading book...</p>

  return (
    <main>
      <h1>{book.title}</h1>

      <img
        src={`/${book.image_path}`}
        alt={`${book.title} cover`}
        style={{ width: '220px', height: 'auto' }}
      />

      <p><strong>Author:</strong> {book.author}</p>
      <p><strong>Category:</strong> {book.category}</p>
      <p><strong>Published:</strong> {book.publication_year}</p>

      <h2>Description</h2>
      <p>{book.description}</p>
    </main>
  )
}