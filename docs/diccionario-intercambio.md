# Espacio e intercambio de diccionarios

La tabla de Aplicaciones muestra el sistema activo en el encabezado, sin repetirlo por fila. La ubicación se conserva en la ficha Ver. El listado mantiene Aplicación, Descripción, Plataformas compatibles, Modelos y Acciones; contador y enlace al diccionario tienen espacio para evitar cortes innecesarios. Las aplicaciones sin sistema mantienen el aviso explícito.

En el diccionario, Importar términos abre un diálogo Jetstream. Permite elegir otra aplicación propia del sistema activo y marcar términos. Reutiliza sus modelos y conserva descripciones, definiciones y lenguajes, sin copiar ni duplicar los registros. No presenta aplicaciones de otros sistemas ni de otros usuarios como origen.

Exportar términos descarga un archivo JSON con todos los términos de la aplicación. Incluye format=pepesky.dictionary, version=1 y, por término, nombre, descripción y definiciones con su lenguaje (nombre, código, descripción). Los ids internos y las cuentas no forman parte del formato portable.

Importar archivo acepta ese JSON: máximo 2 MB, 1.000 términos y 100 definiciones por término. Se valida el formato antes de escribir. Los términos nuevos pertenecen al usuario actual. Si existe el mismo nombre y descripción, se reutiliza; las definiciones nuevas se agregan sin borrar las anteriores. Si el nombre coincide con otra descripción o un lenguaje tiene un código incompatible, el término se omite y se informa su nombre. Nunca se reemplaza la descripción vigente ni se cambia el código de un lenguaje existente. Repetir el intercambio no duplica asociaciones ni definiciones.

Las importaciones de archivo se procesan en una transacción y bloquean la fila del usuario para coordinar la unicidad de su diccionario. Cada acción del componente vuelve a comprobar usuario y sistema activo. Una selección de origen que incluya términos ajenos se rechaza antes de asociar datos. Al terminar, Livewire actualiza los términos sin recargar la página.

No se crean tablas ni migraciones. Se conserva la descripción compartida entre las apps que utilizan el mismo modelo.

Validación: pruebas SQLite y acciones Livewire para intercambio, descarga, formato inválido, conflictos, nuevos lenguajes, reimportación y aislamiento entre sistemas; revisión de sintaxis PHP y compilación Blade. No se realizó verificación visual en navegador.
