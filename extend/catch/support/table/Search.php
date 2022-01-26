<?php
namespace catch\support\table;

use catch\support\form\Fields\Area;
use catch\support\form\Fields\Avatar;
use catch\support\form\Fields\Boolean;
use catch\support\form\Fields\Cascader;
use catch\support\form\Fields\Date;
use catch\support\form\Fields\DateTime;
use catch\support\form\Fields\Editor;
use catch\support\form\Fields\Email;
use catch\support\form\Fields\FileUpload;
use catch\support\form\Fields\Hidden;
use catch\support\form\Fields\ImageUpload;
use catch\support\form\Fields\Number;
use catch\support\form\Fields\Password;
use catch\support\form\Fields\Radio;
use catch\support\form\Fields\Select;
use catch\support\form\Fields\SelectMultiple;
use catch\support\form\Fields\Text;
use catch\support\form\Fields\Textarea;
use catch\support\form\Fields\Tree;
use catch\support\form\Fields\Url;
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
 * @method static Select select(string $name, string $title, $option);
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

class Search
{
    /**
     * use like search
     *
     * @time 2021年08月26日
     * @param string $name
     * @param $title
     * @return Text
     */
    public static function like(string $name, $title): Text
    {
        return Text::make($name . '@like', $title);
    }

    /**
     * %like
     *
     * @time 2021年08月26日
     * @param string $name
     * @param $title
     * @return Text
     */
    public static function startLike(string $name, $title): Text
    {
        return Text::make($name . '@startLike', $title);
    }

    /**
     * like%
     *
     * @time 2021年08月26日
     * @param string $name
     * @param $title
     * @return Text
     */
    public static function endLike(string $name, $title): Text
    {
        return Text::make($name . '@endLike', $title);
    }

    /**
     *
     * @time 2021年09月13日
     * @param string $name
     * @param string $title
     * @return DateTime
     */
    public static function startAt(string $name = 'start_at', string $title = '开始时间'): DateTime
    {
        return DateTime::make($name . '@gt', $title);
    }

    /**
     *
     * @time 2021年09月13日
     * @param string $name
     * @param string $title
     * @return DateTime
     */
    public static function endAt(string $name = 'end_at', string $title = '结束时间'): DateTime
    {
        return DateTime::make($name . '@lt', $title);
    }

    /**
     * 最小值
     *
     * @time 2021年09月13日
     * @param string $name
     * @param $title
     * @return Number
     */
    public static function min(string $name, $title): Number
    {
        return Number::make($name . '@gt', $title);
    }

    /**
     * 最大值
     *
     * @time 2021年09月13日
     * @param string $name
     * @param $title
     * @return Number
     */
    public static function max(string $name, $title): Number
    {
        return Number::make($name . '@lt', $title);
    }

    /**
     *
     * @time 2021年08月11日
     * @param $name
     * @param $arguments
     * @return false|mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(['\\Catcher\\Support\\Form\\Fields\\' . ucfirst($name), 'make'], $arguments);
    }
}
