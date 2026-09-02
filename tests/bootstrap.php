<?php
declare(strict_types=1);
putenv('DB_PATH=' . sys_get_temp_dir() . '/sem-test-' . getmypid() . '.sqlite');
putenv('ADMIN_USER=admin');
putenv('ADMIN_PASSWORD=secret123');
require __DIR__ . '/../src/bootstrap.php';
