import { useEffect, useState } from 'react'
import './Reviews.css'

async function requestReviews(url, options = {}) {
  const response = await fetch(url, { credentials: 'include', ...options })
  let data
  try {
    data = await response.json()
  } catch {
    throw new Error('Reviews could not load. Check Apache, Composer and the database setup.')
  }
  if (!response.ok) throw new Error(data.error || 'Could not complete your request.')
  return data
}

function ReviewForm({ review, onSave, onCancel, busy }) {
  const [rating, setRating] = useState(review?.rating ? String(review.rating) : '')
  const [text, setText] = useState(review?.review_text || '')
  return (
    <form className="review-form" onSubmit={(event) => {
      event.preventDefault()
      onSave({ rating: Number(rating), review_text: text })
    }}>
      <label>
        Your rating
        <select value={rating} onChange={(event) => setRating(event.target.value)} required disabled={busy}>
          <option value="">Choose a rating</option>
          {[1, 2, 3, 4, 5].map((value) => <option key={value} value={value}>{value} / 5</option>)}
        </select>
      </label>
      <label>
        Your review
        <textarea value={text} onChange={(event) => setText(event.target.value)}
          maxLength={1000} rows={4} required disabled={busy} />
      </label>
      <small>{text.length}/1000 characters</small>
      <div className="review-actions">
        <button disabled={busy || !text.trim()} type="submit">
          {busy ? 'Saving...' : review ? 'Save changes' : 'Post review'}
        </button>
        {onCancel && <button className="review-secondary" type="button" disabled={busy} onClick={onCancel}>Cancel</button>}
      </div>
    </form>
  )
}

// The same component displays book reviews and the signed-in user's account reviews.
export default function Reviews({ bookId, mine = false }) {
  const [data, setData] = useState(null)
  const [error, setError] = useState('')
  const [message, setMessage] = useState('')
  const [busy, setBusy] = useState(false)
  const [editing, setEditing] = useState(null)
  const [deleting, setDeleting] = useState(null)
  const [version, setVersion] = useState(0)
  const url = mine ? '/api/reviews.php?mine=1' : `/api/reviews.php?book_id=${encodeURIComponent(bookId)}`

  useEffect(() => {
    const controller = new AbortController()
    requestReviews(url, { signal: controller.signal })
      .then((result) => { setData(result); setError('') })
      .catch((err) => { if (err.name !== 'AbortError') setError(err.message) })
    return () => controller.abort()
  }, [url, version])

  async function mutate(method, payload) {
    setBusy(true)
    setError('')
    setMessage('')
    try {
      const result = await requestReviews('/api/reviews.php', {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': data.csrf_token },
        body: JSON.stringify(payload),
      })
      setEditing(null)
      setDeleting(null)
      setMessage(result.message)
      setVersion((value) => value + 1)
    } catch (err) {
      setError(err.message)
    } finally {
      setBusy(false)
    }
  }

  const ownReview = data?.reviews.find((review) => Number(review.user_id) === Number(data.current_user_id))
  return (
    <section className="reviews-section" aria-label={mine ? 'My reviews' : 'Book reviews'}>
      <h2>{mine ? 'My reviews' : 'Reader reviews'}</h2>
      {error && <p className="review-error" role="alert">{error}</p>}
      {message && <p className="review-success" role="status">{message}</p>}
      {!data ? (
        error ? <button type="button" onClick={() => setVersion((value) => value + 1)}>Try again</button>
          : <p>Loading reviews...</p>
      ) : (
        <>
          {!mine && <p className="review-summary">
            {data.review_count ? `${data.average_rating} / 5 · ${data.review_count} review${data.review_count === 1 ? '' : 's'}` : 'No ratings yet. Be the first to review this book.'}
          </p>}
          {!mine && !data.current_user_id && <p><a href="/?page=login">Log in</a> to write a review and rate this book.</p>}
          {!mine && data.current_user_id && !ownReview && (
            <ReviewForm busy={busy} onSave={(values) => mutate('POST', { ...values, book_id: Number(bookId) })} />
          )}
          {mine && data.reviews.length === 0 && <p>You have not reviewed any books yet. <a href="/">Find a book</a> to get started.</p>}
          <div className="review-list">
            {data.reviews.map((review) => {
              const isOwner = Number(review.user_id) === Number(data.current_user_id)
              return (
                <article className="review-card" key={review.review_id}>
                  <div className="review-card-heading">
                    <h3>{mine ? <a href={`/?id=${review.book_id}`}>{review.title}</a> : review.name}</h3>
                    <span aria-label={`${review.rating} out of 5 stars`} className="review-stars">
                      <span aria-hidden="true">{'★'.repeat(Number(review.rating))}{'☆'.repeat(5 - Number(review.rating))}</span>
                      <span> {review.rating}/5</span>
                    </span>
                  </div>
                  <small>Posted {review.created_at}{isOwner && ' · Your review'}</small>
                  {editing === review.review_id ? (
                    <ReviewForm review={review} busy={busy} onCancel={() => setEditing(null)}
                      onSave={(values) => mutate('PUT', { ...values, review_id: Number(review.review_id) })} />
                  ) : (
                    <>
                      <p className="review-text">{review.review_text}</p>
                      {isOwner && (
                        deleting === review.review_id ? (
                          <div className="review-actions">
                            <span>Delete this review?</span>
                            <button type="button" disabled={busy} onClick={() => mutate('DELETE', { review_id: Number(review.review_id) })}>Yes, delete</button>
                            <button type="button" disabled={busy} className="review-secondary" onClick={() => setDeleting(null)}>Cancel</button>
                          </div>
                        ) : (
                          <div className="review-actions">
                            <button type="button" disabled={busy} className="review-secondary" onClick={() => { setEditing(review.review_id); setDeleting(null) }}>Edit</button>
                            <button type="button" disabled={busy} className="review-secondary" onClick={() => { setDeleting(review.review_id); setEditing(null) }}>Delete</button>
                          </div>
                        )
                      )}
                    </>
                  )}
                </article>
              )
            })}
          </div>
        </>
      )}
    </section>
  )
}
