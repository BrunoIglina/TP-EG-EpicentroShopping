# Epicentro Shopping 🛍️

Este proyecto utiliza Docker para estandarizar el entorno de desarrollo, asegurando que todos los miembros del equipo utilicen las mismas versiones de PHP (8.2) y MariaDB, sin necesidad de instalar servicios locales como XAMPP.

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (o Docker Engine + Docker Compose en Linux).
- Git para clonar el repositorio.

---

## 🚀 Cómo levantar el proyecto

1. **Clonar el repositorio y ubicarse en la carpeta raíz:**
   Abre tu terminal favorita y asegúrate de estar parado en la misma carpeta donde se encuentra este `README.md` y el archivo `docker-compose.yml`.

2. **Construir y levantar los contenedores:**
   Ejecuta el siguiente comando para iniciar el servidor web y la base de datos en segundo plano:

   ```bash
   docker-compose up -d --build
   ```

   _(Nota: La primera vez que lo corras puede demorar un poco mientras descarga las imágenes de PHP y MariaDB)._

3. **Acceder a la página web:**
   Una vez que finalice el comando anterior, abre tu navegador y entra a:
   👉 **http://localhost:8080**

---

## 💾 Base de Datos (MariaDB)

La base de datos se crea e inicializa **automáticamente** la primera vez que levantas el contenedor, consumiendo el archivo `bd/shopping_db_v1.sql`.

### ¿Cómo conectarse desde MySQL Shell, DBeaver o VSCode?

Si necesitas visualizar las tablas o ejecutar consultas directamente desde tu computadora, configura una nueva conexión con estos datos:

- **Host:** `127.0.0.1`
- **Puerto:** `3308` _(Cambiado del 3306 estándar para evitar conflictos con otros proyectos locales)_
- **Usuario:** `root`
- **Contraseña:** _(Dejar en blanco)_
- **Base de Datos:** `shopping_db`

**Connection String para extensiones de VSCode:**
`mysql://root@127.0.0.1:3308/shopping_db`

---

## 📦 Gestión de Dependencias (Composer)

El proyecto utiliza PHPMailer y otras librerías gestionadas por Composer en la carpeta `lib/`.

Si alguien agrega una nueva dependencia al `composer.json`, el resto del equipo debe actualizar sus carpetas locales (vendor) ejecutando este comando en la terminal **mientras Docker está encendido**:

```bash
docker exec -it epicentro_php composer install
```

---

## 🛑 Cómo apagar el proyecto

Para detener los contenedores y liberar los puertos de tu computadora (por ejemplo, si vas a trabajar en otro proyecto), ejecuta:

```bash
docker-compose down
```

_(No uses solo detener desde Docker Desktop, el comando `down` asegura que la red interna se cierre correctamente)._
