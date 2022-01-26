<?php

namespace catchAdmin\cms\tables;

use catch\CatchTable;
use catchAdmin\cms\tables\forms\Factory;
use catch\library\table\Actions;
use catch\library\table\HeaderItem;
use catch\library\table\Search;

class Tags extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('tags')
            ->header([
                HeaderItem::label('编号')->prop('id')->width(100),

                HeaderItem::label('名称')->prop('name'),

                HeaderItem::label('文章数量')->prop('articles_count'),

                HeaderItem::label('创建时间')->prop('created_at'),

                HeaderItem::label('操作')->actions([
                    Actions::update(),
                    Actions::delete()
                ])
            ])
            ->withBind()
            ->withSearch([
                Search::label('名称')->name('请输入标签名称')
            ])
            ->withApiRoute('cms/tags')
            ->withActions([
                Actions::create()
            ])
            ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('tags');
    }
}
