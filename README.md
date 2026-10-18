# Actividad 2.4 — Experimentación con medidas de seguridad en el código

## Objetivo

Aplicación PHP para experimentar con controles de seguridad habituales. Contiene versiones intencionadamente vulnerables y alternativas protegidas, de forma que se puedan observar sus diferencias.

## Autores

- Jaime Manuel Cazalla Chica (jmcc0049)
- Víctor Aguilar Méndez (vam00022)

## Pila tecnológica

- PHP 8.3 CLI sobre Alpine Linux
- PDO con SQLite para el experimento de inyección SQL
- HTML y CSS sencillos
- Docker
- Despliegue previsto: Docker Hub y Google Cloud Run

## Experimentos incluidos

1. **Validación de datos** (`validation.php`): validación en el servidor, límites y codificación de salida.
2. **Inyección SQL** (`sql-injection.php`): compara concatenación vulnerable con consultas preparadas PDO. Usa únicamente datos ficticios.
3. **XSS reflejado** (`xss.php`): compara salida sin codificar con `htmlspecialchars`.
4. **CSRF** (`csrf.php`): token aleatorio de sesión, comparación con `hash_equals` y rechazo de solicitudes sin token válido.

Las rutas vulnerables son deliberadas y solo deben ejecutarse en un laboratorio aislado. No exponer este proyecto a Internet en su configuración de demostración. En especial, el ejemplo XSS vulnerable ejecuta el contenido suministrado en la petición.

## Ejecución local sin Docker

Requisitos: PHP 8.3 o compatible, extensiones `pdo_sqlite` y `mbstring` habilitadas.

Desde la raíz del proyecto:

```bash
php -S 127.0.0.1:8080
```

Abrir `http://127.0.0.1:8080`. La base SQLite se crea automáticamente en `data/lab.sqlite` cuando se visita el experimento SQL.

## Construir y ejecutar con Docker

Desde la raíz del proyecto:

```bash
docker build -t asn-actividad24:latest .
docker run --rm -p 8080:8080 -v asn-lab-data:/var/www/html/data asn-actividad24:latest
```

Abrir `http://localhost:8080`. El volumen `asn-lab-data` conserva la base de datos SQLite entre ejecuciones.


## Pruebas manuales sugeridas

- **Validación:** enviar el formulario sin nombre y probar edades no válidas.
- **SQLi:** en el experimento SQL, probar como entrada `' OR '1'='1' -- ` tanto en modo vulnerable como protegido. El modo vulnerable puede devolver todos los usuarios ficticios; el protegido debe tratar la cadena como un valor literal.
- **XSS:** abrir el ejemplo con el valor por defecto. La versión vulnerable intenta ejecutar un `alert`; la protegida debe mostrar la cadena como texto.
- **CSRF:** enviar el formulario normal y después alterar o eliminar `csrf_token` con las herramientas de desarrollo del navegador. El servidor debe rechazarlo con HTTP 403.

## Direcciones web

- [Docker Hub](https://hub.docker.com/r/jmcc0049/asn-actividad24)
- [Repositorio GitHub](https://github.com/jmcc0049/asn2026_actividad24)

## Referencias

- OWASP Cheat Sheet Series — Input Validation: https://cheatsheetseries.owasp.org/cheatsheets/Input_Validation_Cheat_Sheet.html
- OWASP Cheat Sheet Series — SQL Injection Prevention: https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html
- OWASP Cheat Sheet Series — Cross Site Scripting Prevention: https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html
- OWASP Cheat Sheet Series — Cross-Site Request Forgery Prevention: https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html
- Docker Docs — Dockerfile reference: https://docs.docker.com/reference/dockerfile/
- Google Cloud — Deploying container images to Cloud Run: https://cloud.google.com/run/docs/deploying