

function obtenerDiasSeleccionados() {
    let checkboxes = document.querySelectorAll('input[name="diasSemana"]:checked');
    let diasSeleccionados = [];
    
    checkboxes.forEach((checkbox) => {
        diasSeleccionados.push(checkbox.value);
    });
    

    return diasSeleccionados.join(',');
}

document.addEventListener('DOMContentLoaded', function() {
    let form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        let diasSemanaInput = document.getElementById('diasSemana');
        diasSemanaInput.value = obtenerDiasSeleccionados();
    });
});
