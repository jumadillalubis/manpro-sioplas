<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sioplas - Landing</title>
  <style>
    body {
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      overflow: hidden;
    }
    img {
      width: 300px;
      opacity: 0;
      transform: scale(0.9);
      animation: fadeIn 1.5s ease-in-out forwards;
    }
    @keyframes fadeIn {
      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>
  <script>
    // Setelah 2.5 detik, ke halaman login
    setTimeout(() => {
      window.location.href = '/login';
    }, 2500);
  </script>
</head>
<body>
  <img src="{{ asset('images/sioplas-logo.png') }}" alt="Sioplas Logo">
</body>
</html>
