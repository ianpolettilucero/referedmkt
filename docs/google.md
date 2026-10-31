# Google Search Console y Google Analytics

`php bin/google-report.php` lee los datos de rendimiento de búsqueda (Search
Console) y de tráfico (Analytics 4) del sitio. Es de **solo lectura**: los dos
permisos que pide terminan en `.readonly`, y la cuenta que se usa no puede
modificar nada en ninguna de las dos propiedades.

No hay un conector de Claude para estos servicios. El acceso es con una
**cuenta de servicio** de Google Cloud cuya clave se guarda como secreto del
entorno de trabajo. Nunca se pega en el chat ni se sube al repositorio.

## Configuración, una sola vez

1. **Crear la cuenta de servicio.** En la consola de Google Cloud
   (console.cloud.google.com), en un proyecto propio: *APIs y servicios →
   Biblioteca*, habilitar **Google Search Console API** y **Google Analytics
   Data API**. Después *IAM y administración → Cuentas de servicio → Crear*.
   Un nombre como `capacero-lector`; no necesita ningún rol del proyecto.
2. **Descargar su clave.** En la cuenta creada, *Claves → Agregar clave →
   JSON*. Se descarga un archivo con `client_email` y `private_key`.
3. **Darle lectura en Search Console.** En la propiedad de capacero.online:
   *Configuración → Usuarios y permisos → Agregar usuario*, con el
   `client_email` de la cuenta y permiso **Restringido**.
4. **Darle lectura en Analytics.** En la propiedad GA4 del sitio (la del
   identificador `G-SV0MDSTQL4`): *Administrar → Gestión de acceso a la
   propiedad → +*, con el mismo `client_email` y rol **Lector**. En
   *Administrar → Detalles de la propiedad* figura el **ID de propiedad**, un
   número; es distinto del `G-SV0MDSTQL4`.
5. **Guardar los secretos en el entorno de Claude Code.** En el menú del
   entorno de la nube (en la barra de título de la sesión, *Editar*), en la
   sección de secretos de red o como variables de entorno:

   | Variable | Valor |
   |---|---|
   | `GOOGLE_SERVICE_ACCOUNT_JSON` | el contenido completo del archivo JSON del paso 2 |
   | `GA4_PROPERTY_ID` | el número del paso 4 |
   | `GSC_SITE_URL` | solo si la propiedad de Search Console es de prefijo de URL: `https://capacero.online/`. Si es de dominio, no hace falta |

   Una sesión nueva toma los valores.

## Uso

```
php bin/google-report.php                    # los dos, últimos 28 días
php bin/google-report.php --days=7
php bin/google-report.php --only=gsc --path=/noticia/
php bin/google-report.php --only=ga --limit=50
```

Search Console tarda unos dos días en consolidar sus datos, así que el rango
termina anteayer: leer un día incompleto parece una caída que no existe.

## Qué devuelve

- **Search Console:** consultas y páginas con clics, impresiones, CTR y
  posición media.
- **Analytics 4:** páginas con vistas, sesiones y tasa de interacción, y las
  sesiones por canal (orgánico, directo, referido, social).

## Si falla

| Mensaje | Causa |
|---|---|
| `Falta la variable GOOGLE_SERVICE_ACCOUNT_JSON` | el secreto no está cargado, o la sesión empezó antes de cargarlo |
| `Invalid grant: account not found` | la clave es de una cuenta borrada, o el JSON está incompleto |
| `User does not have sufficient permission` (Search Console) | falta el paso 3, o `GSC_SITE_URL` no coincide con el tipo de propiedad |
| `403` en Analytics | falta el paso 4, o `GA4_PROPERTY_ID` es el `G-…` en vez del número |
| `API has not been used in project` | falta habilitar la API del paso 1 en ese proyecto |
