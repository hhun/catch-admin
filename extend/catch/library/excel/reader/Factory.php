<?php

declare(strict_types=1);

namespace catch\library\excel\reader;

use catch\exceptions\FailedException;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Ods;
use PhpOffice\PhpSpreadsheet\Reader\Slk;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xml;

class Factory
{
    /**
     * make reader
     *
     * @time 2021年04月01日
     * @param $filename
     * @return mixed
     */
    public static function make($filename)
    {
        $ext = mb_strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (isset(self::readers()[$ext])) {
            return app()->make(self::readers()[$ext]);
        }

        throw new FailedException('Dont Support The File Extension');
    }


    /**
     * readers
     *
     * @time 2021年04月01日
     * @return string[]
     */
    protected static function readers(): array
    {
        return [
            'xlsx' => Xlsx::class,
            'xml' => Xml::class,
            'ods' => Ods::class,
            'slk' => Slk::class,
            'csv' => Csv::class,
        ];
    }
}
