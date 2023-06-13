    function actualizarTemporizador() {
    var minutos = Math.floor(tiempoRestante / 60);
    var segundos = tiempoRestante % 60;

    // Formatea el tiempo restante en formato mm:ss
    var tiempoFormateado = segundos.toString().padStart(2, '0');

    // Actualiza el elemento de texto del temporizador
    document.getElementById('temporizador').textContent = tiempoFormateado;

    if (tiempoRestante === 0) {
    // El tiempo ha terminado, puedes realizar alguna acción aquí, como mostrar los resultados
    // o enviar la puntuación del jugador al servidor.

    // Frena el temporizador
    clearInterval(temporizadorInterval);
} else {
    // Disminuye el tiempo restante en 1 segundo
    tiempoRestante--;
}
}

    // Inicializa el temporizador
    var tiempoRestante = <?php echo $tiempoTotal?>;
    actualizarTemporizador();

    // Actualiza el temporizador cada segundo
    var temporizadorInterval = setInterval(actualizarTemporizador, 1000);


