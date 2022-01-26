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

namespace catch\traits\db;

trait WithTrait
{
    /**
     *
     * @time 2021年05月28日
     * @return mixed
     */
    protected function autoWithRelation()
    {
        if (property_exists($this, 'globalScope')) {
            array_push($this->globalScope, 'withRelation');
        }
        $this->scope('scopeWith');
        if (property_exists($this, 'with')) {
            return $this->with($this->with);
        }

        return $this;
    }

    /**
     *
     * @time 2021年05月28日
     * @param $query
     * @return void
     */
    public function scopeWithRelation($query)
    {
        if (property_exists($this, 'with') && ! empty($this->with)) {
            $query->with($this->with);
        }
    }

    /**
     *
     * @time 2021年05月28日
     * @param string $withRelation
     * @return $this
     */
    public function withoutRelation(string $withRelation)
    {
        $withes = $this->getOptions('with');

        foreach ($withes as $k => $item) {
            if ($item === $withRelation) {
                unset($withes[$k]);
                break;
            }
        }

        return $this->setOption('with', $withes);
    }

    /**
     *
     * @time 2021年05月28日
     * @param string $withRelation
     * @return $this
     */
    public function withOnlyRelation(string $withRelation)
    {
        return $this->with($withRelation);
    }
}
