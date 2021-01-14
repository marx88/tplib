<?php

namespace marx\upload;

use marx\filepath\Path;
use think\exception\ValidateException;
use think\File;

/**
 * 文件上传.
 */
class Upload
{
    /**
     * 物理路径前缀 相对于项目根目录.
     *
     * @var string
     */
    public static $pathPre = '/public/upload/';

    /**
     * URL路径前缀 相对于web根目录.
     *
     * @var string
     */
    public static $urlPre = '/upload/';

    /**
     * 上传文件 返回url路径
     * 调用static::urlToPath转物理路径.
     *
     * @param File   $file
     * @param array  $validate
     * @param string $type
     *
     * @throws ValidateException
     *
     * @return string
     */
    public function file($file, $validate = [], $type = '文件')
    {
        if (!($file instanceof File)) {
            throw new ValidateException("{$type}异常");
        }

        $info = $file->validate($validate)->move(Path::splicingRoot(static::$pathPre));
        if (!$info) {
            throw new ValidateException($file->getError() ?: "{$type}上传失败");
        }

        return Path::toURL($info->getSaveName(), static::$urlPre);
    }

    /**
     * 批量上传.
     *
     * @param array|File $files
     * @param array      $validate
     * @param string     $type
     *
     * @throws ValidateException
     *
     * @return array
     */
    public function batch($files, $validate = [], $type = '文件')
    {
        $uploads = [];

        if (!is_array($files)) {
            $files = [$files];
        }

        try {
            foreach ($files as $file) {
                $uploads[] = $this->file($file, $validate, $type);
            }
        } catch (ValidateException $e) {
            foreach ($uploads as $value) {
                Path::deleteFile(static::urlToPath($value));
            }

            throw $e;
        }

        return $uploads;
    }

    /**
     * url转path
     * 注意：该path不是物理路径，需要拼接项目根路径.
     *
     * @param string $path
     *
     * @return string
     */
    public static function urlToPath($path)
    {
        return str_replace(static::$urlPre, static::$pathPre, $path);
    }

    /**
     * 获取上传文件的临时目录的物理路径.
     *
     * @param mixed $file
     * @param array $rules 验证规则
     * @param mixed $rule
     *
     * @return string
     */
    public static function getTempPath($file, $rule = [])
    {
        if (!$file || !($file instanceof File)) {
            exception('缺少上传文件');
        } elseif (!empty($file->getInfo('error'))) {
            exception('上传文件错误：'.$file->getInfo('error'));
        } elseif (!$file->isValid()) {
            exception('非法上传文件');
        } elseif ($rule && !$file->check($rule)) {
            exception($file->getError() ?: '文件检验失败');
        }

        return $file->getRealPath();
    }
}
