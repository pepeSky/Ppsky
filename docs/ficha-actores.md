# Registro y ficha de actores

Esta corrección se aplica después del parche de Actores y de su corrección de migración MySQL.

```bash
cd /Users/jfrv/Sites/PepeSky/pepesky
git apply --check ~/Downloads/PepeSky-Actores-Ficha.patch
git apply ~/Downloads/PepeSky-Actores-Ficha.patch
php artisan migrate
php artisan optimize:clear
```

Si la comprobación falla, revisar la salida antes de aplicar. El parche añade una migración nueva: no vuelve a modificar la migración que ya terminó correctamente.

## Interfaz

Actores y Registrar actor muestran `Sistema: …` debajo del título. La selección del sistema activo corresponde al dashboard. La tabla muestra el nombre del actor y sus cohesiones; las clasificaciones, códigos y datos formales se consultan en Ver.

Registrar actor comienza por el nombre y las cohesiones Existencial, Ocio y Negocio. Se puede asociar una identidad existente o registrar una nueva con sus datos formales desde el mismo formulario, si el usuario tiene permiso para administrar identidades. Al escribir el nombre exacto de una identidad visible, se reutiliza esa identidad, salvo que se elija registrar una nueva o se proporcione otro documento. Las personas con el mismo nombre no se fusionan por su nombre. Si hay varias coincidencias, se solicita elegirla explícitamente.

El nombre natural se compone de primer nombre, segundo nombre, apellido paterno y apellido materno; el tercer nombre se conserva como dato de identidad. Las personas jurídicas utilizan su razón social. Para datos anteriores incompletos se conserva el nombre de entidad como referencia. Si el nombre coincide con el de la identidad, se calcula desde ella para reflejar cambios de sus datos. Un nombre de actor expresamente distinto se conserva sin modificar la identidad.

Importar actor tiene un botón y formulario propios. Recibe la referencia al sistema y código de origen, comparte la identidad y permite definir las cohesiones locales. No copia automáticamente las clasificaciones, códigos ni permisos del otro sistema; cualquiera de sus códigos de clasificación permite referenciar al actor de origen.

## Clasificaciones y ficha

Un solo actor puede tener las cinco clasificaciones del ejemplo de JFRV: UnNE tipo 3 (Empresario Individual), UnSe tipo 5 (Individuo), UnSo tipo 6 (Servicios), UnOp tipo 7 (Trabajador) y UnEl tipo 9 (Padre). Cada clasificación recibe su código de la serie correspondiente del sistema. Se conserva el código anterior, sin renumerar actores ni fusionarlos.

La ficha muestra las cohesiones, los datos formales autorizados y una tabla de clasificaciones. Agregar clasificación SyQuAc permite asociarla opcionalmente a una cohesión existente del actor. Registrar otra clasificación de la misma unidad y tipo conserva su código, actualizando esa asociación. Quitar una cohesión del actor quita su asociación actual a las clasificaciones y conserva los códigos.

Los nuevos vínculos se establecen entre códigos de clasificación, respetando UnNE → UnSe/UnSo/UnOp/UnEl, UnSe → UnSo/UnOp/UnEl, UnSo → UnOp/UnEl y UnOp → UnEl. Esto permite vincular diferentes unidades de un mismo actor y múltiples unidades superiores. Los vínculos anteriores entre registros de actores se conservan y se muestran separados para no reinterpretarlos automáticamente.

Origen y autorizaciones permanecen en una sección secundaria. Se retira el botón Archivar de la ficha; los registros históricos y su protección se conservan. Las reglas contractuales, la fuerza de cohesión y los cálculos de ingresos/egresos siguen pendientes: no se generan valores ni operaciones financieras por asignar una clasificación.

## Verificación

98 pruebas con SQLite, incluidas alta con nombre, composición del nombre, creación de identidad atómica, cohesiones, importación por códigos, cinco clasificaciones de un actor, conservación de series, vínculos y permisos. También se verificaron las regresiones de bitácora, actividades y recuperación de migración. La nueva migración crea estructuras adicionales y se debe ejecutar en MySQL local antes de utilizar la ficha.
