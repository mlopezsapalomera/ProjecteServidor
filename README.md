# ProjecteServidor
## Funcionament del Programa

El programa permet als usuaris registrar-se, iniciar sessió, gestionar el seu perfil, i afegir, modificar o eliminar Pokemons de la seva Pokedex personal. A més, els administradors tenen permisos addicionals per gestionar usuaris.

### Funcions Principals

1. **Registre d'Usuaris**: Permet als nous usuaris registrar-se proporcionant un nom, correu electrònic i contrasenya. La contrasenya es valida per assegurar que conté almenys una majúscula i un número.

2. **Inici de Sessió**: Els usuaris poden iniciar sessió proporcionant el seu correu i contrasenya. Si l'usuari selecciona "Remember me", es genera un token que s'emmagatzema en una cookie i a la base de dades per mantenir la sessió iniciada.

3. **Gestió de Perfil**: Els usuaris poden actualitzar el seu perfil, incloent el seu nom i foto de perfil. També poden canviar la seva contrasenya. A mes poden generar un QR per compartir el seu perfil.

4. **Gestió de Pokemons**: Els usuaris poden afegir nous Pokemons a la seva Pokedex, modificar els detalls dels Pokemons existents o eliminar-los. L'informació de cada pokemon s'auto completa depenen del pokemon a traves de la API

5. **Gestió d'Usuaris (Admin)**: Els administradors poden veure la llista d'usuaris i eliminar usuaris si és necessari.

### Estructura de la Base de Dades

La base de dades conté diverses taules, entre elles `usuarios`, `pokemons` i `user_tokens`.

- **usuarios**: Emmagatzema la informació dels usuaris, incloent el seu nom, correu electrònic, contrasenya, rol i imatge de perfil.
- **pokemons**: Emmagatzema la informació dels Pokemons, incloent el seu nom, descripció, imatge i l'ID de l'usuari propietari.
- **user_tokens**: Emmagatzema els tokens de sessió generats quan un usuari selecciona "Remember me" al iniciar sessió.
- **password_resets**: Emmagatzema les sol·licituds de restabliment de contrasenya.

### Tractament de Tokens en una Taula Diferent

Els tokens de sessió s'emmagatzemen en una taula diferent (`user_tokens`) per diverses raons:

1. **Seguretat**: Els tokens poden ser eliminats o invalidats sense afectar la informació principal de l'usuari.
2. **Escalabilitat**: Facilita l'escalabilitat del sistema permetent operacions específiques sobre els tokens sense sobrecarregar la taula principal d'usuaris.
3. **Manteniment**: Facilita la neteja de tokens caducats amb tasques programades sense afectar altres taules.

### Justificació de Creació d'Administradors

Un usuari administrador es crea quan a un usuari registrat se li assigna manualment el rol d'`admin` des de la base de dades. Això es pot fer actualitzant el camp `rol` a la taula `usuarios` per a l'usuari corresponent. Aquesta funcionalitat permet un control més segur i restringit sobre qui té privilegis administratius a l'aplicació.


## Implementació de Funcionalitats Addicionals

### AJAX

L'ús d'AJAX en el projecte permet una experiència d'usuari més fluida i dinàmica. Amb AJAX, es poden realitzar sol·licituds al servidor i actualitzar parts de la pàgina web sense necessitat de recarregar-la completament. Això és especialment útil per a operacions com afegir, modificar o eliminar Pokemons de la Pokedex personal dels usuaris.

### Generació de Codis QR

La funcionalitat de generació de codis QR permet als usuaris compartir fàcilment el seu perfil. Quan un usuari genera un codi QR, aquest conté un enllaç al seu perfil, que altres usuaris poden escanejar amb els seus dispositius mòbils per accedir-hi ràpidament.

### Lectura de API's

La integració amb API's permet obtenir informació detallada sobre els Pokemons de manera automàtica. Quan un usuari afegeix un nou Pokemon a la seva Pokedex, la informació del Pokemon s'auto completa a través de la API, assegurant que les dades siguin precises i actualitzades. Aquesta funcionalitat millora l'eficiència i l'experiència d'usuari en la gestió de Pokemons.


## Configuracions de Seguretat

Hem configurat una pàgina d'error 404 personalitzada per millorar l'experiència de l'usuari quan es troba amb una pàgina no existent.


Aquesta configuració es troba al fitxer `.htaccess`: