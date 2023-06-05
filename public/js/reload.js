

<script>
    function actualizarContenido() {
    $.ajax({
        url: './controller/PartidaController.php', // Archivo PHP que devuelve el contenido actualizado
        type: 'GET',
        success: function(response) {
            // Utilizar Mustache para renderizar el contenido actualizado en el elemento #contenido-actualizado
            var template = "{{#preguntas}}\n" +
                "<h2 class=\"text-center\">{{categoria}}</h2>\n" +
                "{{/preguntas}}";
            var renderedContent = Mustache.render(template, response);
            $('#actualizarContenido').html(renderedContent);
        }
    })
}

setInterval(actualizarContenido, 5000); // Actualizar cada 5 segundos (ajusta el tiempo según tus necesidades)
</script>