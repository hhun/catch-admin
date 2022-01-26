<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catch\support\form;

use catch\CatchAuth;
use catch\exceptions\FailedException;
use catch\support\form\actions\Destroy;
use catch\support\form\actions\Store;
use catch\support\form\actions\Update;
use catch\support\form\fields\Area;
use catch\support\form\fields\Avatar;
use catch\support\form\fields\Boolean;
use catch\support\form\fields\Cascader;
use catch\support\form\fields\Date;
use catch\support\form\fields\DateTime;
use catch\support\form\fields\Editor;
use catch\support\form\fields\Email;
use catch\support\form\fields\FileUpload;
use catch\support\form\fields\Hidden;
use catch\support\form\fields\ImageUpload;
use catch\support\form\fields\Number;
use catch\support\form\fields\Password;
use catch\support\form\fields\Radio;
use catch\support\form\fields\Select;
use catch\support\form\fields\SelectMultiple;
use catch\support\form\fields\Text;
use catch\support\form\fields\Textarea;
use catch\support\form\fields\Tree;
use catch\support\form\fields\Url;
use Closure;
use catch\support\form\fields\traits\RelationsTrait;
use think\Model;

/**
 * @method static Area area(string $name, string $title, array $props = []);
 * @method static Avatar avatar(string $name, string $title, string $action, bool $auth = true);
 * @method static Boolean boolean(string $name, string $title);
 * @method static Date date(string $name, string $title);
 * @method static DateTime datetime(string $name, string $title);
 * @method static Editor editor(string $name, string $title);
 * @method static Email email(string $name, string $title);
 * @method static FileUpload fileUpload(string $name, string $title, string $action, bool $auth = true);
 * @method static ImageUpload imageUpload(string $name, string $title, string $action, bool $auth = true);
 * @method static Number number(string $name, string $title, $value = '');
 * @method static Password password(string $name, string $title);
 * @method static Select select(string $name, string $title);
 * @method static SelectMultiple selectMultiple(string $name, string $title);
 * @method static Text text(string $name, string $title);
 * @method static Textarea textarea(string $name, string $title);
 * @method static Tree tree(string $name, string $title);
 * @method static Url url(string $name, string $title);
 * @method static Cascader cascader(string $name, string $title, $options = null);
 * @method static Hidden hidden(string $name, $value = '');
 * @method static Radio radio(string $name, $value = '');
 * @time 2021年08月11日
 */
class CatchForm implements \ArrayAccess, \Iterator
{
    use RelationsTrait;

    /**
     * @var Closure
     */
    protected Closure $save;

    /**
     * @var Closure
     */
    protected Closure $create;

    /**
     * @var Closure
     */
    protected Closure $update;

    /**
     * @var Closure
     */
    protected Closure $destroy;

    /**
     * query condition
     *
     * @var array
     */
    protected array $condition = [];

    /**
     * will store in database
     *
     * @var array
     */
    protected array $data = [];

    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var null|Closure
     */
    protected ?Closure $beforeSave;

    /**
     * @var null|Closure
     */
    protected ?Closure $beforeUpdate;

    /**
     * @var null|Closure
     */
    protected ?Closure $beforeDestroy;

    /**
     * @var Closure
     */
    protected Closure $afterSave;

    /**
     * @var Closure
     */
    protected Closure $afterUpdate;

    /**
     * @var Closure
     */
    protected Closure $afterDestroy;

    /**
     * @var bool
     */
    protected bool $autoWriteCreatorId = true;

    /**
     * @var array
     */
    protected array $fields = [];

    /**
     * create form
     *
     * @time 2021年08月11日
     * @param Closure $create
     * @param Model $model
     * @return CatchForm
     */
    public function creating(Closure $create, Model $model): CatchForm
    {
        $this->create = $create;

        $this->model = $model;

        return $this;
    }

