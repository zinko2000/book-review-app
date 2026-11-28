import { useEffect, useState } from 'react'

export default function SessionBar() {
  const [user, setUser] = useState(null)

  useEffect(() => {
    fetch('/api/account.php', {
      credentials: 'same-origin',
    })
      .then(response => response.ok ? response.json() : null)
      .then(data => {
        if (data?.user) setUser(data.user)
      })
      .catch(() => {})
  }, [])

  async function handleLogout() {
    try {
      const response = await fetch('/api/logout.php', {
        method: 'POST',
        credentials: 'same-origin',
      })

      if (response.ok) window.location.href = '/'
    } catch {
      alert('Could not log out. Please try again.')
    }
  }

  if (!user) return null

  return (
    <div className="session-bar">
      <span>Welcome, {user.name}</span>
      <button type="button" onClick={handleLogout}>Logout</button>
    </div>
  )
}
