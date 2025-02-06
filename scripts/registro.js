$(document).ready(function(){
    $("#registerForm").submit(function(event){
        event.preventDefault(); // Evita el envio comun y corriente del form
        
        var tipo = document.querySelector('input[name="tipo"]:checked').value;
        var actionUrl = tipo === 'Cliente' ? '../private/alta_cliente.php' : '../private/alta_dueño.php';

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                // Actualizar el mensaje específico para el campo email
                $("#emailMessage").html(response);
            
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error: " + textStatus + " - " + errorThrown);
            }
        });
    });
});
