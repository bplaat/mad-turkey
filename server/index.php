<?php

spl_autoload_register(function ($class) {
    $file = ROOT . '/' . $class . '.php';
    if (file_exists($file)) require_once $file;
});
spl_autoload_register(function ($class) {
    $file = ROOT . '/controllers/' . $class . '.php';
    if (file_exists($file)) require_once $file;
});
spl_autoload_register(function ($class) {
    $file = ROOT . '/models/' . $class . '.php';
    if (file_exists($file)) require_once $file;
});

function view ($_path, $_data = null) {
    if (!is_null($_data)) extract($_data);
    unset($_data);
    ob_start();
    eval('unset($_path) ?>' . preg_replace(
        ['/@view\((.*)\)/', '/@(.*)/', '/{{(.*)}}/U', '/{!!(.*)!!}/U'],
        ['<?php echo view($1) ?>', '<?php $1 ?>', '<?php echo htmlspecialchars($1, ENT_QUOTES, \'UTF-8\') ?>', '<?php echo $1 ?>'],
        file_get_contents(ROOT . '/views/' . str_replace('.', '/', $_path) . '.html')
    ));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

require_once ROOT . '/config.php';

Database::connect(DATABASE_DSN, DATABASE_USER, DATABASE_PASSWORD);

Auth::init();

require_once ROOT . '/routes.php';
