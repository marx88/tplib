<?php

namespace marx\filepath;

class Path
{
    /**
     * 分隔符.
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * 获取应用根目录.
     *
     * @return string
     */
    public static function appRoot()
    {
        return env('root_path');
    }

    /**
     * 拼接根目录并统一分隔符.
     *
     * @param string $path 不带root的路径
     * @param mixed  $root root路径，默认为应用根目录
     *
     * @return string
     */
    public static function splicingRoot($path, $root = '')
    {
        return str_replace(
            [static::DS.static::DS, static::DS.static::DS.static::DS],
            static::DS,
            $root ?: static::appRoot().str_replace('/', static::DS, $path)
        );
    }

    /**
     * path转URL.
     *
     * @param string $path
     * @param string $prefix url前缀
     *
     * @return string
     */
    public static function toURL($path, $prefix = '')
    {
        $path = str_replace(static::DS, '/', $path);
        $prefix = str_replace(static::DS, '/', $prefix);

        if (!$path) {
            return '';
        }

        return str_replace(['//', '///', '////', '/////'], '/', $prefix.'/'.$path);
    }
}
