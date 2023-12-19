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
        //prepara Datos
        var self = this;
        var valor = {
            funcion: "verDatos",
            pagina_actual: self.paginaActual
        };
        //Envio de datos
        fetch('../Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(valor)
        })
        //Respuesta peticion
        .then(response => response.json())
        .then(data => {
            crearTabla(data);
            // Iterar sobre las filas en el array
            data.forEach(fila => {
                console.log("Datos Cargados Exitosamente");
            });
        })
        //Respuesta en caso de error
        .catch(error => {
            console.error('Disculpe, existió un problema:', error);
        });

    }

    verDatosEliminados() {
        //prepara Datos
        var self = this;
        var valor = {
            funcion: "verDatosEliminados",
            pagina_actual: self.paginaActual
        };
        //Envio de datos
        fetch('../Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(valor)
        })
        //Respuesta peticion
        .then(response => response.json())
        .then(data => {
            crearTablaEliminados(data);
            // Iterar sobre las filas en el array
            data.forEach(fila => {
                console.log("Datos Cargados Exitosamente");
            });
        })
        //Respuesta en caso de error
        .catch(error => {
            console.error('Disculpe, existió un problema:', error);
        });

    }


    mostrarUpdateDatos(rut_usuario) {
        const formData = {
            funcion: "mostrarUpdateDatos",
            rut_usuario: rut_usuario
        };
    
        console.log(formData);
    
        fetch('/MVC/Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            try {
                console.log(data)
    
                if (data.success == 1) {
                    document.getElementById('modalRutUsuario').value = data.rut_usuario;
                    document.getElementById('modalNombreUsuario').value = data.nombre_usuario;
                    document.getElementById('modalApellidoUsuario').value = data.apellido_usuario;
    
                    // Muestra el modal
                    $('#modalUpdateDatos').modal('show');
                } else {
                    console.error('Error en la respuesta del servidor');
                }
            } catch (error) {
                console.error('Error al parsear la respuesta como JSON', error);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud al servidor', error);
        });
    }

    UpdateDatos(){
         // Prepara Datos
         const rut_usuario = document.getElementById('modalRutUsuario').value;
         const nombre_usuario = document.getElementById('modalNombreUsuario').value;
         const apellido_usuario = document.getElementById('modalApellidoUsuario').value;
 
         const formData = {
             funcion: "updateDatos",
             rut_usuario: rut_usuario,
             nombre_usuario: nombre_usuario,
             apellido_usuario: apellido_usuario,
         };
         console.log(formData);
         // Envia Datos
         fetch('/MVC/Controller/PHP/controlador.php', {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/json'
             },
             body: JSON.stringify(formData)
         })
         // Recibe Respuesta
         .then(response => response.json())
         .then(data => {
             console.log(data);
             if (data === 1) {
                this.verDatos();
                $('#modalUpdateDatos').modal('hide');
                 // Redirigir a la página deseada
             } else {
                 // Manejar otros casos (puedes agregar lógica adicional aquí)
                 console.error('Error en el servidor:', data.message);
             }
         })
         //Respuesta en caso de error
         .catch(error => {
             console.error('Error:', error);
         });
     }
 

     registrarDatos() {
        // Prepare data
        const rut_usuario = document.getElementById('rutUsuario').value;
        const nombre_usuario = document.getElementById('nombreUsuario').value;
        const apellido_usuario = document.getElementById('apellidoUsuario').value;
        const password_usuario = document.getElementById('passwordUsuario').value;
        const confirmacion_password_usuario = document.getElementById('confirmacionPasswordUsuario').value;
    
        const formData = {
            funcion: "registrarDatos",
            rut_usuario: rut_usuario,
            nombre_usuario: nombre_usuario,
            apellido_usuario: apellido_usuario,
            password_usuario: password_usuario,
            confirmacion_password_usuario: confirmacion_password_usuario
        };
    
        // Send data
        fetch('/MVC/Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            console.log(response); // Log the response to the console
            return response.json(); // Parse the response as JSON
        })
        .then(data => {
            console.log("hola");
            console.log(data);
            if (data.success === 1) {
                // Redirect to the desired page
                window.location.href = '/MVC/View/administrador.html';
            } else {
                // Handle other cases (you can add additional logic here)
                console.error('Error on the server:', data.message);
            }
        })
        // Handle errors
        .catch(error => {
            console.error('Error:', error);
        });
    }

    

    borrarUsuario(rut_usuario){
        //Prepara datos
        const formData ={
            funcion: "borrarUsuario",
            rut_usuario : rut_usuario
        }
        console.log(formData);
        //Envia Datos
        fetch('/MVC/Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        //Recibe Datos
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
        //Respuesta en caso de error
        .catch(error => {
            console.error('Error:', error);
        });
    }

        
    leerNfcNueva(rut_usuario){
        const formData ={
            funcion: "leerNfcNueva",
            rut_usuario : rut_usuario
        }
        fetch('/MVC/Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        //Recibe Respuesta
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server if needed
            if (data === 1) {
                // Obtén el elemento por su ID
                $('#modalLeyendoNFC').modal('hide');
                this.verDatos();
            } else {
                // Manejar otros casos (puedes agregar lógica adicional aquí)
                console.error('Error en el servidor:', data.message);
            }
        })
        //Respuesta en caso de error
        .catch(error => {
            console.error('Error en la solicitud:', error);
        });
        
    }

    leerNfcNuevaUpdate(rut_usuario){
        const formData ={
            funcion: "leerNfcNuevaUpdate",
            rut_usuario : rut_usuario
        }
        fetch('/MVC/Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        //Recibe Respuesta
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server if needed
            if (data === 1) {
                // Obtén el elemento por su ID
                $('#modalLeyendoNFC').modal('hide');
                this.verDatos();
            } else {
                // Manejar otros casos (puedes agregar lógica adicional aquí)
                console.error('Error en el servidor:', data.message);
            }
        })
        //Respuesta en caso de error
        .catch(error => {
            console.error('Error en la solicitud:', error);
        });
    }


    preparaNFC(rut_usuario){
        //Prepara Datos
        const formData ={
            funcion: "preparaNFC",
            rut_usuario : rut_usuario
        }
        console.log(formData);
        //Envia Datos
        fetch('/MVC/Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        //Recibe Respuesta
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server if needed
            if (data === 1) {
                $('#modalLeyendoNFC').modal('show');
                const formData ={
                    funcion: "leerNfcAdministrador",
                    rut_usuario : rut_usuario
                }
                fetch('/MVC/Controller/PHP/controlador.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Raw Response:', data);
                    if (data == 1) {
                                // Obtén el elemento por su ID
                                var mensajeNFCElemento = document.getElementById("MensajeNFC");

                                // Cambia el texto del elemento
                                mensajeNFCElemento.textContent = "INSERTE NUEVA NFC";
                                this.leerNfcNueva(rut_usuario);
                                
                            } else if(data == 2) {
                                var mensajeNFCElemento = document.getElementById("MensajeNFC");
                                mensajeNFCElemento.textContent = "INSERTE NUEVA NFC UPDATE";
                                this.leerNfcNuevaUpdate(rut_usuario);
                                // Manejar otros casos (puedes agregar lógica adicional aquí)
                                
                            }

})
                //Respuesta en caso de error
                .catch(error => {
                       console.error('Error en la solicitud:', error);
                });
                
            } else {
                // Manejar otros casos (puedes agregar lógica adicional aquí)
                console.error('Error en el servidor:', data.message);
            }
        })
        //Respuesta en caso de error
        .catch(error => {
            console.error('Error en la solicitud:', error);
        });
    }

    
    

    RecuperarUsuario(rut_usuarioEliminado){
        //Prepara datos
        const formData ={
            funcion: "recuperarUsuarioEliminado",
            rut_usuarioEliminado : rut_usuarioEliminado
        }
        console.log(formData);
        //Envia Datos
        fetch('/MVC/Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        //Recibe Datos
        .then(response => {
            console.log(response); // Agrega esta línea para ver la respuesta completa
            return response.json();
        })
        .then(data => {
            // Handle the response from the server if needed
            if (data.status === 'success') {
                this.verDatosEliminados();
                console.log(data);
                // No necesitas iterar sobre data aquí
            } else {
                // Manejar otros casos (puedes agregar lógica adicional aquí)
                console.error('Error en el servidor:', data.message);
            }
        })
        //Respuesta en caso de error
        .catch(error => {
            console.error('Error:', error);
        });
    }

    loginAdministrador(){
        //Prepara Datos
        const rutAdministrador = document.getElementById('rutAdministrador').value;
        const passwordAdministrador = document.getElementById('passwordAdministrador').value;
        const formData = {
            funcion: "loginAdministrador",
            rutAdministrador: rutAdministrador,
            passwordAdministrador: passwordAdministrador
        };
        //Envia Datos
        fetch('/MVC/Controller/PHP/controlador.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        //Recibe Respuesta
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data === 1) {
                // El rut y contraseña coinciden, redirige a la página correspondiente
                // Almacena el rut en una variable de sesión
                fetch('/MVC/Controller/PHP/guardarRutEnSesion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ rut: rutAdministrador })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        window.location.href = '/MVC/View/administrador.html';
                    } else {
                        console.error('Error al guardar el rut en sesión:', result.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else if (data === 0) {
                console.log("rut y password no coinciden");
            } else {
                console.error('Error en el servidor:', data.message);
            }
        })
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

        //Boton 1
        // Agregar una celda (preparar NFC) con un botón y asociar el RUT del usuario
        var nuevaCeldapreparaNFC = nuevaFila.insertCell();
        var preparaNFC = document.createElement('button');
        preparaNFC.textContent = 'Preparar NFC';
        preparaNFC.className = 'btn btn-primary';
        // Asociar el RUT del usuario al botón (puedes usar un atributo personalizado)
        preparaNFC.dataset.rutUsuario = fila.rut_usuario;
        preparaNFC.addEventListener('click', function () {
            var objpreparaNFC = new Administrador();
            objpreparaNFC.preparaNFC(rut_usuario);
        });
        nuevaCeldapreparaNFC.appendChild(preparaNFC);

        //Boton 1
        // Agregar una celda (preparar NFC) con un botón y asociar el RUT del usuario
        var nuevaCeldapreparaNFCUpdate = nuevaFila.insertCell();
        var preparaNFCUpdate = document.createElement('button');
        preparaNFCUpdate.textContent = 'Update NFC';
        preparaNFCUpdate.className = 'btn btn-success';
        // Asociar el RUT del usuario al botón (puedes usar un atributo personalizado)
        preparaNFCUpdate.dataset.rutUsuario = fila.rut_usuario;
        preparaNFCUpdate.addEventListener('click', function () {
            var objpreparaNFCUpdate = new Administrador();
            objpreparaNFCUpdate.preparaNFC(rut_usuario);
        });
        nuevaCeldapreparaNFCUpdate.appendChild(preparaNFCUpdate);

        //Boton 2
        // Agregar una celda (UPDATE DATOS) con un botón y asociar el RUT del usuario
        var nuevaCeldamostrarUpdateDatos = nuevaFila.insertCell();
        var mostrarUpdateDatos = document.createElement('button');
        mostrarUpdateDatos.textContent = 'Modificar Datos';
        mostrarUpdateDatos.className = 'btn btn-dark';
        // Asociar el RUT del usuario al botón (puedes usar un atributo personalizado)
        mostrarUpdateDatos.dataset.rutUsuario = fila.rut_usuario;
        mostrarUpdateDatos.addEventListener('click', function () {
            var objmostrarUpdateDatos = new Administrador();
            objmostrarUpdateDatos.mostrarUpdateDatos(rut_usuario);
        });
        nuevaCeldamostrarUpdateDatos.appendChild(mostrarUpdateDatos);

        //BOTON 3
        // Agregar una celda (Eliminar) con un botón y asociar el RUT del usuario
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

function crearTablaEliminados(datos) {
    // Obtener la referencia al cuerpo de la tabla
    var cuerpoTablaEliminados = document.getElementById('cuerpoTablaEliminados');
    // Limpiar el cuerpo de la tabla antes de agregar nuevas filas
    cuerpoTablaEliminados.innerHTML = '';
    // Iterar sobre los datos y crear filas para la tabla
    datos.forEach(fila => {
        console.log(fila.rut_usuarioEliminado)
        // Crear una nueva fila
        var nuevaFila = cuerpoTablaEliminados.insertRow();
        // Iterar sobre las propiedades de cada objeto (columnas)
        for (var prop in fila) {
            // Crear una celda en la fila para cada propiedad
            var nuevaCelda = nuevaFila.insertCell();
            nuevaCelda.textContent = fila[prop];
        }

        //Boton 1
        // Agregar una celda (preparar NFC) con un botón y asociar el RUT del usuario
        var nuevaCeldaRecuperarUsuario = nuevaFila.insertCell();
        var RecuperarUsuario = document.createElement('button');
        RecuperarUsuario.textContent = 'Recuperar';
        RecuperarUsuario.className = 'btn btn-success';
        // Asociar el RUT del usuario al botón usando dataset
        RecuperarUsuario.dataset.rut_usuarioEliminado = fila.rut_usuarioEliminado;
        RecuperarUsuario.addEventListener('click', function () {
            var rut_usuarioEliminado = this.dataset.rut_usuarioEliminado;
            var objRecuperarUsuario = new Administrador();
            objRecuperarUsuario.RecuperarUsuario(rut_usuarioEliminado);
        });
        nuevaCeldaRecuperarUsuario.appendChild(RecuperarUsuario);
    });
}

