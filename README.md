# ProjecteServidor
## Funcionament del Programa

El programa permet als usuaris registrar-se, iniciar sessió, gestionar el seu perfil, i afegir, modificar o eliminar Pokemons de la seva Pokedex personal. A més, els administradors tenen permisos addicionals per gestionar usuaris.

### Funcions Principals

1. **Registre d'Usuaris**: Permet als nous usuaris registrar-se proporcionant un nom, correu electrònic i contrasenya. La contrasenya es valida per assegurar que conté almenys una majúscula i un número.

2. **Inici de Sessió**: Els usuaris poden iniciar sessió proporcionant el seu correu electrònic i contrasenya. Si l'usuari selecciona "Remember me", es genera un token que s'emmagatzema en una cookie i a la base de dades per mantenir la sessió iniciada.

3. **Gestió de Perfil**: Els usuaris poden actualitzar el seu perfil, incloent el seu nom i foto de perfil. També poden canviar la seva contrasenya.

4. **Gestió de Pokemons**: Els usuaris poden afegir nous Pokemons a la seva Pokedex, modificar els detalls dels Pokemons existents o eliminar-los. Cada Pokemon té un nom, descripció i imatge.

5. **Gestió d'Usuaris (Admin)**: Els administradors poden veure la llista d'usuaris i eliminar usuaris si és necessari.

### Estructura de la Base de Dades

La base de dades conté diverses taules, entre elles `usuarios`, `pokemons` i `user_tokens`.

- **usuarios**: Emmagatzema la informació dels usuaris, incloent el seu nom, correu electrònic, contrasenya, rol i imatge de perfil.
- **pokemons**: Emmagatzema la informació dels Pokemons, incloent el seu nom, descripció, imatge i l'ID de l'usuari propietari.
- **user_tokens**: Emmagatzema els tokens de sessió generats quan un usuari selecciona "Remember me" al iniciar sessió.

### Tractament de Tokens en una Taula Diferent

Els tokens de sessió s'emmagatzemen en una taula diferent (`user_tokens`) per diverses raons:

1. **Seguretat**: Emmagatzemar els tokens en una taula separada permet gestionar millor la seguretat i el cicle de vida dels tokens. Els tokens poden ser eliminats o invalidats sense afectar la informació principal de l'usuari.

2. **Escalabilitat**: Mantenir els tokens en una taula separada facilita l'escalabilitat del sistema. Es poden realitzar operacions específiques sobre els tokens sense sobrecarregar la taula principal d'usuaris.

3. **Manteniment**: Facilita el manteniment i la neteja de tokens caducats. Es poden executar tasques programades per eliminar tokens antics sense afectar altres taules.

### Justificació de Creació d'Administradors

Un usuari administrador es crea quan a un usuari registrat se li assigna manualment el rol d'`admin` des de la base de dades. Això es pot fer actualitzant el camp `rol` a la taula `usuarios` per a l'usuari corresponent. Aquesta funcionalitat permet un control més segur i restringit sobre qui té privilegis administratius a l'aplicació.

## Configuracions de Seguretat

Hem configurat una pàgina d'error 404 personalitzada per millorar l'experiència de l'usuari quan es troba amb una pàgina no existent.

Per redirigir tot el tràfic HTTP a HTTPS i assegurar la comunicació

Per protegir contra la injecció de codi deshabilitant l'execució de scripts en directoris específics

Per ajudar a prevenir atacs de Cross-Site Scripting (XSS) i altres atacs relacionats amb la injecció de contingut:

 Aquesta configuració es troba al fitxer `.htaccess`: