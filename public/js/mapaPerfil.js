function initMap() {
    // Obtener las coordenadas del elemento <p>
    var coordenadasElement = document.getElementById('coordenadas');
    var coordenadas = coordenadasElement.textContent.trim().split(',');

    // Verificar que se hayan obtenido las coordenadas
    if (coordenadas.length === 2) {
        var latitud = parseFloat(coordenadas[0]);
        var longitud = parseFloat(coordenadas[1]);

        // Crear mapa
        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: latitud, lng: longitud },
            zoom: 13
        });

        // Agregar marcador en la coordenada especificada
        var marker = new google.maps.Marker({
            position: { lat: latitud, lng: longitud },
            map: map
        });
    }
}
