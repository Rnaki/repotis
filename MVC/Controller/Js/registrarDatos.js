document.addEventListener('DOMContentLoaded', function() {
    // Crear una instancia de la clase cuando se carga la página
    var instanciaVerDatosUsuario = new Administrador();
    instanciaVerDatosUsuario.verDatos();
    // Asignar el método registrarDatos a un evento de click (o al evento que desees)
    // document.getElementById('tuBotonRegistrar').addEventListener('click', function() {
    //     instanciaVerDatosUsuario.registrarDatos();
    // });
});

class Administrador {
    constructor() {
        this.target = document.getElementById("listaUsuario");
        this.paginaActual = 1; // Puedes inicializar con la página que desees
    }


    verDatos() {
        var self = this;
        var valor = {
            funcion: "verDatos",
            pagina_actual: self.paginaActual
        };
    
        fetch('../Controller/PHP/registrarDatos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(valor)
        })
        .then(response => response.json())
        .then(data => {
            crearTabla(data);
            // Iterar sobre las filas en el array
            data.forEach(fila => {
                console.log(`ID: ${fila.rut_usuario}, Nombre: ${fila.nombre_usuario}`);
                // Realiza acciones con cada fila según sea necesario
            });
        })
        // .then(response => response.json())
        // .then(data => {
        //     if (self.target) {
        //         self.target.innerHTML = data;
        //     } else {
        //         console.error('Elemento target no encontrado');
        //     }
        // })
        .catch(error => {
            console.error('Disculpe, existió un problema:', error);
        });

    }


    registrarDatos() {
        // Your method code here
        const rut = document.getElementById('rutUsuario').value;
        const nombre = document.getElementById('nombreUsuario').value;
        const apellido = document.getElementById('apellidoUsuario').value;
        const password = document.getElementById('passwordUsuario').value;
        const confirmacionPasswordUsuario = document.getElementById('confirmacionPasswordUsuario').value;

        // Create a JSON object with the form data
        const formData = {
            funcion: "registrarDatos",
            rutUsuario: rut,
            nombreUsuario: nombre,
            apellidoUsuario: apellido,
            passwordUsuario: password,
            confirmacionPasswordUsuario: confirmacionPasswordUsuario
        };

        // Log the form data to the console (you can remove this in production)
        console.log(formData);

        // Send the form data to a PHP script using AJAX
        // Example using the Fetch API:
        fetch('/MVC/Controller/PHP/registrarDatos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server if needed
            console.log(data);
            if (data.status === 'success') {
                // Redirigir a la página deseada
                window.location.href = '/MVC/View/registrarNfc.html';
            } else {
                // Manejar otros casos (puedes agregar lógica adicional aquí)
                console.error('Error en el servidor:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    borrarUsuario(rut_usuario){
        const formData ={
            funcion: "borrarUsuario",
            rut_usuario : rut_usuario
        }
        console.log(formData);
        fetch('/MVC/Controller/PHP/registrarDatos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            console.log(response); // Agrega esta línea para ver la respuesta completa
            return response.json();
        })
        .then(data => {
            // Handle the response from the server if needed
            if (data.status === 'success') {
                this.verDatos();
                console.log(data);
                // No necesitas iterar sobre data aquí
            } else {
                // Manejar otros casos (puedes agregar lógica adicional aquí)
                console.error('Error en el servidor:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

function crearTabla(datos) {
    // Obtener la referencia al cuerpo de la tabla
    var cuerpoTabla = document.getElementById('cuerpoTabla');

    // Limpiar el cuerpo de la tabla antes de agregar nuevas filas
    cuerpoTabla.innerHTML = '';

    // Iterar sobre los datos y crear filas para la tabla
    datos.forEach(fila => {
        // Crear una nueva fila
        var nuevaFila = cuerpoTabla.insertRow();

        // Iterar sobre las propiedades de cada objeto (columnas)
        for (var prop in fila) {
            // Crear una celda en la fila para cada propiedad
            var nuevaCelda = nuevaFila.insertCell();
            nuevaCelda.textContent = fila[prop];
        }

        // Agregar una celda con un botón y asociar el RUT del usuario
        var nuevaCeldaBoton = nuevaFila.insertCell();
        var boton = document.createElement('button');
        boton.textContent = 'Ver Detalles';
        boton.className = 'btn btn-primary';
        // Asociar el RUT del usuario al botón (puedes usar un atributo personalizado)
        boton.dataset.rutUsuario = fila.rut_usuario;
        boton.addEventListener('click', function () {
            // Acción al hacer clic en el botón
            alert('RUT del usuario: ' + this.dataset.rutUsuario);
        });
        nuevaCeldaBoton.appendChild(boton);

        var nuevaCeldaBorrar = nuevaFila.insertCell();
        var Borrar = document.createElement('button');
        Borrar.textContent = 'Borrar';
        Borrar.className = 'btn btn-danger';
        // Asociar el RUT del usuario al botón (puedes usar un atributo personalizado)
        var rut_usuario = fila.rut_usuario;
        Borrar.addEventListener('click', function () {
            // Acción al hacer clic en el botón 2
            event.preventDefault();
            var objBorrarUsuario = new Administrador();
            objBorrarUsuario.borrarUsuario(rut_usuario);
        });
        nuevaCeldaBorrar.appendChild(Borrar);
    });
}