<?php
declare(strict_types=1);
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$examples = [
    ['title' => '1. Validación de datos', 'description' => 'Validación de entrada en el servidor y salida HTML codificada.', 'url' => 'validation.php'],
    ['title' => '2. Inyección SQL', 'description' => 'Comparación entre consulta concatenada vulnerable y consulta preparada con PDO.', 'url' => 'sql-injection.php'],
    ['title' => '3. XSS reflejado', 'description' => 'Comparación entre salida sin codificar y salida codificada con htmlspecialchars.', 'url' => 'xss.php'],
    ['title' => '4. Protección CSRF', 'description' => 'Formulario de cambio de estado con token CSRF de sesión.', 'url' => 'csrf.php'],
];

$phpVersion = PHP_VERSION;
$pdoDrivers = implode(', ', PDO::getAvailableDrivers()) ?: 'ninguno';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Actividad 2.4 · Aplicaciones Seguras en la Nube</title>
  <style>
    :root{font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:#182230;background:#f4f7fb}
    body{max-width:960px;margin:0 auto;padding:2rem 1.2rem;line-height:1.55}
    header,.panel{background:#fff;border:1px solid #dce4ee;border-radius:16px;padding:1.4rem;margin-bottom:1rem}
    h1{line-height:1.2;margin-top:0} h2{margin-top:0}
    .meta{color:#526174;font-size:.95rem}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:1rem}
    .item{background:#fff;border:1px solid #dce4ee;border-radius:12px;padding:1rem}
    a{color:#075fc7;font-weight:650} code{background:#edf2f8;padding:.15rem .35rem;border-radius:5px}
    .notice{border-left:4px solid #d97706;padding:.7rem 1rem;background:#fff7ed}
  </style>
</head>
<body>
<header>
  <h1>Evaluación de seguridad de aplicaciones web con PHP</h1>
  <p><strong>Actividad 2.4 — Aplicaciones Seguras en la Nube</strong></p>
  <p class="meta">Autores: Jaime Manuel Cazalla Chica y Víctor Aguilar Méndez</p>
  <h2>Detalles de la pila</h2>
  <ul>
    <li>PHP <?= e($phpVersion) ?></li>
    <li>Servidor web: servidor integrado de PHP para demostración</li>
    <li>Base de datos: SQLite mediante PDO</li>
    <li>Contenerización: Docker</li>
  </ul>
  <p class="meta">Controladores PDO disponibles: <?= e($pdoDrivers) ?></p>
</header>
<section class="panel">
  <h2>Experimentos</h2>
  <p>En cada práctica se comparan patrones inseguros con alternativas recomendadas. Utiliza los ejemplos vulnerables únicamente en este laboratorio local.</p>
  <div class="grid">
    <?php foreach ($examples as $example): ?>
      <article class="item">
        <h3><?= e($example['title']) ?></h3>
        <p><?= e($example['description']) ?></p>
        <a href="<?= e($example['url']) ?>">Abrir experimento →</a>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<section class="panel">
  <h2>Uso responsable</h2>
  <p class="notice">Las rutas vulnerables son pruebas educativas intencionadas. No introduzcas datos reales, no expongas estas rutas a Internet y no reutilices el código vulnerable en producción.</p>
  <p>Consulta el <code>README.md</code> para instrucciones de ejecución, publicación de la imagen y despliegue en Google Cloud Run.</p>
</section>
</body>
</html>
