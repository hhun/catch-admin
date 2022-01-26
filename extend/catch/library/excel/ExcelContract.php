<?php

declare(strict_types=1);

namespace catch\library\excel;

interface ExcelContract
{
    public function headers(): array;

    public function sheets();
}
