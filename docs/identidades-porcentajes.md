# Barras de completitud e Integración MeWo

La tabla de identidades muestra dos columnas compactas: Datos de contacto e Información personal. Cada una contiene únicamente el porcentaje y su barra; los datos se consultan en la ficha.

La distribución inicial es lineal: campos completos / campos definidos × 100, con un decimal. Un campo vacío o con solo espacios no suma. Un catálogo sin criterios muestra —, no un porcentaje inventado.

Contacto cuenta seis campos con igual peso: correo electrónico, teléfono, dirección, ciudad/comuna, región y país. Información personal cuenta inicialmente misión, visión, valores y lógica proposicional, con 25 % cada uno. Estos contenidos se registran desde Editar identidad, en Información personal; se almacenan separados de nombres, documento y contacto.

Los catálogos iniciales están en `config/identity_information.php`. Su definición y las reglas de vigencia se desarrollarán en Integración MeWo. El menú y título Instrucciones pasan a llamarse Integración; se conservan las rutas existentes. Este cambio todavía no agrega un editor de criterios en Integración ni calcula antigüedad o validez de los contenidos. Las barras miden campos informados, no calidad semántica ni vigencia.

Instalar sobre la versión con PepeSky-Identidades-Datos.patch ya aplicado:

```bash
cd /Users/jfrv/Sites/PepeSky/pepesky
git apply --check ~/Downloads/PepeSky-Identidades-Barras.patch
git apply ~/Downloads/PepeSky-Identidades-Barras.patch
php artisan migrate
php artisan optimize:clear
```

La nueva migración añade información personal y conserva los datos anteriores. No cambia las migraciones que ya terminaron. Si la comprobación del parche falla, revisar su salida antes de aplicar.

Verificación: 101 pruebas con SQLite, incluidas distribución lineal, campos vacíos, edición parcial sin pérdida de información, tabla compacta y las regresiones anteriores. Vistas compiladas y sintaxis PHP verificadas.
