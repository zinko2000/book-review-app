import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.jsx'
import Home from './Home.jsx'
import Register from './Register.jsx'
import Login from './Login.jsx'
import Favourites from './Favourites.jsx'
import Account from './Account.jsx'
import SessionBar from './SessionBar.jsx'

const params = new URLSearchParams(window.location.search)
const page = params.get('page')

const screen =
  page === 'register' ? <Register /> :
  page === 'login' ? <Login /> :
  page === 'favourites' ? <Favourites /> :
  page === 'account' ? <Account /> :
  params.has('id') ? <App /> :
  <Home />

createRoot(document.getElementById('root')).render(
  
  <StrictMode>
   <SessionBar /> 
    <nav className="site-nav" aria-label="Main navigation">
      <a href="/">Home</a>
      <a href="/?page=favourites">Favourites</a>
      <a href="/?page=login">Login</a>
      <a href="/?page=register">Register</a>
      <a href="/?page=account">My Account</a>
    </nav>
    {screen}
  </StrictMode>
)