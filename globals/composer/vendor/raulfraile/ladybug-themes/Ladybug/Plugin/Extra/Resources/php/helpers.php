<?php



// helpers

/**
 * Gets the ladybug dumper
 *
 * @return Ladybug\Dumper
 */
function getLadybug()
{
    global $ladybug;

    if (is_null($ladybug)) {
        $ladybug = new \Ladybug\Dumper();
    }

    return $ladybug;
}

/**
 * Sets the theme
 * @param string $theme
 */
function ladybug_set_theme($theme)
{
    $ladybug = getLadybug();
    $ladybug->setTheme($theme);
}

function ladybug_set_format($format)
{
    $ladybug = getLadybug();
    $ladybug->setFormat($format);
}

function ladybug_set_option($name, $value)
{
    $ladybug = getLadybug();
    $ladybug->setOption($name, $value);
}

function ladybug_set_options(array $options)
{
    foreach ($options as $name => $value) {
        ladybug_set_option($name, $value);
    }
}

function ladybug_dump(/*$var1 [, $var2...$varN]*/)
{
    $ladybug = getLadybug();
    echo call_user_func_array(array($ladybug,'dump'), func_get_args());
}

function ladybug_dump_die(/*$var1 [, $var2...$varN]*/)
{
    $ladybug = getLadybug();
    echo call_user_func_array(array($ladybug,'dump'), func_get_args());
    die(1);
}

function ladybug_dump_class(/*$var1 [, $var2...$varN]*/)
{
    $ladybug = getLadybug();

    $currentOptions = $ladybug->getOptions();
    $ladybug->setOption('object_max_nesting_level', 1);

    echo call_user_func_array(array($ladybug,'dump'), func_get_args());

    $ladybug->setOptions($currentOptions);
}

function ladybug_dump_class_die(/*$var1 [, $var2...$varN]*/)
{
    echo call_user_func_array('ladybug_dump_class', func_get_args());
    die(1);
}

// Shortcuts
if (!function_exists('ld')) {
    function ld(/*$var1 [, $var2...$varN]*/)
    {
        call_user_func_array('ladybug_dump', func_get_args());
    }
}

if (!function_exists('ldd')) {
    function ldd(/*$var1 [, $var2...$varN]*/)
    {
        call_user_func_array('ladybug_dump_die', func_get_args());
    }
}

if (!function_exists('ldc')) {
    function ldc(/*$var1 [, $var2...$varN]*/)
    {
        call_user_func_array('ladybug_dump_class', func_get_args());
    }
}

if (!function_exists('ldcd')) {
    function ldcd(/*$var1 [, $var2...$varN]*/)
    {
        call_user_func_array('ladybug_dump_class_die', func_get_args());
    }
}
