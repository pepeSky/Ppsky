# Operación MeWo: contextos, procesos, actividades y tareas

Una actividad de proyecto puede ser atendida por varios contextos y procesos. Cada tarea registra una ejecución del proceso responsable. Los contextos simples mantienen su especialidad y alcance transversal; los ampliados conservan la secuencia de componentes que coordina su participación.

## Instalación sobre una base existente

```bash
composer install
php artisan migrate
php artisan db:seed --class=MewoOperationalSeeder
php artisan view:clear
```

La migración agrega relaciones y campos; conserva las actividades, tareas, contextos y procesos existentes. Incorpora las participaciones que se deducen de tareas históricas y los componentes de ampliaciones con códigos reconocidos. Las tareas anteriores mantienen sus identificadores y descripciones; su proceso y cuantificación se completan desde la pantalla de tareas.

No ejecutar `migrate:fresh` ni el `DatabaseSeeder` general para instalar esta funcionalidad: el segundo contiene operaciones históricas ajenas al catálogo MeWo. El seeder específico crea registros faltantes sin sustituir los procesos y procedimientos que ya se hayan editado. Sus asociaciones de verbos son aditivas.

## Pantallas

- **MeWo → Contextos:** consultar especialidades y crear ampliaciones ordenadas. Se acepta X + X al comienzo una única vez; se rechazan X + X + X y repeticiones posteriores. No se generan todas las combinaciones por anticipado.
- **MeWo → Procesos:** mantener procesos, entradas, resultados, procedimientos reutilizables y verbos en infinitivo. Un verbo puede participar en varios procesos y contextos.
- **MeWo → Actividades:** seleccionar proyecto, objetivo cuántico, contextos y procesos participantes. Un contexto ampliado incorpora sus componentes simples. Los procesos deben pertenecer a los contextos seleccionados.
- **Actividad → Tareas:** registrar operaciones concretas de sus procesos, estado y cuantía por unidad SyQuAc. El contexto responsable se deriva del proceso, no de un dato arbitrario del formulario.
- **MeWo → Orientación de proposiciones:** comparar la acción expresada con el catálogo y presentar alternativas. También se puede consultar una proposición almacenada mediante `/admin/mewo/propositions/{proposition}/suggestions`, con autorización por su identidad.

Los listados y modificaciones de actividades están restringidos a los proyectos de sistemas a los que pertenece el usuario. El catálogo MeWo compartido conserva la política actual de administración autenticada del repositorio; esta entrega no incorpora nuevos roles ni permisos por contexto.

## Objetivos ya implementados en la instalación local

El punto de partida publicado `db23d2a` no incluye `objetives.parent_id`, ni el controlador y las vistas de la jerarquía de objetivos que el usuario ya utiliza localmente. Esta entrega no reemplaza esa implementación. El adaptador `QuantumObjectives` aprovecha `parent_id` cuando existe y acepta únicamente el cuarto nivel: general → principal → específico → cuántico. Rechaza ciclos, padres de otro proyecto y otros niveles.

Sin esa jerarquía disponible, las actividades pueden prepararse sin vinculación. La pantalla informa la falta de objetivos cuánticos; los vínculos históricos no se borran al editar. Para usar el flujo completo en un checkout nuevo, primero debe incorporarse la implementación local de objetivos al repositorio. No se inventan registros o niveles para suplirla.

## Cuantificación y conservación

Las cantidades se registran en cada tarea y se suman una sola vez por actividad y unidad. No se suman entre unidades diferentes ni se multiplican por el número de contextos participantes. La cuantía registrada expresa trabajo, y no certifica automáticamente el cumplimiento del objetivo. El porcentaje de avance y los valores económicos requieren sus criterios y reglas de SyQuAc; no se deducen simplemente del número de tareas terminadas.

Una actividad con tareas no se elimina. Tampoco se retiran de ella contextos o procesos con tareas registradas. Las tareas finalizadas, en ejecución o con cuantificación se conservan; solo se permite eliminar borradores pendientes sin cantidad.

## Catálogo y clasificación lingüística

Los nombres de procesos siguen el apartado de contextos del extracto JFRV `Texto pegado(20260930-194731).txt`, secciones 3.3.2.1 y 3.3.2.1.12. Se conserva Salud como denominación vigente de Clínico. Los verbos asociados son una adaptación operativa editable, no una afirmación de que una norma externa prescriba esos infinitivos.

El clasificador es deliberadamente orientativo: reconoce infinitivos y formas conjugadas curadas, conserva ambigüedades, muestra el fragmento posterior al verbo y marca posibles negaciones. Reconoce «reparar» en «Necesito reparar…», sin confundir el verbo modal con la acción requerida. No es un analizador gramatical completo, no detecta todas las conjugaciones ni certifica sujeto/predicado o intención. No crea actividades ni activa contextos automáticamente. Los procedimientos se mantienen como definiciones en los procesos, separadas de las tareas ejecutadas.

## Verificación

```bash
DB_CONNECTION=sqlite DB_DATABASE=:memory: php vendor/bin/phpunit tests/Feature/MewoOperationsTest.php
php artisan route:list --path=mewo
php artisan route:list --path=activities
php artisan view:cache
```

Las pruebas utilizan una base SQLite en memoria, migran el esquema completo y simulan adicionalmente la jerarquía local de objetivos. Incluyen composición ordenada, validaciones de pertenencia, integridad de registros anteriores, aislamiento entre usuarios, cuantificación por unidad y renderizado HTTP de vistas. La base MySQL de la instalación del usuario no se modifica desde este entorno.
