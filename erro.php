<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Erro de Acesso</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --roxo-fundo: #1c0f2e;
      --roxo-card: #2b134b;
      --amarelo: #ffb300;
      --amarelo-hover: #e6a700;
      --branco: #fff;
      --cinza: #ccc;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, var(--roxo-fundo), #0e071b);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: var(--branco);
      overflow: hidden;
      padding: 20px;
    }

    .card {
      background: var(--roxo-card);
      padding: 50px 35px;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(255, 179, 0, 0.2);
      text-align: center;
      max-width: 400px;
      width: 100%;
      position: relative;
      animation: slideUp 0.8s ease;
      border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.03));
      z-index: -1;
      border-radius: 20px;
    }

    h1 {
      font-size: 28px;
      color: var(--amarelo);
      margin-bottom: 20px;
    }

    p {
      font-size: 16px;
      color: var(--cinza);
      margin-bottom: 30px;
      line-height: 1.5;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background: var(--amarelo);
      color: var(--roxo-fundo);
      font-weight: 600;
      font-size: 16px;
      padding: 14px 24px;
      border: none;
      border-radius: 12px;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(255, 179, 0, 0.3);
    }

    .btn:hover {
      background: var(--amarelo-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(255, 179, 0, 0.5);
    }

    .btn i {
      font-size: 18px;
      transition: transform 0.3s ease;
    }

    .btn:hover i {
      transform: translateX(-2px);
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 480px) {
      .card {
        padding: 35px 25px;
      }

      h1 {
        font-size: 22px;
      }

      p {
        font-size: 15px;
      }

      .btn {
        width: 100%;
      }
    }
  </style>
  <!-- Ícones do Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <div class="card">
    <h1>Erro de Acesso</h1>
    <p>Houve um problema ao acessar o sistema.<br>Verifique suas credenciais ou tente novamente mais tarde.</p>
    <a href="cadastro/login.php" class="btn">
      <i class="bi bi-arrow-left-circle"></i>
      Voltar para a página de login
    </a>
  </div>
</body>
</html>
