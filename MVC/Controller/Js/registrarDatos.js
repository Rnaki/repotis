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
            if (self.target) {
                self.target.innerHTML = data;
            } else {
                console.error('Elemento target no encontrado');
            }
        })
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
}
