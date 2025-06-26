const el = document.querySelector("h1");
if (el) {
    const text = "ONDE TRADIÇÃO E ESTILO SE ENCONTRAM NO CORAÇÃO DE BANGU.";
    const interval = 100;

    function showText(el, text, interval) {
        const char = text.split("").reverse();
        const typer = setInterval(() => {
            if (!char.length) {
                clearInterval(typer);
                return;
            }
            const next = char.pop();
            el.innerHTML += next;
        }, interval);
    }

    showText(el, text, interval);
}

 mapboxgl.accessToken = 'SEU_MAPBOX_ACCESS_TOKEN';

  const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/dark-v11',
    center: [-43.4688, -22.8820], // [lng, lat]
    zoom: 17
  });

  new mapboxgl.Marker({ color: '#ffb300' })
    .setLngLat([-43.4688, -22.8820])
    .setPopup(new mapboxgl.Popup().setHTML(
      '<strong>Barbearia Na Cara do Gol</strong><br>Rua Rio da Prata, 1124 – Esq. c/ Rua dos Açudes'
    ))
    .addTo(map);

document.getElementById("menu-button").addEventListener("click", function () {
  document.getElementById("nav-links").classList.toggle("open");
});