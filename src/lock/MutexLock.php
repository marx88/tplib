<?php

namespace mphp\tplib\lock;

use think\Exception;

/**
 * 互斥锁.
 *
 * 根据key进行上锁、解锁，上锁后其它上锁会返回失败
 *
 * 目前用文件锁，后期可抽象接口，扩展锁类型：redis锁等
 */
class MutexLock
{
    protected $fp;

    protected $locked = false;

    protected $fileName;

    /**
     * MutexLock constructor.
     *
     * @param $prefix
     * @param $key
     *
     * @throws Exception
     */
    public function __construct($prefix, $key)
    {
        $this->init($prefix, $key);
    }

    /**
     * MutexLock destructor.
     *
     * @throws Exception
     */
    public function __destruct()
    {
        $this->free();
    }

    /**
     * 创建锁实例.
     *
     * @param string $prefix
     * @param string $key
     *
     * @throws Exception
     *
     * @return static
     */
    public static function make($prefix, $key)
    {
        return new static($prefix, $key);
    }

    /**
     * 获取锁
     *
     * @return bool
     */
    public function get()
    {
        $this->locked = flock($this->fp, LOCK_EX | LOCK_NB);

        return $this->locked;
    }

    /**
     * 释放锁
     *
     * @throws Exception
     */
    public function free()
    {
        if (!$this->locked) {
            return;
        }

        if (false === flock($this->fp, LOCK_UN)) {
            exception('释放互斥锁失败');
        }

        $this->locked = false;
    }

    /**
     * 初始化.
     *
     * @param string $prefix
     * @param string $key
     *
     * @throws Exception
     */
    protected function init($prefix, $key)
    {
        $path = $this->parsePath($prefix, $key);

        $this->checkPath($path);

        $this->openLockFile($path);
    }

    /**
     * 生成路径.
     *
     * @param string $prefix
     * @param string $key
     *
     * @return string
     */
    private function parsePath($prefix, $key)
    {
        $ds = DIRECTORY_SEPARATOR;
        $md5 = md5($prefix.'__'.$key);
        $first = substr($md5, 0, 2);
        $second = substr($md5, 2, 2);
        $path = env('runtime_path').'lock'.$ds.$prefix.$ds.$first.$ds.$second.$ds;

        $this->fileName = $md5.'.lock';

        return $path;
    }

    /**
     * 检查路径是否存在 没有则尝试创建.
     *
     * @param string $path
     *
     * @throws Exception
     */
    private function checkPath($path)
    {
        if (is_dir($path)) {
            return;
        }

        try {
            mkdir($path, 0755, true);
        } catch (\Exception $e) {
        }

        if (!is_dir($path)) {
            exception('创建互斥锁失败');
        }
    }

    /**
     * 获取要上锁的文件句柄.
     *
     * @param string $path
     *
     * @throws Exception
     */
    private function openLockFile($path)
    {
        $this->fp = fopen($path.$this->fileName, 'w+');
        if (false === $this->fp) {
            exception('读取互斥锁失败');
        }
    }
}
