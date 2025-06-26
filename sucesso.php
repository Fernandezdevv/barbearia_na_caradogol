<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento Confirmado</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1c0b2b, #301c41, #413b6b);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            overflow: hidden;
            position: relative;
        }

        /* Efeito sutil de fundo com círculos animados */
        .background-circles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            animation: float 15s infinite ease-in-out;
        }

        .circle:nth-child(1) {
            width: 400px;
            height: 400px;
            top: 10%;
            left: 20%;
            animation-delay: 0s;
        }

        .circle:nth-child(2) {
            width: 300px;
            height: 300px;
            bottom: 15%;
            right: 10%;
            animation-delay: 5s;
        }

        @keyframes float {
            0% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-30px) translateX(20px); }
            100% { transform: translateY(0px) translateX(0px); }
        }

        .card {
            background: #fff;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            text-align: center;
            width: 90%;
            max-width: 420px;
            z-index: 1;
            position: relative;
            animation: fadeIn 1s ease forwards;
            opacity: 0;
        }

        .checkmark {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 6px solid #4CAF50;
            position: relative;
            margin: 0 auto 35px;
            box-shadow: 0 0 20px rgba(76, 175, 80, 0.6);
            background: linear-gradient(145deg, #e6ffe6, #ffffff);
        }

        .checkmark::after {
            content: "";
            position: absolute;
            left: 28px;
            top: 18px;
            width: 22px;
            height: 45px;
            border-right: 6px solid #4CAF50;
            border-bottom: 6px solid #4CAF50;
            transform: rotate(45deg);
        }

        h1 {
            font-size: 30px;
            color: #1c1c1c;
            margin-bottom: 15px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 40px;
        }

        .btn {
            background: linear-gradient(90deg, #ffb300, #ffa200);
            color: #fff;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }

        @keyframes fadeIn {
            to { opacity: 1; transform: translateY(0); }
            from { opacity: 0; transform: translateY(-40px); }
        }

        @media (max-width: 480px) {
            .card {
                padding: 35px 25px;
            }
            .checkmark {
                width: 70px;
                height: 70px;
                margin-bottom: 30px;
            }
            .checkmark::after {
                left: 20px;
                top: 12px;
                width: 18px;
                height: 36px;
                border-width: 5px;
            }
            h1 {
                font-size: 24px;
            }
            p {
                font-size: 16px;
            }
            .btn {
                padding: 12px 30px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="background-circles">
        <div class="circle"></div>
        <div class="circle"></div>
    </div>

    <div class="card">
        <div class="checkmark"></div>
        <h1>Agendamento Confirmado!</h1>
        <p>Obrigado por escolher a <strong>Na Cara do Gol</strong>. Te esperamos no seu horário!</p>
        <a class="btn" href="inicio.php">Voltar ao Início</a>
    </div>

</body>
</html>
