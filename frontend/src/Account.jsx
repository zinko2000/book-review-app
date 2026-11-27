

import { useEffect, useState } from 'react'
import './Account.css'
import Reviews from './Reviews.jsx'

function Account() {
  const [user, setUser] = useState({
    name: '',
    email: ''
  })

  const [currentPassword, setCurrentPassword] = useState('')
  const [newPassword, setNewPassword] = useState('')
  const [message, setMessage] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(true)
  const [saving, setSaving] = useState(false)

  useEffect(() => {
    const fetchAccount = async () => {
      try {
        const response = await fetch('/api/account.php', {
          method: 'GET',
          credentials: 'include'
        })

        const data = await response.json()

        if (!response.ok) {
          throw new Error(data.error || 'Could not load account.')
        }

        setUser(data.user)
      } catch (err) {
        setError(err.message)
      } finally {
        setLoading(false)
      }
    }

    fetchAccount()
  }, [])

  const handleSubmit = async (event) => {
    event.preventDefault()

    setMessage('')
    setError('')
    setSaving(true)

    try {
      const response = await fetch('/api/account.php', {
        method: 'PUT',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          name: user.name,
          email: user.email,
          current_password: currentPassword,
          new_password: newPassword
        })
      })

      const data = await response.json()

      if (!response.ok) {
        throw new Error(
          data.error || data.errors?.join(' ') || 'Could not update account.'
        )
      }

      setUser(data.user)
      setCurrentPassword('')
      setNewPassword('')
      setMessage(data.message)
    } catch (err) {
      setError(err.message)
    } finally {
      setSaving(false)
    }
  }

  if (loading) {
    return <p>Loading account...</p>
  }

  if (error && loading === false && !user.user_id) {
    return (
      <main className="account-page">
        <div className="account-container">
          <h1>My Account</h1>
          <p className="account-error">{error}</p>
          <p className="account-login-link">
            <a href="/?page=login">Log in to access your account</a>
          </p>
        </div>
      </main>
    )
  }

  return (
    <main className="account-page">
      <section className="account-container">
        <h1>My Account</h1>
        <p>Manage your personal details and password.</p>

        {message && <p className="account-success">{message}</p>}
        {error && <p className="account-error">{error}</p>}

        <form onSubmit={handleSubmit}>
          <h2>Personal Details</h2>

          <label htmlFor="name">Username</label>
          <input
            id="name"
            type="text"
            value={user.name}
            onChange={(event) =>
              setUser({ ...user, name: event.target.value })
            }
            required
          />

          <label htmlFor="email">Email Address</label>
          <input
            id="email"
            type="email"
            value={user.email}
            onChange={(event) =>
              setUser({ ...user, email: event.target.value })
            }
            required
          />

          <h2>Change Password</h2>
          <p>
            Leave these fields empty if you don't want to change your password.
          </p>

          <label htmlFor="currentPassword">Current Password</label>
          <input
            id="currentPassword"
            type="password"
            value={currentPassword}
            onChange={(event) => setCurrentPassword(event.target.value)}
          />

          <label htmlFor="newPassword">New Password</label>
          <input
            id="newPassword"
            type="password"
            value={newPassword}
            onChange={(event) => setNewPassword(event.target.value)}
            minLength="8"
          />

          <button type="submit" disabled={saving}>
            {saving ? 'Saving...' : 'Save Changes'}
          </button>
        </form>
        <Reviews mine />
      </section>
    </main>
  )
}

export default Account
