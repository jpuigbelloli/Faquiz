function aprobar(id) {
    var pregunta = $("#fila" + id + " input[name='pregunta']").val();
    var categoria = $("#fila" + id + " select option:selected").val();
    var respuesta1 = $("#fila" + id + " input[name='respuesta1']").val();
    var respuesta2 = $("#fila" + id + " input[name='respuesta2']").val();
    var respuesta3 = $("#fila" + id + " input[name='respuesta3']").val();
    var respuesta4 = $("#fila" + id + " input[name='respuesta4']").val();
    var correcta = $("#fila" + id + " input[name='correcta']").val();

    var button = $("#fila" + id + " button.btn-success");
    button.prop("disabled", true);



    $.ajax({
        url: 'http://localhost/revisarPregunta/aprobar',
        type: 'POST',
        data:{
            id:id,
            pregunta: pregunta,
            categoria: categoria,
            respuesta1: respuesta1,
            respuesta2: respuesta2,
            respuesta3: respuesta3,
            respuesta4: respuesta4,
            correcta: correcta
        },
        success: function (response) {
            $('#fila' + id).fadeOut(400, function() {
                $(this).remove();
            });
        },
        error: function (xhr, status, error) {
        }
    });
}

function rechazar(id) {
    $.ajax({
        url: 'http://localhost/revisarPregunta/rechazar',
        type: 'POST',
        data:{
            id:id
        },
        success: function (response) {
            $('#fila' + id).fadeOut(400, function() {
                $(this).remove();
            });
        },
        error: function (xhr, status, error) {
        }
    });
}