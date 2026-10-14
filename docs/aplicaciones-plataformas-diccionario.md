# Aplicaciones: sistema activo, plataformas y diccionario

El sistema de una aplicación nueva se toma en el servidor del sistema activo en el dashboard. El formulario no permite seleccionarlo ni modificarlo; enviar system_id expresamente se rechaza. Cambiar de sistema se realiza desde el dashboard. El listado y las rutas directas solo admiten las aplicaciones propias del sistema activo.

Las aplicaciones anteriores con system_id nulo se mantienen identificadas como Sin sistema asignado. No se clasifican por sus nombres. Se pueden revisar en el listado; al guardar su edición quedan vinculadas explícitamente al sistema activo, con un aviso visible. Las ya asignadas conservan su sistema.

Plataformas compatibles registra dónde se utiliza la solución. Los requisitos distinguen las necesidades del navegador y del servidor. No se infiere compatibilidad a partir del lenguaje de programación.

Modelos muestra cantidad, los lenguajes registrados en las definiciones y un enlace al diccionario. No se considera la plataforma un lenguaje del modelo. Si no hay definiciones con lenguaje, se informa Sin lenguajes referenciados; crear un modelo no inventa un lenguaje ni una definición.

Agregar modelo abre x-dialog-modal de Jetstream mediante Livewire 3. Solicita nombre y descripción como referencia comunicacional. El popup guarda y asocia el término sin recargar el formulario principal, conservando los cambios pendientes de la aplicación. Permite también asociar modelos ya existentes. La edición principal no reemplaza ni borra las asociaciones del diccionario.

El diccionario presenta búsqueda, contador de resultados, lista paginada de términos y ficha con descripción personal y definiciones agrupadas por lenguaje. Solo muestra términos asociados a la aplicación; el término solicitado se comprueba contra esa asociación. No incorpora definiciones externas sin registrarlas.

Se reutilizan models, definitions, languages y application_model. Los modelos siguen perteneciendo al usuario; nombre + usuario conserva su unicidad y su descripción se comparte entre apps. No se introduce una tabla Diccionario ni nuevas migraciones en esta corrección.

El servicio ApplicationAccess y el componente vuelven a comprobar propietario y sistema activo en cada operación, incluidas las peticiones Livewire. El id de la aplicación es una propiedad Locked. Las plataformas permanecen en los tres campos añadidos por el parche anterior.

Validación: pruebas funcionales de alcance y diccionario, pruebas de acciones Livewire, sintaxis PHP y compilación Blade con SQLite aislado. No se realizó comprobación visual en navegador: el entorno no dispone del ejecutable Chromium. La modificación no requiere cambios de esquema.

Aplicar después de PepeSky-Aplicaciones-Diccionario.patch: git apply --check, git apply y php artisan optimize:clear. Si falla la comprobación, detenerse sin forzar el parche.
