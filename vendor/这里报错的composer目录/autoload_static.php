<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit81095f4525fbbb2417f4d44580f22687
{
    public static $files = array (
        '841780ea2e1d6545ea3a253239d59c05' => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu/functions.php',
        '5dd19d8a547b7318af0c3a93c8bd6565' => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu/Http/Middleware/Middleware.php',
    );

    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\composer\\' => 15,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
        'Q' => 
        array (
            'Qiniu\\' => 6,
        ),
        'M' => 
        array (
            'MyCLabs\\Enum\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application',
        ),
        'Qiniu\\' => 
        array (
            0 => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu',
        ),
        'MyCLabs\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/php-enum/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit81095f4525fbbb2417f4d44580f22687::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit81095f4525fbbb2417f4d44580f22687::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit81095f4525fbbb2417f4d44580f22687::$classMap;

        }, null, ClassLoader::class);
    }
}
