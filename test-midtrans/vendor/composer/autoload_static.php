<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbd5c0f31935fde53028909163c7c24e3
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Midtrans\\' => 9,
        ),
        'L' => 
        array (
            'Lenovo\\TestMidtrans\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Midtrans\\' => 
        array (
            0 => __DIR__ . '/..' . '/midtrans/midtrans-php/Midtrans',
        ),
        'Lenovo\\TestMidtrans\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbd5c0f31935fde53028909163c7c24e3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbd5c0f31935fde53028909163c7c24e3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbd5c0f31935fde53028909163c7c24e3::$classMap;

        }, null, ClassLoader::class);
    }
}
