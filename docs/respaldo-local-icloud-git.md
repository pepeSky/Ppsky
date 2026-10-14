# Respaldo local e iCloud antes del commit

La utilidad tools/backup-applications-icloud.php se ejecuta en el Mac del usuario. Reconoce el proyecto Laravel directamente en Sites/PepeSky y Sites/manTcic Life, o en un subdirectorio inmediato. Si encuentra cero o varios proyectos, se detiene para resolver la ruta.

Usar --inspect para revisar las rutas sin respaldar. La ejecución normal genera, para cada proyecto, project.zip (código, .git, .env, vendor y archivos del proyecto), database.sql (MySQL) y manifest.json (estado Git y origen). Las credenciales se leen localmente desde Laravel; no se imprimen ni se incorporan al comando o manifiesto. El ZIP contiene la configuración privada necesaria para restaurar el proyecto.

Se prepara una copia en un directorio temporal privado, se valida el ZIP, se verifica la copia mediante SHA-256 y se conserva la copia temporal. Destinos: iCloud Drive/Applications/PepeSky/Backups/FECHA e iCloud Drive/Applications/manTcic Life/Backups/FECHA. No elimina ni sobrescribe versiones anteriores. Si una operación falla, termina con código distinto de cero: no continuar con el commit por una cadena que ignore ese fallo.

La verificación corresponde a archivos locales y no prueba una restauración completa. Finder debe confirmar que iCloud termina su sincronización. No se conecta a GitHub ni se crea ningún commit desde la utilidad; esos pasos se realizan explícitamente después.

PepeSky tiene rama main y origin git@github.com:pepeSky/Ppsky.git según la salida del Mac. Tras respaldo exitoso y revisión del diff, preparar el código, crear el commit y hacer git push origin main sin forzar. Un rechazo del push requiere revisar la divergencia; no utilizar --force para resolverla.

El script no fue ejecutado aquí: requiere macOS y las bases reales del usuario. Se verificó su sintaxis. Los comandos mysqldump requieren los permisos adecuados para exportar esquema, datos, rutinas y eventos; un fallo impide declarar completo el respaldo.
