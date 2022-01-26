<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace catch;

use catch\exceptions\FailedException;
use catch\exceptions\ValidateFailedException;
use think\exception\ValidateException;
use think\facade\Filesystem;
use think\file\UploadedFile;

class CatchUpload
{
    /**
     * 阿里云
     */
    public const OSS = 'oss';

    /**
     * 腾讯云
     */
    public const QCLOUD = 'qcloud';

    /**
     * 七牛
     */
    public const QIQNIU = 'qiniu';

    /**
     * 驱动
     *
     * @var string
     */
    protected string $driver;

    /**
     * 本地
     */
    public const LOCAL = 'local';

    /**
     * path
     *
     * @var string
     */
    protected string $path = '';

    /**
     * upload files
     *
     * @param UploadedFile $file
     * @return string
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     */
    public function upload(UploadedFile $file): string
    {
        try {
            $this->initUploadConfig();

            $path = Filesystem::disk($this->getDriver())->putFile($this->getPath(), $file);

            if ($path) {
                $url = self::getCloudDomain($this->getDriver()).'/'.$this->getLocalPath($path);

                event('attachment', [
                    'path' => $path,
                    'url' => $url,
                    'driver' => $this->getDriver(),
                    'file' => $file,
                ]);

                return $url;
            }

            throw new FailedException('Upload Failed, Try Again!');
        } catch (\Exception $exception) {
            throw new FailedException($exception->getMessage());
        }
    }

    /**
     * 上传到 Local
     *
     * @time 2021年04月21日
     * @param $file
     * @return string
     */
    public function toLocal($file): string
    {
        return public_path().$this->getLocalPath(
            Filesystem::disk(self::LOCAL)->putFile($this->getPath(), $file)
        );
    }


    /**
     * 本地路径
     *
     * @time 2020年09月07日
     * @param $path
     * @return string
     */
    protected function getLocalPath($path): string
    {
        if ($this->getDriver() === self::LOCAL) {
            $path = str_replace(root_path('public'), '', \config('filesystem.disks.local.root')) . DIRECTORY_SEPARATOR . $path;

            return str_replace('\\', '/', $path);
        }

        return $path;
    }

    /**
     * 多文件上传
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/2/1
     * @param $attachments
     * @return array|string
     */
    public function multiUpload($attachments): array|string
    {
        if (! is_array($attachments)) {
            return $this->upload($attachments);
        }

        $paths = [];
        foreach ($attachments as $attachment) {
            $paths[] = $this->upload($attachment);
        }

        return $paths;
    }

    /**
     * get upload driver
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     * @return string
     */
    protected function getDriver(): string
    {
        if ($this->driver) {
            return $this->driver;
        }

        return \config('filesystem.default');
    }

    /**
     * set driver
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     * @param $driver
     * @throws \Exception
     * @return $this
     */
    public function setDriver($driver): self
    {
        if (! in_array($driver, [self::OSS, self::QCLOUD, self::QIQNIU, self::LOCAL])) {
            throw new FailedException(sprintf('Upload Driver [%s] Not Supported', $driver));
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     * @return string
     */
    protected function getPath(): string
    {
        return $this->path;
    }

    /**
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     *
     * @time 2020年01月25日
     * @param UploadedFile $file
     * @return array
     */
    protected function data(UploadedFile $file): array
    {
        return [
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMime(),
            'file_ext' => $file->getOriginalExtension(),
            'filename' => $file->getOriginalName(),
            'driver' => $this->getDriver(),
        ];
    }

    /**
     * 验证图片
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/2/1
     * @param array $images
     * @return $this
     */
    public function checkImages(array $images): static
    {
        try {
            validate(['image' => config('catch.upload.image')])->check($images);
        } catch (ValidateException $e) {
            throw new ValidateFailedException($e->getMessage());
        }

        return $this;
    }

    /**
     * 验证文件
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/2/1
     * @param array $files
     * @return $this
     */
    public function checkFiles(array $files): static
    {
        try {
            validate(['file' => config('catch.upload.file')])->check($files);
        } catch (ValidateException $e) {
            throw new ValidateFailedException($e->getMessage());
        }

        return $this;
    }

    /**
     * 初始化配置
     *
     * @time 2020年06月01日
     * @return void
     */
    public function initUploadConfig()
    {
        Utils::setFilesystemConfig();
    }

    /**
     * 获取云存储的域名
     *
     * @time 2020年01月25日
     * @param $driver
     * @return string|null
     */
    public static function getCloudDomain($driver): ?string
    {
        $driver = \config('filesystem.disks.'.$driver);

        return match ($driver['type']) {
            self::QIQNIU, self::LOCAL => $driver['domain'],
            self::OSS => self::getOssDomain(),
            self::QCLOUD => $driver['cdn'],
            default => throw new FailedException(sprintf('Driver [%s] Not Supported.', $driver)),
        };
    }

    /**
     * 获取 OSS Domain
     *
     * @time 2021年01月20日
     * @return string
     */
    protected static function getOssDomain(): string
    {
        $oss = \config('filesystem.disks.oss');

        if ($oss['is_cname'] === false) {
            return 'https://'.$oss['bucket'].'.'.$oss['end_point'];
        }

        return $oss['end_point'];
    }
}
