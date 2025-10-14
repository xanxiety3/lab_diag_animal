

let animalCount = 1;

function cargarRazas(especieId, razaSelect) {
    if (!especieId) {
        razaSelect.innerHTML = '<option value="" disabled selected>Seleccione raza</option>';
        return;
    }

    razaSelect.innerHTML = '<option value="" disabled selected>Cargando...</option>';

    fetch(`/razas/${especieId}`)
        .then(res => res.json())
        .then(data => {
            razaSelect.innerHTML = '<option value="" disabled selected>Seleccione raza</option>';
            data.forEach(raza => {
                const option = document.createElement('option');
                option.value = raza.id;
                option.textContent = raza.nombre;
                razaSelect.appendChild(option);
            });
        })
        .catch(() => {
            razaSelect.innerHTML = '<option value="" disabled selected>Error cargando razas</option>';
        });
}

function agregarListenersEspecie(especieSelect) {
    especieSelect.addEventListener('change', function () {
        const animalRow = especieSelect.closest('.animal-row');
        const razaSelect = animalRow.querySelector('.raza-select');
        cargarRazas(this.value, razaSelect);
    });

    if (especieSelect.value) {
        const animalRow = especieSelect.closest('.animal-row');
        const razaSelect = animalRow.querySelector('.raza-select');
        cargarRazas(especieSelect.value, razaSelect);
    }
}

function agregarAnimal() {
    const wrapper = document.getElementById('animales-wrapper');
    const original = document.querySelector('.animal-item');
    const nuevo = original.cloneNode(true);

    // Limpiar valores y actualizar nombres
    nuevo.querySelectorAll('select, input').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            const newName = name.replace(/\d+/, animalCount);
            input.setAttribute('name', newName);
        }
        input.value = '';
        if (input.tagName.toLowerCase() === 'select') {
            input.selectedIndex = 0;
        }
    });

    wrapper.appendChild(nuevo);
    const nuevaEspecie = nuevo.querySelector('.especie-select');
    agregarListenersEspecie(nuevaEspecie);

    agregarBotonEliminar(nuevo);
    animalCount++;
}

function agregarBotonEliminar(animalItem) {
    const boton = animalItem.querySelector('.eliminar-animal');
    boton.addEventListener('click', function () {
        const totalAnimales = document.querySelectorAll('.animal-item').length;
        if (totalAnimales > 1) {
            animalItem.remove();
        } else {
            alert('Debe haber al menos un animal.');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.especie-select').forEach(agregarListenersEspecie);
    document.querySelectorAll('.animal-item').forEach(agregarBotonEliminar);
    window.agregarAnimal = agregarAnimal;
});



document.addEventListener('DOMContentLoaded', function () {
    const dptoSelect = document.getElementById('departamento-select');
    const municipioSelect = document.getElementById('municipio-select');

    if (dptoSelect && municipioSelect) {
        dptoSelect.addEventListener('change', function () {
            const departamentoId = this.value;

            municipioSelect.innerHTML = '<option value="" disabled selected>Cargando municipios...</option>';

            fetch(`/municipios/${departamentoId}`)
                .then(res => res.json())
                .then(data => {
                    municipioSelect.innerHTML = '<option value="" disabled selected>Seleccione municipio</option>';
                    data.forEach(muni => {
                        const option = document.createElement('option');
                        option.value = muni.id;
                        option.textContent = muni.nombre;
                        municipioSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    municipioSelect.innerHTML = '<option value="" disabled selected>Error cargando municipios</option>';
                });
        });
    }
});