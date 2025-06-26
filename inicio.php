<?php
session_start();


if (!isset($_SESSION['id']) || !isset($_SESSION['email'])) {
    header('Location: cadastro/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.12.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.12.0/mapbox-gl.js"></script>

    <link rel="stylesheet" href="inicio.css">
   
    <title>Barbearia</title>
</head>

<body>
<nav class="navbar">
  <!-- Botão do menu mobile -->
  <button class="responsivo" id="menu-button" aria-label="Menu responsivo">
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
  </button>

  <!-- Menu de navegação desktop -->
  <ul class="nav-links nav-links-desktop">
    <li><a href="#inicio">Home</a></li>
    <li><a href="#sobre">Sobre</a></li>
    <li><a href="#serv">Serviços</a></li>
    <li><a href="#contato">Contato</a></li>
    <?php if (isset($_SESSION['id'])): ?>
      <li><a href="editaruser.php?id=<?php echo $_SESSION['id']; ?>">Usuário</a></li>
    <?php endif; ?>
    <li><a href="sair.php">Sair</a></li>
    <li><a href="./pedidos.php">Meus pedidos</a></li>
  </ul>

  <!-- Menu lateral mobile (sem os primeiros links) -->
  <ul class="nav-links nav-links-mobile" id="nav-links">
    <button id="close-menu" aria-label="Fechar menu">&times;</button>
    <?php if (isset($_SESSION['id'])): ?>
      <li><a href="editaruser.php?id=<?php echo $_SESSION['id']; ?>">Usuário</a></li>
    <?php endif; ?>
    <li><a href="sair.php">Sair</a></li>
    <li><a href="./pedidos.php">Meus pedidos</a></li>
  </ul>
</nav>


    <main>
  <section class="inicio" id="inicio">
  <div id="particles-js"></div>
  
  <div class="container-inicio">
    <div class="conteudo-inicio">
      <div class="texto">
        <h1 class="fade-top">BARBEARIA NA CARA DO GOL</h1>
        <p class="fade-delay">Tradição, estilo e atitude no coração de Bangu.</p>
        <a href="#serv" class="btn-cta fade-button">Conheça nossos serviços</a>
      </div>
      <div class="img-wrapper fade-img">
        <img src="imagens/animate-inicio.svg" alt="Barbearia ilustração" class="image" />
      </div>
    </div>
  </div>
<div class="custom-shape-divider-bottom-1719471000">
  <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" 
       viewBox="0 0 1200 120" preserveAspectRatio="none">
    <path d="M321.39,56.35c... (path encurtado)" fill="#1e0f30"></path>
  </svg>
</div>

</section>



       <section class="sobre fade-in" id="sobre">
  <div class="container-sobre">
    <div class="decor-bar"></div>
    <h1 class="titulo-sobre">NOSSA <span>HISTÓRIA</span></h1>
    <p>
      Bem-vindo à Barbearia Na Cara do Gol, onde atitude, coragem e tradição se encontram para criar uma nova experiência em Bangu, Zona Oeste do Rio de Janeiro.
      <br><br>
      Mais do que um salão, a Barbearia Na Cara do Gol é o resultado de uma decisão ousada de André Luis Fernandes, seu fundador, que deixou para trás uma história já consolidada em busca de um recomeço. Com coragem, abriu mão do passado para viver algo novo, mais moderno, autêntico e com a cara da comunidade.
      <br><br>
      Nasceu da vontade de fazer diferente, de elevar o padrão sem perder a essência. A ideia era clara: transformar cada atendimento em um momento decisivo, como aquele lance no último minuto do jogo. E foi exatamente assim que surgiu a Barbearia Na Cara do Gol: com garra, visão e amor pelo que se faz.
      <br><br>
      Cada detalhe do espaço foi pensado para unir conforto, estilo e conexão com quem valoriza mais do que um bom corte. Aqui, cada cliente é tratado como peça fundamental do time.
      <br><br>
      A Barbearia Na Cara do Gol é mais do que um negócio. É um recomeço com propósito, é respeito pelas raízes e é orgulho de fazer parte da história de Bangu. Vem com a gente. Aqui, você entra confiante e sai pronto pro jogo.
    </p>
  </div>
</section>


        <section class="equipe" id="serv">
            <h2 class="titulo">NOSSOS <span>SERVIÇOS</span></h2>
            <div class="card-group">
                <div class="card">
                    <img src="./imagens/./maquina.jpg" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title">Máquina</h3>
                        <p class="card-text">Na Barbearia Na cara do gol, oferecemos cortes rápidos e modernos na máquina por apenas R$22, garantindo praticidade e um acabamento impecável.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="./imagens/./tesoura.jpg" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title">Corte na tesoura</h3>
                        <p class="card-text">Na Barbearia Na cara do gol, realizamos cortes detalhados na tesoura por R$30, proporcionando um acabamento personalizado e refinado para cada cliente.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="./imagens/./barba.jpg" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title">Barba</h3>
                        <p class="card-text">Na Barbearia Na cara do gol, oferecemos um serviço de barba impecável por apenas R$20, garantindo contornos perfeitos e um acabamento cuidadoso.</p>
                    </div>
                </div>
            </div>

            <div class="card-group">
                <div class="card">
                    <img src="./imagens/./navalhado.jpeg" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title">Corte navalhado</h3>
                        <p class="card-text">Na Barbearia Na cara do gol, o corte navalhado é realizado com precisão e estilo por R$30, proporcionando um acabamento perfeito e um visual sofisticado.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="./imagens/./completo.jpeg" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title">Barba e cabelo</h3>
                        <p class="card-text">Na Barbearia Na cara do gol, oferecemos o pacote completo de barba e cabelo por R$50, unindo qualidade e estilo em uma única experiência de cuidado pessoal.</p>
                    </div>
                </div>
                <div class="card">
                    <img src="./imagens/./platinado.jpeg" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title">Platinado</h3>
                        <p class="card-text">Na Barbearia Na cara do gol, o serviço de platinado custa R$90, transformando seu visual com um descolorido impecável e moderno.</p>
                    </div>
                </div>
            </div>
            <br>
            <button class="btn-agendar">
                <a href="agendamento.php">Agendar serviço</a>
            </button>
        </section>
     <section class="localizacao">
  <h2>Nossa Localização</h2>
  <hr>
  <p>Rua Rio da Prata, 1124 – esquina com Rua dos Açudes, Bangu</p>

  <div class="mapa">
    <iframe
      src="https://www.google.com/maps?q=Rua+Rio+da+Prata,+1124,+Bangu&output=embed"
      allowfullscreen
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>

  <a href="https://www.google.com/maps/search/?api=1&query=Rua+Rio+da+Prata,+1124,+Bangu" 
     target="_blank" 
     class="botao-maps">
    Abrir no Google Maps
  </a>
</section>

<section class="formulario" id="formulario">
  <div class="interface">
    <h2 class="titulo">FALE <span>CONOSCO</span></h2>
    <form id="form_msg" method="POST">
      <input type="hidden" name="access_key" value="52dfeaa9-afae-455a-87ff-21cb2ebbfebe">
      <input type="text" name="nome" id="nome" placeholder="Nome" required>
      <input type="email" name="email" id="email" placeholder="E-mail" required>
      <textarea name="mensagem" id="mensagem" placeholder="Mensagem" required></textarea>
      <div class="advance-enviar"><input type="submit" value="ENVIAR"></div>
    </form>
  </div>
</section>



    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 Na cara do gol. Todos os direitos reservados.</p>
        </div>
    </footer>




<script src="https://unpkg.com/scrollreveal"></script>
<script src="inicio.js"></script> <!-- sem defer -->
<!-- tsParticles CDN -->
<script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>
<script>
  tsParticles.load("particles-js", {
    background: {
      color: "#2b134b"
    },
    fpsLimit: 60,
    particles: {
      number: { value: 60 },
      color: { value: "#ffb300" },
      shape: { type: "circle" },
      opacity: {
        value: 0.5,
        random: true
      },
      size: {
        value: { min: 1, max: 3 },
        random: true
      },
      move: {
        enable: true,
        speed: 1.2,
        direction: "none",
        outModes: "out"
      },
      links: {
        enable: true,
        distance: 100,
        color: "#ffb300",
        opacity: 0.2,
        width: 1
      }
    },
    interactivity: {
      events: {
        onHover: { enable: true, mode: "repulse" }
      },
      modes: {
        repulse: { distance: 100 }
      }
    },
    detectRetina: true
  });

  const form = document.getElementById('form_msg');

form.addEventListener('submit', async function (e) {
  e.preventDefault();

  const formData = new FormData(form);

  try {
    const response = await fetch("https://api.web3forms.com/submit", {
      method: "POST",
      body: formData
    });

    if (response.ok) {
      const mensagem = document.createElement('div');
      mensagem.classList.add('mensagem-sucesso');

      mensagem.innerHTML = `
        <div class="sucesso-card">
          <div class="icone-check">✅</div>
          <h2>MENSAGEM ENVIADA COM SUCESSO!</h2>
          <p>Agradecemos o seu contato. Em breve retornaremos.</p>
          <a href="#inicio" class="btn-retorno">Voltar ao Início</a>
        </div>
      `;

      const interfaceDiv = document.querySelector('.formulario .interface');
      interfaceDiv.replaceWith(mensagem);
    } else {
      alert("Erro ao enviar a mensagem.");
    }
  } catch (error) {
    alert("Erro de conexão. Tente novamente.");
  }
});




</script>

</body>

</html>