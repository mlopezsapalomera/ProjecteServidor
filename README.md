# ProjecteServidor

Este proyecto es una aplicación web que permite a los usuarios gestionar una Pokedex personal. A continuación, se detalla el funcionamiento del programa, las funciones principales y la estructura de la base de datos.

## Funcionamiento del Programa

El programa permite a los usuarios registrarse, iniciar sesión, gestionar su perfil, y añadir, modificar o eliminar Pokemons de su Pokedex personal. Además, los administradores tienen permisos adicionales para gestionar usuarios.

### Funciones Principales

1. **Registro de Usuarios**: Permite a los nuevos usuarios registrarse proporcionando un nombre, correo electrónico y contraseña. La contraseña se valida para asegurar que contiene al menos una mayúscula y un número.

2. **Inicio de Sesión**: Los usuarios pueden iniciar sesión proporcionando su correo electrónico y contraseña. Si el usuario selecciona "Remember me", se genera un token que se almacena en una cookie y en la base de datos para mantener la sesión iniciada.

3. **Gestión de Perfil**: Los usuarios pueden actualizar su perfil, incluyendo su nombre y foto de perfil. También pueden cambiar su contraseña.

4. **Gestión de Pokemons**: Los usuarios pueden añadir nuevos Pokemons a su Pokedex, modificar los detalles de los Pokemons existentes o eliminarlos. Cada Pokemon tiene un nombre, descripción e imagen.

5. **Gestión de Usuarios (Admin)**: Los administradores pueden ver la lista de usuarios y eliminar usuarios si es necesario.

### Estructura de la Base de Datos

La base de datos contiene varias tablas, entre ellas `usuarios`, `pokemons` y `user_tokens`.

- **usuarios**: Almacena la información de los usuarios, incluyendo su nombre, correo electrónico, contraseña, rol e imagen de perfil.
- **pokemons**: Almacena la información de los Pokemons, incluyendo su nombre, descripción, imagen y el ID del usuario propietario.
- **user_tokens**: Almacena los tokens de sesión generados cuando un usuario selecciona "Remember me" al iniciar sesión.

### Tratamiento de Tokens en una Tabla Distinta

Los tokens de sesión se almacenan en una tabla distinta (`user_tokens`) por varias razones:

1. **Seguridad**: Almacenar los tokens en una tabla separada permite gestionar mejor la seguridad y el ciclo de vida de los tokens. Los tokens pueden ser eliminados o invalidados sin afectar la información principal del usuario.

2. **Escalabilidad**: Mantener los tokens en una tabla separada facilita la escalabilidad del sistema. Se pueden realizar operaciones específicas sobre los tokens sin sobrecargar la tabla principal de usuarios.

3. **Mantenimiento**: Facilita el mantenimiento y la limpieza de tokens expirados. Se pueden ejecutar tareas programadas para eliminar tokens antiguos sin afectar otras tablas.

En resumen, este proyecto proporciona una plataforma completa para gestionar una Pokedex personal, con funcionalidades de registro, inicio de sesión, gestión de perfil y administración de Pokemons y usuarios. La separación de los tokens en una tabla distinta mejora la seguridad, escalabilidad y mantenimiento del sistema.