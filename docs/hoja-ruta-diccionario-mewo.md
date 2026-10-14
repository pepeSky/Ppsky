# Hoja de ruta — Diccionario MeWo y generación de soluciones

Fecha: 14 de octubre de 2026. Las operaciones siguen el acuerdo de dialogar el alcance antes de implementarlo. Esta entrega implementa la primera fase; las siguientes requieren revisar sus decisiones y datos antes de operar.

## Principios acordados

- Un modelo es la referencia comunicacional del usuario dentro de su sistema. Tiene descripción y puede expresarse mediante varias definiciones o representaciones en distintos lenguajes.
- Las relaciones se establecen entre modelos completos de una aplicación y pueden orientar su estructura y comportamiento.
- El sistema activo procede del dashboard. Una aplicación nueva pertenece a ese sistema.
- Integrarse con Apple, Google, Falabella u otro actor mediante una aplicación propia y una API debe conservar el vocabulario del sistema. Una correspondencia técnica no constituye igualdad conceptual ni unificación jurídica.
- Modelos de sistemas independientes conservan su identidad y procedencia. La unificación y sus cambios internos requieren el acuerdo jurídico que establezca esa igualdad.
- Las eliminaciones de registros se solicitan a la administración de comunicación, con respaldos y resguardos legales. La desvinculación y desactivación conservan registros.
- Una representación textual en C, PHP, Objective-C, HTML, UML u otro destino no demuestra por sí sola soporte suficiente para generar código. Hay que especificar estructura, restricciones y reglas de transformación.

## Fase 1 — Diccionario operativo (implementada en este parche)

| Función | Resultado |
|---|---|
| Agregar término desde el diccionario | Reutiliza el modal existente; comunica la creación al listado mediante Livewire |
| Índice alfabético | Todos, A–Z, Ñ y #; las vocales con acento corresponden a su inicial; búsqueda e índice se combinan sin recargar |
| Lenguajes y definiciones | Registrar una definición o representación del término en un lenguaje existente, o crear un lenguaje/notación desde el diccionario |
| Resumen de modelos | Los lenguajes se obtienen automáticamente de las definiciones registradas |
| Relaciones de modelos completos | Sinónimo, antónimo, relacionado, más general/específico, contiene/parte de, depende de y requiere, con explicación opcional |
| Conservación | Desactivar una relación conserva la fila; los términos y definiciones tampoco se borran |
| Requisitos de ejecución | Retirados de las vistas; los valores anteriores se conservan al editar la app |
| Mensajes de eliminación | Remiten a la administración de comunicación |

Cambio de esquema: una sola tabla nueva, application_model_relations. Reutiliza applications, models, definitions y languages. Las relaciones se limitan a la aplicación y sus dos extremos deben estar asociados activamente a ella. Las simétricas se guardan una sola vez; las inversas de general/específico y contiene/parte de comparten una fila. No se ejecutan textos ni se generan archivos. No se impone transitividad ni un grafo sin ciclos: esas reglas dependen de la semántica que se acuerde para la programación.

Las definiciones nuevas se agregan sin sustituir las anteriores. La edición y ampliación de modelos compartidos se bloquean para evitar propagación entre apps mientras se revisa su propiedad. Registrar un nuevo término solicita nombre y descripción; luego se puede añadir su primera definición desde la ficha.

Criterio de cierre: creación y selección fluida, filtros con acentos/Ñ, definición en otro lenguaje visible en el resumen, relaciones con sentido e inversa correctos, rechazo de términos ajenos y conservación al desactivar.

## Fase 2 — Propiedad, procedencia e intercambio (siguiente diálogo)

Prioridad: resolver el modelo compartido actual antes de ampliar o automatizar la importación.

1. Precisar la propiedad del modelo dentro del sistema y la independencia de su uso en cada aplicación. Presentar el esquema y el efecto sobre los registros actuales antes de nuevas migraciones.
2. Permitir términos con el mismo nombre y significados distintos en sus ámbitos independientes. Mantener identificadores y procedencia; el texto coincidente no debe fusionar registros automáticamente.
3. Revisar la importación actual. Hoy reutiliza modelos por usuario/nombre y las importaciones internas comparten registros. Eso todavía no satisface la independencia acordada. Preparar vista previa de importación, correspondencias y conflictos; preservar originales.
4. Incorporar las relaciones al formato de exportación/importación después de resolver sus identificadores de origen/destino. El JSON actual transporta términos, descripciones, definiciones y lenguajes, pero no las nuevas relaciones.
5. Precisar qué datos representa el acuerdo jurídico: sistemas/actores, modelos involucrados, alcance, igualdad reconocida, vigencia y cambios autorizados. Revisar con el usuario antes de crear tablas o flujos.
6. Vista previa .MeWo y descripción automática: implementadas en la corrección posterior de nombres. La plantilla general sigue la captura del usuario (sistema activo como usuario, MeWo en PepeSky –Execution Quantum–); PepeSky utiliza el texto especial indicado. Las aplicaciones anteriores se actualizan individualmente al guardar, sin renombrado masivo.

Descripción especial ya indicada para PepeSky:

> Solución informática al problema de comunicación del usuario PepeSky, creada bajo las normas del mecanismo de trabajo MeWo en el sistema JFRV.

Criterio de cierre: modificar o importar en una app independiente no cambia otra; los modelos de origen se conservan; ninguna coincidencia o relación léxica activa una igualdad jurídica automáticamente.

## Fase 3 — Representaciones y generación de código (posterior)

Primero se especifica qué constituye soporte de un modelo en cada destino. El catálogo deberá distinguir lenguaje, framework, notación y herramienta. Se conserva “Mathematic” como nombre aportado por el usuario, pendiente de precisar la herramienta exacta antes de diseñar su adaptador.

Datos a acordar por modelo:

- Identificador interno y nombre utilizado en cada destino.
- Tipo de estructura: entidad, valor, propiedad, operación, interfaz u otra categoría aprobada.
- Atributos, tipos, cardinalidades, restricciones y unidades cuando correspondan.
- Operaciones, parámetros, resultados y reglas de comportamiento.
- Relaciones y la interpretación concreta que autoriza su transformación.
- Representación por destino, versión compatible, dependencias, biblioteca/framework y plantilla de generación.
- Estado del soporte: descrito, validado o apto para generar; errores y faltantes visibles.

Secuencia operativa propuesta:

1. Validar los datos y reglas del modelo para el destino elegido.
2. Construir una representación intermedia explícita; no interpretar cualquier descripción personal como código.
3. Generar una vista previa del código o documento técnico y su lista de archivos.
4. Verificar sintaxis y comportamiento con herramientas del destino.
5. Aprobar la inserción concreta antes de modificar una aplicación existente o publicar artefactos.

Comenzar por un destino y un caso pequeño acordados. C, PHP, Objective-C, HTML, UML, Laravel y la herramienta matemática requieren adaptadores y validaciones propios; no se declara compatibilidad universal por registrar su nombre. La generación debe informar qué modelos no tienen soporte completo, sin inventar estructuras ni ejecutar texto arbitrario del diccionario.

## Validación de esta entrega

Pruebas de componentes Livewire y SQLite: índice alfabético, creación/registro, definiciones y lenguajes, duplicados, relaciones simétricas, desactivación, aislamiento y protección de modelos compartidos. Sintaxis PHP, compilación Blade y suite general revisadas. Sin comprobación visual en navegador y sin ejecución sobre la base MySQL del PC.
