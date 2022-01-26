<?php

declare(strict_types=1);

/**
 * @filename  ProgressBar.php
 * @createdAt 2020/6/20
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */

 namespace catch\library;

 use think\console\Output;

 class ProgressBar
 {
     protected Output $output;

     protected int $total;

     protected int $current = 0;

     protected string $header = '[x] ';

     protected int $length = 100;

     protected $average;

     public function __construct(Output $output, int $total)
     {
         $this->output = $output;

         $this->total = $total;

         $this->average = $this->length / $total;
     }

     /**
      * 开始
      *
      * @time 2020年06月20日
      * @return void
      */
     public function start()
     {
         $this->write();
     }

     /**
      * 前进
      *
      * @time 2020年06月20日
      * @param int $step
      * @return void
      */
     public function advance(int $step = 1)
     {
         $this->current += $step;

         $this->write();
     }

     /**
      * 结束
      *
      * @time 2020年06月20日
      * @return void
      */
     public function finished()
     {
         $this->write(true);

         $this->current = 1;
     }

     /**
      * 输出
      *
      * @time 2020年06月20日
      * @param bool $end
      * @return void
      */
     protected function write(bool $end = false)
     {
         $bar = $this->bar().($end ? '' : "\r");

         $this->output->write(sprintf('<info>%s</info>', $bar), false);
     }

     /**
      * 进度条
      *
      * @time 2020年06月20日
      * @return string
      */
     protected function bar(): string
     {
         $left = $this->total - $this->current;

         $empty = str_repeat(' ', intval($left * $this->average));

         $bar = str_repeat('>', intval($this->current * $this->average));

         $percent = ((int) (sprintf('%.2f', $this->current / $this->total) * 100)).'%';

         return $this->header . $bar . $empty . ' ' . $percent;
     }

     /**
      * 设置头信息
      *
      * @time 2020年06月20日
      * @param $header
      * @return $this
      */
     public function setHeader($header): ProgressBar
     {
         $this->header = $header;

         return $this;
     }
 }
