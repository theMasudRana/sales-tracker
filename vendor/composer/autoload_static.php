<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit318b25b5b6854b2fd41f296d2b84d297
{
    public static $files = array (
        '9c9844c31be1fb599a951bfa5d6dbc4b' => __DIR__ . '/../..' . '/includes/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Sales\\Tracker\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Sales\\Tracker\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Sales\\Tracker\\Admin' => __DIR__ . '/../..' . '/includes/Admin.php',
        'Sales\\Tracker\\Admin\\Menu' => __DIR__ . '/../..' . '/includes/Admin/Menu.php',
        'Sales\\Tracker\\Admin\\Sales' => __DIR__ . '/../..' . '/includes/Admin/Sales.php',
        'Sales\\Tracker\\Admin\\Sales_List' => __DIR__ . '/../..' . '/includes/Admin/Sales_List.php',
        'Sales\\Tracker\\Frontend' => __DIR__ . '/../..' . '/includes/Frontend.php',
        'Sales\\Tracker\\Frontend\\Shortcode' => __DIR__ . '/../..' . '/includes/Frontend/Shortcode.php',
        'Sales\\Tracker\\Installer' => __DIR__ . '/../..' . '/includes/Installer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit318b25b5b6854b2fd41f296d2b84d297::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit318b25b5b6854b2fd41f296d2b84d297::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit318b25b5b6854b2fd41f296d2b84d297::$classMap;

        }, null, ClassLoader::class);
    }
}
