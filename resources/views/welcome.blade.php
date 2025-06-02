<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Grote buttons met achtergrondafbeelding</title>
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
      height: 100vh;
      background-color: #FDFDFC;
      font-family: Arial, sans-serif;
      color: #1b1b18;
    }
    .container {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      justify-content: center;
      max-width: 95vw;
    }
    a.button-link, button {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 600px;   /* Increased width */
      height: 350px;  /* Increased height */
      border: none;
      border-radius: 20px;
      font-size: 2.8rem;  /* Slightly bigger font */
      font-weight: 700;
      color: #1b1b18;
      cursor: pointer;
      overflow: hidden;
      background-color: transparent;
      box-shadow: 0 6px 15px rgba(0,0,0,0.2);
      user-select: none;
      position: relative;
      text-decoration: none;
      transition: color 0.3s ease;
      text-align: center;
    }
    a.button-link:focus,
    button:focus {
      outline: 3px solid #1b1b18;
      outline-offset: 3px;
    }
    a.button-link::before,
    button::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background-size: cover;
      background-position: center;
      filter: grayscale(100%) blur(4px);
      opacity: 0.4;
      transition: filter 0.5s ease, opacity 0.5s ease;
      border-radius: 20px;
      z-index: -1;
    }
    #btn-countries::before,
    a#btn-countries::before {
      background-image: url('https://images.unsplash.com/photo-1589262804704-c5aa9e6def89?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }
    #btn-capitals::before {
      background-image: url('https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=2073&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
    }
    a.button-link:hover::before,
    button:hover::before {
      filter: grayscale(0%) blur(0);
      opacity: 1;
    }
    a.button-link:hover,
    button:hover {
      color: #fff;
      text-shadow: 0 0 5px rgba(0,0,0,0.7);
    }

    @media (max-width: 700px) {
      a.button-link, button {
        width: 90vw;
        height: 200px;
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <a href="{{ url('/game') }}" class="button-link" id="btn-countries" role="button" aria-label="Countries game">Countries</a>
    <a href="{{ url('/capitals') }}" class="button-link" id="btn-capitals" role="button" aria-label="Capitals game">Capitals</a>
  </div>

</body>
</html>
