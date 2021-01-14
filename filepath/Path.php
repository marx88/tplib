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
        $ds = static::DS;

        return str_replace(
            [$ds.$ds.$ds.$ds.$ds, $ds.$ds.$ds.$ds, $ds.$ds.$ds, $ds.$ds],
            $ds,
            ($root ?: static::appRoot()).$ds.str_replace('/', $ds, $path)
        );
    }

    /**
     * trim根目录.
     *
     * @param string $path
     * @param string $root
     *
     * @return string
     */
    public static function trimRoot($path, $root = '')
    {
        return str_replace($root ?: static::appRoot(), '', $path);
    }

    /**
     * DS转/.
     *
     * @param string $path
     * @param string $prefix url前缀
     *
     * @return string
     */
    public static function toURL($path, $prefix = '')
    {
        if (!$path) {
            return '';
        }

        $prefix = str_replace(static::DS, '/', $prefix);

        return str_replace(
            ['/////', '////', '///', '//'],
            '/',
            $prefix.'/'.str_replace(static::DS, '/', static::trimRoot($path))
        );
    }

    /**
     * 删除文件.
     *
     * @param string $path
     * @param string $root
     */
    public static function deleteFile($path, $root = '')
    {
        $path = static::splicingRoot($path, $root);
        if (is_file($path)) {
            unlink($path);
        }
    }

    /**
     * 删除目录.
     *
     * @param string $dir
     *
     * @return bool
     */
    public static function deleteDir($dir)
    {
        if (!is_dir($dir) || !file_exists($dir)) {
            return true;
        }

        $dir_handle = opendir($dir);
        if (false === $dir_handle) {
            return false;
        }

        $empty = true;
        while ($filename = readdir($dir_handle)) {
            if (in_array($filename, ['.', '..'], true)) {
                continue;
            }

            $filepath = $dir.static::DS.$filename;
            if (is_dir($filepath)) {
                if (false === $empty = static::deleteDir($filepath)) {
                    break;
                }
            } elseif (is_file($filepath)) {
                if (false === $empty = unlink($filepath)) {
                    break;
                }
            } else {
                $empty = false;

                break;
            }
        }
        closedir($dir_handle);

        return true === $empty && rmdir($dir);
    }
}
