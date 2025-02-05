$(document).ready(function(){
    $("#loginForm").submit(function(event){
        event.preventDefault(); 
        
        $.ajax({
            url: '../private/login.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                if (response.includes("Contraseña incorrecta")) {
                    // Mostrar el mensaje de error en el campo de la contraseña
                    $("#passwordMessage").html(response);
                } else if (response.includes("No se encontró una cuenta con ese correo electrónico")) {
                    // Mostrar el mensaje de error en el campo del email
                    $("#emailMessage").html(response);
                } else {
                    // Limpiar los campos de mensaje si el inicio de sesión es exitoso
                    $("#emailMessage").html("");
                    $("#passwordMessage").html("");
                     
                    $("#loginForm")[0].reset(); // Reiniciar el formulario tras éxito
                    window.location.href = "../public/index.php"; // Redirigir al usuario tras inicio de sesión exitoso
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error: " + textStatus + " - " + errorThrown);
            }
        });
    });
});
