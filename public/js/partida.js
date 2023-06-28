$(document).ready(function() {
    var puntos = 0;

    $('#finPartida').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#finDeTiempo').modal({
        backdrop: 'static',
        keyboard: false
    });

    //Llamada a la primera pregunta
    nuevaPregunta();

    var tiempoElement = $('#tiempo');
    var tiempoValue = parseInt(tiempoElement.text());
    var timer;

    startTimer();

    function startTimer() {
        clearInterval(timer);

        tiempoValue = 10;
        tiempoElement.text(tiempoValue);

        timer = setInterval(function() {
            tiempoValue--;

            if (tiempoValue >= 0) {
                tiempoElement.text(tiempoValue);
            } else {
                clearInterval(timer);
                finTiempo();
            }
        }, 1000);
    }

    $(document).on('click', '.mi-boton', function() {
        var respuesta = $(this).find('.respuesta').text();
        var idPregunta = $(this).data('id-pregunta');
        var id_div = $(this).closest('.card').attr('id');
        mandarRespuesta(respuesta, idPregunta, id_div);
    });

    $(document).on('click', '.lobby', function() {
        window.location.href = '/lobby';
    });

    $(document).on('click', '.report-option', function() {
        $('.list-group .report-option').removeClass('active');
        $(this).addClass('active');
    });

    $(document).on('click', '#reportarBtn', function() {
        var motivo = $('.list-group .report-option.active').data('value');
        var idPregunta = $('#reportModal').data('id-pregunta');

        $.ajax({
            url: 'http://localhost/partida/reportar',
            method: 'POST',
            data: {
                motivo: motivo,
                id_pregunta: idPregunta
            },
            success: function(response) {
                // Redirect
                window.location.href = '/lobby';
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    function checkearCategoria(categoria) {
        var categoria_columna = '#categoria_columna';

        switch (categoria) {
            case 'Arte':
                $(categoria_columna).addClass('Arte');
                break;
            case 'Historia':
                $(categoria_columna).addClass('Historia');
                break;
            case 'Geografia':
                $(categoria_columna).addClass('Geografia');
                break;
            case 'Ciencia':
                $(categoria_columna).addClass('Ciencia');
                break;
            case 'Deportes':
                $(categoria_columna).addClass('Deportes');
                break;
            case 'Entretenimiento':
                $(categoria_columna).addClass('Entretenimiento');
                break;
            default:
                break;
        }
    }

    function mandarRespuesta(respuesta, id_pregunta, id_div) {
        var jsTime = new Date().getTime();
        $.ajax({
            url: 'http://localhost/partida/responder',
            method: 'POST',
            data: {
                respuesta: respuesta,
                id_pregunta: id_pregunta,
                tiempo: jsTime
            },
            success: function(response) {
                var correcta = JSON.parse(response);
                if (correcta[0].correcta == 1) {
                    $('#' + id_div).addClass('verde');
                    puntos++;
                    $('#puntos').text(puntos);

                    //pasan 500 milisegundos te muestra la opcion correcta en verde
                    setTimeout(function() {
                        $('#' + id_div).removeClass('verde');
                        startTimer();
                        nuevaPregunta();
                    }, 500);
                } else {
                    $('#' + id_div).addClass('rojo');
                    $('.boton').prop('disabled', true);
                    clearInterval(timer);
                    respuestaIncorrecta(correcta.puntos);
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(error);
            }
        });
    }

    function showReportModal(idPregunta) {
        $('#reportModal').modal('show');
        $('#reportModal').data('id-pregunta', idPregunta);
    }

    function nuevaPregunta() {
        $.ajax({
            url: 'http://localhost/partida/nuevaPregunta',
            method: 'GET',
            success: function(respuesta) {
                var data = JSON.parse(respuesta);
                var pregunta = data.pregunta[0];
                var respuestas = data.respuestas;

                //---> SETEO DE LOS CAMPOS <---//
                $('#categoria').text(pregunta.categoria);
                $('#pregunta').text(pregunta.pregunta);

                var categoria = $('#categoria').text();
                checkearCategoria(categoria);

                $('.respuesta').each(function(index) {
                    $(this).text(respuestas[index].respuesta);
                });

                $('.mi-boton').each(function(index) {
                    var button = $(this);
                    var h4 = button.find('.respuesta');
                    var respuesta = respuestas[index];

                    h4.text(respuesta.respuesta);
                    button.data('id-pregunta', pregunta.id_pregunta);
                    var divId = 'respuesta_' + (index + 1);
                    button.closest('.card').attr('id', divId);
                    // button.attr('onclick', 'mandarRespuesta("' + respuesta.respuesta + '", ' + pregunta.id_pregunta + ')');
                });

                var id_pregunta = pregunta.id_pregunta;
                $('.reportar').on('click', function() {
                    /*var idPregunta = $(this).closest('.card').data('id-pregunta');*/
                    showReportModal(id_pregunta);
                });

                //---> FIN SETEO DE LOS CAMPOS <---//
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function respuestaIncorrecta(puntos) {
        $('#losPuntos').text(puntos);
        $('#finPartida').modal('show');
    }

    function finTiempo() {
        $.ajax({
            url: 'http://localhost/partida/fin',
            method: 'GET',
            success: function(respuesta) {
                var data = JSON.parse(respuesta);
                var puntaje = data.puntaje;
                $('#misPuntos').text(puntaje);
                $('#finDeTiempo').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
});