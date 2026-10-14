<?php
// Run locally on the user's Mac. Never commit the backup files or database credentials.
use Symfony\Component\Process\Process;

if (PHP_OS_FAMILY !== 'Darwin') {
    fwrite(STDERR, "Esta utilidad se ejecuta en su Mac.\n"); exit(1);
}

try {
    if (($argv[1] ?? '') === '--dump-source') {
        $project = $argv[2]; $output = $argv[3];
        chdir($project);
        require $project.'/vendor/autoload.php';
        $app = require $project.'/bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $cfg = Illuminate\Support\Facades\DB::connection()->getConfig();
        if (($cfg['driver'] ?? '') !== 'mysql' || empty($cfg['database'])) {
            throw new RuntimeException('La utilidad requiere una conexión MySQL configurada.');
        }
        $dump = (new Symfony\Component\Process\ExecutableFinder)->find('mysqldump');
        if (!$dump) {
            foreach (['/usr/local/opt/mysql@8.0/bin/mysqldump', '/opt/homebrew/opt/mysql@8.0/bin/mysqldump', '/usr/local/bin/mysqldump'] as $candidate) {
                if (is_executable($candidate)) { $dump = $candidate; break; }
            }
        }
        if (!$dump) { throw new RuntimeException('No se encontró mysqldump.'); }
        $cmd = [$dump, '--single-transaction', '--quick', '--no-tablespaces', '--set-gtid-purged=OFF', '--routines', '--events', '--triggers',
            '--host='.($cfg['host'] ?? '127.0.0.1'), '--port='.($cfg['port'] ?? 3306), '--user='.($cfg['username'] ?? ''),
            '--result-file='.$output, '--databases', $cfg['database']];
        if (!empty($cfg['unix_socket'])) { $cmd[] = '--socket='.$cfg['unix_socket']; }
        $process = new Process($cmd, $project, ['MYSQL_PWD' => (string) ($cfg['password'] ?? '')]);
        $process->setTimeout(null)->run();
        if (!$process->isSuccessful()) { throw new RuntimeException('mysqldump falló: '.$process->getErrorOutput()); }
        if (!is_file($output) || filesize($output) === 0) { throw new RuntimeException('El respaldo SQL está vacío.'); }
        exit(0);
    }

    require dirname(__DIR__).'/vendor/autoload.php';
    $home = getenv('HOME');
    $cloud = $home.'/Library/Mobile Documents/com~apple~CloudDocs/Applications';
    if (!is_dir($cloud)) { throw new RuntimeException('No existe la carpeta Applications de iCloud Drive.'); }
    $projects = [];
    foreach (['PepeSky', 'manTcic Life'] as $label) {
        $base = $home.'/Sites/'.$label;
        $candidates = array_merge([$base], glob($base.'/*', GLOB_ONLYDIR) ?: []);
        $found = array_values(array_filter($candidates, fn ($path) => is_file($path.'/artisan') && is_file($path.'/vendor/autoload.php')));
        if (count($found) !== 1) { throw new RuntimeException('Debe existir un único proyecto Laravel con vendor instalado dentro de '.$base); }
        $projects[$label] = $found[0];
    }
    if (($argv[1] ?? '') === '--inspect') {
        foreach ($projects as $label => $project) { echo $label.': '.$project."\n"; }
        echo 'Destino: '.$cloud."\n"; exit(0);
    }
    $stamp = (new DateTimeImmutable('now', new DateTimeZone('America/Santiago')))->format('Y-m-d_His').'_'.bin2hex(random_bytes(3));
    $staging = sys_get_temp_dir().'/mewo-backup-'.$stamp;
    if (!mkdir($staging, 0700, true)) { throw new RuntimeException('No se pudo crear el directorio temporal.'); }
    foreach ($projects as $label => $project) {
        echo 'Respaldando '.$label."…\n";
        $local = $staging.'/'.$label;
        mkdir($local, 0700);
        (new Process([PHP_BINARY, __FILE__, '--dump-source', $project, $local.'/database.sql'], $project))->setTimeout(null)->mustRun();
        (new Process(['/usr/bin/ditto', '-c', '-k', '--sequesterRsrc', '--keepParent', $project, $local.'/project.zip']))->setTimeout(null)->mustRun();
        (new Process(['/usr/bin/unzip', '-tq', $local.'/project.zip']))->setTimeout(null)->mustRun();
        $head = new Process(['git', '-C', $project, 'rev-parse', 'HEAD']); $head->run();
        $status = new Process(['git', '-C', $project, 'status', '--short']); $status->run();
        file_put_contents($local.'/manifest.json', json_encode([
            'project' => $label, 'source' => $project, 'created_at' => date(DATE_ATOM),
            'git_head' => trim($head->getOutput()), 'git_status' => $status->getOutput(),
            'contents' => ['project.zip', 'database.sql'],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        $destination = $cloud.'/'.$label.'/Backups/'.$stamp;
        if (file_exists($destination) || file_exists($destination.'.en-proceso')) { throw new RuntimeException('El destino ya existe. No se sobrescribirá.'); }
        if (!is_dir(dirname($destination))) { mkdir(dirname($destination), 0700, true); }
        (new Process(['/usr/bin/ditto', $local, $destination.'.en-proceso']))->setTimeout(null)->mustRun();
        foreach (['project.zip', 'database.sql', 'manifest.json'] as $file) {
            if (hash_file('sha256', $local.'/'.$file) !== hash_file('sha256', $destination.'.en-proceso/'.$file)) {
                throw new RuntimeException('Falló la verificación de la copia: '.$label.'/'.$file);
            }
        }
        if (!rename($destination.'.en-proceso', $destination)) { throw new RuntimeException('No se pudo finalizar el respaldo.'); }
        echo 'Respaldo verificado: '.$destination."\n";
    }
    echo "Los archivos quedaron verificados en la carpeta local de iCloud. Compruebe en Finder que termine la sincronización.\n";
    echo 'Copia temporal conservada: '.$staging."\n";
} catch (Throwable $error) {
    fwrite(STDERR, "El respaldo no se completó: ".$error->getMessage()."\n"); exit(1);
}
