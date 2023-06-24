$(document).ready(function() {
    $("#preguntaForm").submit(function(event) {
        var categoriaSeleccionada = $("#categoria").val();
        if (categoriaSeleccionada === "0") {
            event.preventDefault();
            alert("Elija una categoria!");
        } else {
            var correcta = $('input[name="correcta"]:checked').val();

            // Get the value of the corresponding text field
            var correctaTexto = $('input[name="' + correcta + 'Text"]').val();

            $('input[name="correcta"]:checked').val(correctaTexto);

            // Log the correct answer
            console.log("Correcta:", correctaTexto);

            this.submit();
        }



    });
});