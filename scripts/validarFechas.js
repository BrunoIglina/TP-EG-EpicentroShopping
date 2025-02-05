function validarFechas(event) {
    var fechaInicio = new Date(document.getElementById("fecha_inicio").value);
    var fechaFin = new Date(document.getElementById("fecha_fin").value);
    var fechaHoy = new Date();
    fechaHoy.setHours(0, 0, 0, 0); // No tomamos en cuenta la hora, o sea estamos solo fijandonos en la fecha

    var errorFechaInicio = document.getElementById("fechaInicioError");
    var errorFechaFin = document.getElementById("fechaFinError");
    
    // Limpiar mensajes de error previos
    errorFechaInicio.textContent = "";
    errorFechaFin.textContent = "";

    if (fechaInicio < fechaHoy) {
        event.preventDefault();
        errorFechaInicio.textContent = "La fecha de inicio no puede ser anterior a hoy.";
        return false;
    } else if (fechaFin < fechaInicio) {
        event.preventDefault();
        errorFechaFin.textContent = "La fecha de fin no puede ser anterior a la fecha de inicio.";
        return false;
    }
    return true;
}
