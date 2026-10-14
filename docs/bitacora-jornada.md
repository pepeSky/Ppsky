# Bitácora de la jornada

La entrada Bitácora abre una jornada automática por sistema y fecha. Conserva el histórico anterior. Las proposiciones se agregan desde esta vista. Programar una actividad la sitúa en el día elegido; no implica que se haya ejecutado. Las actividades sin programar son las disponibles del sistema para ese día.

Los indicadores muestran tiempo registrado y porcentaje de la duración del día local (America/Santiago). Los períodos de interacción se clasifican por la cohesión existente: 0:0 existencial, 0:1 ocio y 1:1 negocio. Descanso se registra explícitamente, sin inventar una nueva cohesión. Los intervalos no pueden superponerse para un mismo usuario, incluso entre sistemas. El tiempo no registrado no cuenta como descanso. Los datos anteriores no contienen duración; deben registrarse períodos para obtener indicadores reales.

Instalación sobre la corrección anterior de Bitácora:

```bash
cd /Users/jfrv/Sites/PepeSky/pepesky
git apply --check ~/Downloads/PepeSky-Bitacora-Jornada.patch
git apply ~/Downloads/PepeSky-Bitacora-Jornada.patch
php artisan migrate
php artisan view:clear
```

Si la comprobación falla, no aplicar el parche por la fuerza. Revisar los archivos indicados por Git. No ejecutar migrate:fresh: eliminaría los datos.

Verificación: abrir Bitácora dos veces debe conservar la misma jornada. Agregar una proposición debe regresar a la jornada. Programar una actividad debe moverla a la lista programada. Registrar una interacción de tres horas en un día de 24 horas debe mostrar tres horas y 12,5 % en su categoría. Histórico permite consultar los registros anteriores.
