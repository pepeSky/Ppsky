# Actores, identidades y SyQuAc por sistema

La tabla de Actores muestra ID, nombre y cohesiones. El encabezado indica el sistema activo, que se cambia desde el dashboard. Registrar actor e Importar actor son acciones distintas. Ver abre la ficha con identidad, clasificaciones SyQuAc, códigos y vínculos. Roles y permisos se administra desde la cabecera de Actores.

## Identidad y participación

Una identidad conserva los datos formales; varias participaciones pueden referenciarla. Los datos incluyen persona natural/jurídica/otro elemento, nombres y apellidos, razón social, nacionalidad, nacimiento, correo, teléfono y dirección. El identificador formal distingue país, tipo y número; se normalizan puntos, espacios y guiones para evitar duplicados. Esta normalización no verifica legalmente un documento ni valida el dígito verificador del RUT.

Las identidades existentes no se fusionan ni se borran. No se crean nuevas identidades para una entidad ya identificada. El responsable de los datos originales conserva su edición; consultar un actor externo no expone automáticamente sus datos privados.

Cada actor pertenece a un sistema. Los anteriores quedan sin asignación hasta que su dueño los edite y determine el sistema correcto. Sus IDs y relaciones anteriores se conservan. Un actor puede tener varias clasificaciones SyQuAc, cada una con unidad, tipo y código permanente. No es necesario duplicar el actor para agregar otra función. Se conservan la clasificación primaria y las referencias anteriores por compatibilidad. También sigue siendo posible que una identidad represente varios actores. Para otro sistema se registra una participación local, referenciando la identidad.

## Códigos

Formato: símbolo + posición estable del tipo + correlativo, por ejemplo `UnSe 1.002`. La serie es independiente por sistema, unidad y tipo. El correlativo avanza de uno en uno, usa un mínimo de tres cifras y no se reutiliza al archivar. Una fila bloqueada del sistema serializa las asignaciones concurrentes. La clasificación referencia las unidades SyQuAc existentes; la migración agrega solo los cinco símbolos que falten.

`config/actors.php` contiene los tipos en el orden visible de los selectores compartidos, comenzando en 0 (Ninguna). No reordenar ni reciclar sus posiciones. Algunas filas antiguas de Numbers no coinciden con ese orden: por ejemplo, Amigo(a) y Vecino(a) de UnEl. No se importan ni renumeran esas filas; una importación histórica deberá conservar y conciliar su catálogo de origen.

## Relaciones e interacciones

| Unidad superior | Unidades vinculables |
| --- | --- |
| UnNE | UnSe, UnSo, UnOp, UnEl |
| UnSe | UnSo, UnOp, UnEl |
| UnSo | UnOp, UnEl |
| UnOp | UnEl |

Los vínculos son de muchos a muchos dentro del sistema. Se admiten asociaciones directas y varias unidades superiores. No se obliga a completar toda la estructura antes de registrar un actor. Archivar conserva los vínculos históricos.

Cada actor admite simultáneamente las cohesiones Existencial (0:0), Ocio (0:1) y Negocio (1:1), determinadas por el sistema local. La fuerza de cohesión no recibe una puntuación inventada: su fórmula queda pendiente. Las relaciones existentes entre pares de actores se conservan; las nuevas requieren actores autorizados del mismo sistema. Las referencias externas se registran localmente antes de establecer relaciones locales.

## Origen y autorizaciones

Desde Importar actor, indicar el ID del sistema de origen y el código exacto de su actor vigente, dejando vacía la selección de identidad local. Se comparte su identidad mediante referencia, conservando el actor de origen. No se trasladan códigos ni permisos del otro sistema. La autorización local empieza pendiente y no pretende resolver responsabilidades contractuales.

Participación y acceso a la aplicación tienen estados independientes: pendiente, autorizado y bloqueado. Dar acceso exige vincular una cuenta que ya pertenezca al sistema. Los miembros existentes sin una decisión nueva conservan su acceso anterior; al administrar una cuenta vinculada, la decisión se vuelve explícita. Revocar o desvincular no restablece permisos anteriores. Las autorizaciones contractuales se desarrollarán posteriormente en Jurídico.

El usuario propietario de la identidad que representa al sistema, si es miembro de él, conserva la administración inicial. Puede crear roles con nombres libres y conjuntos de permisos, asignados exclusivamente a miembros de ese sistema. Los roles pueden coexistir y sus permisos se suman. Tener acceso a la aplicación no concede todos los permisos del módulo.

Permisos separados: consultar/registrar/modificar/archivar actores, clasificar, vincular unidades, determinar interacciones, autorizar participación, autorizar acceso, consultar datos formales propios, administrar identidades propias y administrar roles. La administración se puede delegar mediante el último permiso. Se registran cambios de actores, interacciones del sistema, vínculos y roles con usuario y sistema; no se registran contraseñas.

## Instalación

Este parche se aplica sobre la versión con `PepeSky-Bitacora-Jornada.patch` ya instalado. No publica cambios en GitHub.

```bash
cd /Users/jfrv/Sites/PepeSky/pepesky
git apply --check ~/Downloads/PepeSky-Actores-SyQuAc.patch
git apply ~/Downloads/PepeSky-Actores-SyQuAc.patch
php artisan migrate
php artisan optimize:clear
```

Si la comprobación falla, revisar los archivos que indique Git antes de aplicar. La migración agrega estructuras y conserva los registros existentes. Su reversión automática se bloquea para impedir perder códigos y decisiones de acceso; una reversión con datos requiere un procedimiento explícito.

En Sistema → Actores, seleccionar el sistema. Editar cada registro anterior para asignarlo y clasificarlo, o registrar una nueva participación. Administrar sus clasificaciones y vínculos desde Ver y sus autorizaciones desde Editar. Crear roles y asignarlos a los miembros necesarios desde Roles y permisos.

Las verificaciones automatizadas usan SQLite y cubren separación entre sistemas, referencias externas, datos privados, series, vínculos múltiples, autorizaciones, roles, archivado, proposiciones y regresión de Bitácora/actividades. MySQL del equipo local debe ejecutar la migración antes de utilizar la nueva vista. Las vistas de Bitácora y planificación conservan su presentación; sus selecciones internas respetan el sistema y los accesos revocados.
