# Bitácora del Sistema

La entrada existente **MeWo → Bitácora** (`/admin/posts`) abre el histórico del Sistema activo, con selector igual al de Planificación. La cabecera ofrece **Agregar proposición**, **Crear bitácora** y **Agregar modelo**.

Una bitácora contiene fecha, título, Sistema, proyecto opcional y resumen de trabajo. Sus proposiciones, modelos y actividades se vinculan mediante relaciones; no se copian ni se convierten en publicaciones. Las tareas se consultan desde las actividades relacionadas.

## Instalar la corrección

El parche parte exactamente de los archivos locales aportados en `PepeSky-bitacora-actual.zip`, más el parche MeWo ya instalado. En la carpeta del proyecto:

```bash
git apply --check ~/Downloads/PepeSky-Bitacora-Sistema.patch
git apply ~/Downloads/PepeSky-Bitacora-Sistema.patch
php artisan migrate
php artisan view:clear
php artisan config:clear
```

No requiere ejecutar seeders ni volver a cargar el catálogo de contextos. La migración añade tablas de bitácoras y asociaciones sin eliminar registros existentes.

## Uso

1. Seleccionar el Sistema en la Bitácora.
2. **Agregar proposición** abre el formulario existente de proposiciones, conservando el Sistema de origen. Después se puede seleccionar ese registro al crear una bitácora.
3. **Crear bitácora** guarda la fecha y el resumen, y permite asociar proposiciones, modelos y actividades existentes.
4. **Abrir** presenta el documento de trabajo, sus proposiciones, actividades/tareas y modelos.
5. Los botones de agregar proposición y modelo desde una bitácora abierta incorporan el nuevo registro a esa bitácora y regresan a su detalle.
6. **Administrar** permite actualizar el resumen e incorporar más registros, conservando las asociaciones históricas anteriores.

Los modelos se guardan en la tabla `models` existente. Las proposiciones siguen teniendo actor y oración; las asociaciones se guardan por separado. El flujo original de proposiciones sin contexto de bitácora permanece disponible.

Los antiguos registros `posts` se conservan. Si existen para el usuario, se ofrecen en una sección separada de registros anteriores, sin inventar a qué Sistema o bitácora pertenecían.

## Corrección del alcance MeWo

**Instrucciones** presenta el catálogo de contextos y procesos definidos. Se retiró el formulario y la ruta de creación manual de contextos ampliados. Se retiraron las rutas de la pantalla independiente de orientación de proposiciones. El servicio interno de comparación de verbos se conserva, pero esta entrega no afirma que el ingreso de proposiciones ejecute automáticamente una clasificación o cree actividades.

El histórico controla el acceso mediante pertenencia del usuario al Sistema. Solo admite proyectos y actividades de ese Sistema, y modelos y proposiciones autorizados. No incluye eliminación de bitácoras ni operaciones de borrado sobre los registros anteriores.

## Verificaciones

Las pruebas de `BinnacleTest` utilizan SQLite en memoria y las migraciones reales. Cubren navegación, botones, histórico por Sistema, asociaciones, flujo existente de proposiciones, acceso entre usuarios, conservación de historia y retirada de las pantallas fuera de alcance. La implementación de Planificación se conserva sin modificaciones.
