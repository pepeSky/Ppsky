# Recuperar la migración de actores en MySQL

El error 1553 aparece porque `actors_identity_id_unique` sostiene la clave foránea de `actors.identity_id`. La corrección crea primero `actors_identity_id_index`, un índice no único, en una operación separada; después elimina el único y permite varias participaciones de la misma identidad.

La migración comprueba columnas, índices, claves foráneas y tablas antes de crearlos. Así puede continuar cuando MySQL ya creó parte de la estructura antes del error, conservando los datos. No se modifica el registro de migraciones manualmente.

Aplicar este parche sobre `PepeSky-Actores-SyQuAc.patch` ya instalado:

```bash
cd /Users/jfrv/Sites/PepeSky/pepesky
git apply --check ~/Downloads/PepeSky-Actores-Fix-Migracion.patch
git apply ~/Downloads/PepeSky-Actores-Fix-Migracion.patch
php artisan migrate
php artisan optimize:clear
```

Si la comprobación del parche falla, detenerse y revisar su salida antes de aplicarlo. No se necesita eliminar columnas, restaurar tablas ni volver a aplicar el parche de actores.

Las verificaciones locales usan SQLite: 29 pruebas, incluidas recuperación después de crear columnas, reejecución sin duplicados y reparación de una clave foránea pendiente. El comportamiento del índice MySQL debe confirmarse al ejecutar `php artisan migrate` en la instalación local.
