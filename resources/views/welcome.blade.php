<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Topografie</title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #1b1b18;
      padding: 20px;
    }
    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      justify-content: center;
      max-width: 1200px;
      width: 100%;
    }
    a.button-link, button.button-link {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 280px;
      border: none;
      border-radius: 20px;
      font-size: 2.2rem;
      font-weight: 700;
      color: black;
      cursor: pointer;
      overflow: hidden;
      background-color: transparent;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      user-select: none;
      position: relative;
      text-decoration: none;
      transition: all 0.3s ease;
      text-align: center;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255,255,255,0.1);
    }
    a.button-link:focus,
    button.button-link:focus {
      outline: 3px solid #2c3e50;
      outline-offset: 3px;
    }
    a.button-link::before,
    button.button-link::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background-size: cover;
      background-position: center;
      filter: grayscale(100%) blur(2px);
      opacity: 0.6;
      transition: all 0.4s ease;
      border-radius: 18px;
      z-index: -1;
    }
    #btn-countries::before,
    a#btn-countries::before {
      background-image: url('https://images.unsplash.com/photo-1589262804704-c5aa9e6def89?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }
    #btn-capitals::before {
      background-image: url('https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=2073&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }
    #btn-dashboard::before {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }
    #btn-logout::before {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
    }
    #btn-logout {
      background: rgba(231, 76, 60, 0.1);
      border: 2px solid rgba(231, 76, 60, 0.3);
      color: #fff;
    }
    #btn-logout:hover {
      background: rgba(231, 76, 60, 0.2);
      color: #fff;
    }
    a.button-link:hover::before,
    button.button-link:hover::before {
      filter: grayscale(0%) blur(0);
      opacity: 0.8;
      transform: scale(1.05);
    }
    a.button-link:hover,
    button.button-link:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.4);
      text-shadow: 0 2px 10px rgba(0,0,0,0.8);
      color: #fff;
    }
    a.button-link:active,
    button.button-link:active {
      transform: translateY(-2px);
    }
    .logout-form {
      display: contents;
    }
    .page-title {
      position: absolute;
      top: 30px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 3rem;
      font-weight: 800;
      color: #2c3e50;
      text-shadow: 0 2px 10px rgba(0,0,0,0.3);
      margin: 0;
      text-align: center;
    }
    @media (max-width: 768px) {
      .container {
        grid-template-columns: 1fr;
        gap: 20px;
        padding: 0 10px;
      }
      a.button-link, button.button-link {
        height: 200px;
        font-size: 1.8rem;
      }
      .page-title {
        font-size: 2.2rem;
        top: 20px;
      }
      body {
        padding: 80px 10px 20px;
      }
    }
    @media (max-width: 480px) {
      a.button-link, button.button-link {
        height: 160px;
        font-size: 1.5rem;
      }
      .page-title {
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>
  <h1 class="page-title">Geography</h1>
  <div class="container">
    <a href="{{ url('/game') }}" class="button-link" id="btn-countries" role="button" aria-label="Countries game">
      Countries
    </a>
    <a href="{{ url('/capitals') }}" class="button-link" id="btn-capitals" role="button" aria-label="Capitals game">
      Capitals
    </a>
    @auth
      @if(Auth::user()->role == 'admin')
        <a href="{{ url('/dashboard') }}" class="button-link" id="btn-dashboard" role="button" aria-label="Dashboard">
          Dashboard
        </a>
      @endif
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="button-link" id="btn-logout" role="button" aria-label="Logout">
        Logout
      </a>
      <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
      </form>
    @endauth
  </div>
</body>
</html>
