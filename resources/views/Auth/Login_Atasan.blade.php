<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SIOPLAS</title>
  <style>
    body {
      background-color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      font-family: 'Poppins', sans-serif;
    }
    .logo {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    .logo img {
      height: 100px;
    }
    form {
      display: flex;
      flex-direction: column;
      width: 350px;
    }
    input {
      margin-bottom: 15px;
      padding: 15px;
      border-radius: 25px;
      border: 1px solid #ccc;
      outline: none;
    }
    button {
      padding: 15px;
      border: none;
      border-radius: 25px;
      background-color: #6ec8e0;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background-color: #5bb9d2;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="logo">
    <img src="{{ asset('images/logo-left.png') }}" alt="Logo Left">
    <img src="{{ asset('images/logo-right.png') }}" alt="Logo Right">
  </div>

  <form method="POST" action="/login">
    @csrf
    @if (session('error'))
      <div class="error">{{ session('error') }}</div>
    @endif
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Masuk</button>
  </form>
</body>
</html>

