function validarFechas(event) {
    var fechaInicio = new Date(document.getElementById("fecha_inicio").value);
    var fechaFin = new Date(document.getElementById("fecha_fin").value);
    var fechaHoy = new Date();
    fechaHoy.setHours(0, 0, 0, 0); 

    var errorFechaInicio = document.getElementById("fechaInicioError");
    var errorFechaFin = document.getElementById("fechaFinError");
    

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