    /**
     * when
     *
     * @time 2021年09月13日
     * @param $condition
     * @param Closure $callback
     * @return $this
     */
    public function when($condition, Closure $callback): CatchForm
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }

    /**
     *
     * @time 2021年08月11日
     * @param Closure $save
     * @return $this
     */
    public function saving(Closure $save): CatchForm
    {
        $this->save = $save;

        return $this;
    }

    /**
     * update
     *
     * @time 2021年08月11日
     * @param Closure $update
     * @return $this
     */
    public function updating(Closure $update): CatchForm
    {
        $this->update = $update;

        return $this;
    }

    /**
     * destroy it
     *
     * @time 2021年08月11日
     * @param Closure $destroy
     * @return $this
     */
    public function destroying(Closure $destroy): CatchForm
    {
        $this->destroy = $destroy;

        return $this;
    }

    /**
     * prepare
     *
     * @time 2021年08月11日
     * @param Closure $closure
     * @return $this
     */
    public function prepare(Closure $closure): self
    {
        $closure($this);

        return $this;
    }

    /**
     * before save
     *
     * @time 2021年08月11日
     * @param Closure $closure
     * @return CatchForm
     */
    public function beforeSave(Closure $closure): self
    {
        $this->beforeSave = $closure;

        return $this;
    }

    /**
     * before update
     *
     * @time 2021年08月11日
     * @param Closure $closure
     * @return $this
     */
    public function beforeUpdate(Closure $closure): self
    {
        $this->beforeUpdate = $closure;

        return $this;
    }


    /**
     * before destroy
     *
     * @time 2021年08月11日
     * @param Closure $closure
     * @return $this
     */
    public function beforeDestroy(Closure $closure): self
    {
        $this->beforeDestroy = $closure;

        return $this;
    }

    /**
     * store
     *
     * @time 2021年08月11日
     * @return false|mixed
     */
    public function store()
    {
        if ($this->beforeSave instanceof Closure) {
            call_user_func($this->beforeSave, $this);
        }

        $res = (new Store($this))->run();

        if ($this->afterSave instanceof Closure) {
            call_user_func($this->afterSave, $this);
        }

        return $res;
    }

    /**
     * update
     *
     * @time 2021年08月11日
     * @return false|mixed
     */
    public function update()
    {
        if ($this->beforeUpdate instanceof Closure) {
            call_user_func($this->beforeUpdate, $this);
        }

        $res = (new Update($this))->run();

        if ($res && $this->afterUpdate instanceof Closure) {
            call_user_func($this->afterUpdate, $this);
        }

        return $res;
    }

    /**
     * destroy
     *
     * @time 2021年08月11日
     * @return false|mixed
     */
    public function destroy()
    {
        if ($this->beforeDestroy instanceof Closure) {
            call_user_func($this->beforeDestroy, $this);
        }

        $res =  (new Destroy($this))->run();

        if ($res && $this->afterDestroy instanceof  Closure) {
            call_user_func($this->afterDestroy, $this);
        }

        return $res;
    }

    /**
     * after save
     *
     * @time 2021年08月26日
     * @param Closure $closure
     * @return $this
     */
    public function afterSave(Closure $closure): CatchForm
    {
        $this->afterSave = $closure;

        return $this;
    }

    /**
     * after update
     *
     * @time 2021年08月26日
     * @param Closure $closure
     * @return $this
     */
    public function afterUpdate(Closure $closure): CatchForm
    {
        $this->afterUpdate = $closure;

        return $this;
    }

    /**
     * after destroy
     *
     * @time 2021年08月26日
     * @param Closure $closure
     * @return $this
     */
    public function afterDestroy(Closure $closure): CatchForm
    {
        $this->afterDestroy = $closure;

        return $this;
    }

    /**
     * fields
     *
     * @time 2021年08月11日
     * @return array
     */
    public function create(): array
    {
        $fields = [];

        $created = call_user_func($this->create);

        foreach ($created as $field) {
            $field = $this->parseRelate($field);

            $fields[] = $field();
        }

        return $fields;
    }

    /**
     * set condition
     *
     * @time 2021年08月11日
     * @param array $condition
     * @return $this
     */
    public function setCondition(array $condition): CatchForm
    {
        $this->condition = $condition;

        return $this;
    }


    /**
     * set data
     *
     * @time 2021年08月11日
     * @param array $data
     * @return $this
     */
    public function setData(array $data): CatchForm
    {
        $this->data = $data;

        return $this;
    }

    /**
     * get condition
     *
     * @time 2021年08月19日
     * @return array
     */
    public function getCondition(): array
    {
        return $this->condition;
    }

    /**
     * get data
     *
     * @time 2021年08月19日
     * @return array
     */
    public function getData(): array
    {
        // auto write creator_id
        if ($this->autoWriteCreatorId && in_array('creator_id', $this->getModel()->getFillable())) {
            $this->data['creator_id'] = CatchAuth::id();
        }

        return $this->data;
    }

    /**
     * close write creator id
     *
     * @time 2021年09月22日
     * @return $this
     */
    public function dontWriteCreatorId(): CatchForm
    {
        $this->autoWriteCreatorId = false;

        return $this;
    }

    /**
     * get model of form
     *
     * @time 2021年08月11日
     * @return Model
     */
    public function getModel(): Model
    {
        if (is_string($this->model)) {
            $this->model = app()->make($this->model);
        }

        return $this->model;
    }

    /**
     * col
     *
     * @time 2021年08月17日
     * @param int $col
     * @param mixed $fields
     * @return array
     */
    public function col(int $col, Closure $fields): array
    {
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $field->col($col);
            }
        } else {
            $fields->col($col);
        }

        return $fields;
    }

    /*
     *
     * @time 2021年08月19日
     * @return Closure
     */
    public function getSaving(): Closure
    {
        return $this->save;
    }

    /**
     *
     * @time 2021年08月19日
     * @return Closure
     */
    public function getUpdating(): Closure
    {
        return $this->update;
    }

    /**
     *
     * @time 2021年08月19日
     * @return Closure
     */
    public function getDestroying(): Closure
    {
        return $this->destroy;
    }

    /**
     *
     * @time 2021年08月26日
     * @return Closure|null
     */
    public function getBeforeSave(): ?Closure
    {
        return $this->beforeSave;
    }

    /**
     *
     * @time 2021年08月26日
     * @return Closure|null
     */
    public function getBeforeUpdate(): ?Closure
    {
        return $this->beforeUpdate;
    }

    /**
     *
     * @time 2021年08月26日
     * @return Closure|null
     */
    public function getBeforeDestroy(): ?Closure
    {
        return $this->beforeDestroy;
    }

    /**
     * get primary key value
     *
     * @time 2021年09月15日
     * @return mixed
     */
    public function getPrimaryKeyValue()
    {
        return $this->condition[$this->getModel()->getPk()];
    }

    /**
     * invoke
     *
     * @time 2021年09月23日
     * @return array
     */
    public function __invoke(): array
    {
        return $this->data;
    }

    /**
     *
     * @time 2021年08月11日
     * @param $name
     * @param $arguments
     * @return false|mixed
     */
    public function __call($name, $arguments)
    {
        $field = call_user_func_array([__NAMESPACE__ . '\\fields\\' . ucfirst($name), 'make'], $arguments);

        $this->fields[] = $field;

        return $field;
    }

    /**
     * get value
     *
     * @time 2021年09月15日
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new FailedException(sprintf('{%s} Not Exist', $key));
    }

    /**
     * set value
     *
     * @time 2021年09月15日
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * isset key in data
     *
     * @time 2021年09月15日
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * unset key from data
     *
     * @time 2021年09月15日
     * @param $key
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;

        return $this;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function rewind()
    {
        return reset($this->data);
    }
}
