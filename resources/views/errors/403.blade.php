<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>403 - Forbidden</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8f8fc;
      font-family: sans-serif;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
      text-align: center;
      animation: fadeInUp 0.7s ease both;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(24px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .heading {
      font-family: "Pacifico", cursive;
      font-size: clamp(3rem, 6vw, 6rem);
      color: #4b2d8a;
      line-height: 1;
      margin-bottom: 80px;
    }

    .subtitle {
      font-size: clamp(0.95rem, 2.5vw, 1.15rem);
      color: #5f3fb0;
      font-weight: 500;
      /* margin-bottom: 36px; */
      letter-spacing: 0.01em;
    }

    .error-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .digit {
      font-family: "Pacifico", cursive;
      /* font-size: clamp(18rem, 54vw, 33rem); */
      font-size: clamp(8rem, 22vw, 18rem);
      color: #1a6bff;
      line-height: 1;
      animation: bounce 2.4s ease-in-out infinite;
    }

    .digit:last-child {
      animation-delay: 0.2s;
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-12px);
      }
    }

    .illus-wrap {
      width: clamp(140px, 20vw, 220px);
      aspect-ratio: 1 / 1;
      background-color: #1a6bff;
      border-radius: 38px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      animation: wobble 3s ease-in-out infinite;
    }

    @keyframes wobble {

      0%,
      100% {
        transform: rotate(-2deg) scale(1);
      }

      50% {
        transform: rotate(2deg) scale(1.03);
      }
    }

    .illus-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .btn-home {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 44px;
      padding: 14px 32px;
      border: 2px solid #222;
      border-radius: 999px;
      background: transparent;
      color: #111;
      font-family: sans-serif;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
      /* tambah ini ↓ */
      will-change: transform;
      transform: translateY(0);
      /* baseline eksplisit */
    }

    .btn-home:hover {
      background: #1a6bff;
      color: #fff;
      border-color: #1a6bff;
      transform: translateY(-3px);
      /* geser ke atas, bukan scale */
    }

    .btn-home:active {
      transform: translateY(0);
      /* balik saat diklik */
      transition-duration: 0.1s;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="heading">Forbidden</h1>
    <p class="subtitle">You don't have permission to access this resource.</p>

    <div class="error-row">
      <span class="digit">4</span>
      <span class="digit">0</span>
      <span class="digit">3</span>
    </div>

    <a href="/" class="btn-home">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
        stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6" />
      </svg>
      Go Home
    </a>
  </div>
</body>

</html>
