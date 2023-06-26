function aprobar(id) {
    var preguntaId = $("#fila" + id + " td[id^='pregunta_id']").attr("id");
    preguntaId = preguntaId.replace("pregunta_id", "");
    var pregunta = $("#fila" + id + " input[name='pregunta']").val();
    var categoria = $("#fila" + id + " select option:selected").val();
    var respuestas = [];

    var respuestaInputs = $("#fila" + id + " input[name^='respuesta']");
    respuestaInputs.each(function(index) {
        var respuestaId = $(this).closest("td").attr("id");
        var respuestaValor = $(this).val();
        respuestas.push({
            id_respuesta: respuestaId,
            respuesta: respuestaValor
        });
    });

    console.log(respuestas);

    var correcta = $("#fila" + id + " input[name='correcta']").val();

    var button = $("#fila" + id + " button.btn-success");
    button.prop("disabled", true);

    $.ajax({
        url: 'http://localhost/revisarPregunta/actualizarPregunta',
        type: 'POST',
        data: {
            id: id,
            id_pregunta_reportada: preguntaId,
            pregunta: pregunta,
            categoria: categoria,
            respuestas: respuestas,
            correcta: correcta
        },
        success: function(response) {
            $('#fila' + id).fadeOut(400, function() {
                $(this).remove();
            });
        },
        error: function(xhr, status, error) {
        }
    });
}