# Acciones del diccionario y conservación de registros

Acuerdo: primero se dialogan los cambios; después se implementan los aprobados. Las eliminaciones de registros se solicitan al contexto Comunicacional con los respaldos y resguardos legales correspondientes. No se inventa ni se ejecuta en esta entrega un circuito de aprobación jurídica.

La barra junto al término seleccionado ofrece Ver, Editar y Desvincular, con iconos y etiquetas accesibles. Ver abre la ficha con descripción, fechas y definiciones. Editar permite modificar nombre y descripción si el modelo no está compartido con otras aplicaciones; conserva las definiciones. Si está compartido, se informa que primero debe revisarse su independencia o el acuerdo pertinente y no se propagan cambios.

Desvincular solicita confirmación y marca is_active=false en application_model. No borra el modelo, las definiciones ni la asociación. La fila y sus fechas se conservan; no se pretende representar con ella un registro completo de auditoría. Reasociar desde Agregar modelo o desde una importación reactiva la fila existente, sin duplicarla. La migración añade solamente ese booleano a la tabla existente y mantiene activas las asociaciones previas.

Se retira Eliminar del listado de Aplicaciones. El middleware web bloquea solicitudes DELETE a /admin y /app con un mensaje que remite a Comunicación. Esto incluye llamadas directas a las rutas, no solo botones. La protección es para rutas administrativas y de la aplicación; no modifica servicios internos, comandos de mantenimiento, ni controles de seguridad de Jetstream. Otras vistas heredadas pueden conservar controles que quedarán bloqueados por el middleware; su revisión visual es independiente.

La independencia entre modelos de sistemas y aplicaciones, la procedencia de los modelos importados y la igualdad mediante acuerdos jurídicos siguen pendientes de revisión específica, como se acordó. El esquema actual todavía contiene modelos compartidos por usuario; no se ha añadido una entidad de acuerdo ni se ha migrado ahora la propiedad de modelos. Esta entrega no sustituye esa revisión.

Validación: pruebas SQLite, acciones Livewire, bloqueo de solicitudes DELETE, conservación y reactivación de asociaciones, edición restringida de modelos compartidos, sintaxis PHP y compilación Blade. Los tests anteriores sobre DELETE se ajustaron al bloqueo general vigente, conservando las comprobaciones de protección de datos.
