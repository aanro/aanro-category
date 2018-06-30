<?php

namespace Someline\Component\Category\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Someline\Image\Models\Traits\SomelineHasOneImageTrait;
use Someline\Models\BaseModel;
use Someline\Models\Category\SomelineCategory;
use Someline\Models\Foundation\User;
use Someline\Models\Image\SomelineImage;
use Someline\Models\Traits\RelationUserTrait;

/**
 * Class SomelineCategoryBase
 * @package Someline\Component\Category\Models
 * @property Collection $children_categories
 * @property $this $parent_category
 * @property integer someline_category_id
 * @property integer $user_id
 * @property string $type
 * @property string $category_name
 * @property integer $parent_category_id
 */
class SomelineCategoryBase extends BaseModel implements Transformable
{
    use TransformableTrait;
    use RelationUserTrait;
    use SoftDeletes;
    use SomelineHasOneImageTrait;

    const MORPH_NAME = 'SomelineCategory';

    const TYPE_ABOUT = 'About';
    const TYPE_ARTICLE = 'Article';
    const TYPE_PRODUCT = 'Product';
    const TYPE_CASE = 'Case';
    const TYPE_ALBUM = 'Album';

    public static $polymorphicType = 'SomelineCategory';

    protected $table = 'someline_categories';

    protected $primaryKey = 'someline_category_id';

    protected $fillable = [
        'user_id',
        'type',
        'identifier',
        'category_name',
        'category_ename',
        'parent_category_id',
        'someline_image_id',
        'sequence',
        'data',
    ];

    // Fields to be converted to Carbon object automatically
    protected $dates = [];

    protected $hidden = ['someline_image'];

    public static function getTypeTexts()
    {
        return [
            self::TYPE_ABOUT => '关于',
            self::TYPE_ARTICLE => '文章',
            self::TYPE_PRODUCT => '产品',
            self::TYPE_CASE => '案例',
            self::TYPE_ALBUM => '相册',
        ];
    }

    public static function getTypeText($type)
    {
        $typeTexts = SomelineCategory::getTypeTexts();
        return $typeTexts[$type];
    }

    /**
     * @param $type
     * @param $identifier
     * @return SomelineCategory|null
     */
    public static function fromIdentifier($type, $identifier)
    {
        if (empty($type) || empty($identifier)) {
            return null;
        }
        return SomelineCategory::where([
            'type' => $type,
            'identifier' => $identifier,
        ])->first();
    }

    public function onCreating()
    {
        parent::onCreating();
        if ($this->parent_category_id) {
            $this->type = $this->parent_category->type;
        }
    }

    public function categorizable()
    {
        return $this->morphTo();
    }

    public function getSomelineCategoryId()
    {
        return (integer)$this->someline_category_id;
    }

    public function getCategoryName()
    {
        return $this->category_name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isType($type)
    {
        return $this->type == $type;
    }

    public function getParentCategoryId()
    {
        return (integer)$this->parent_category_id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children_categories()
    {
        return $this->hasMany(SomelineCategory::class, 'parent_category_id', 'someline_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent_category()
    {
        return $this->belongsTo(SomelineCategory::class, 'parent_category_id', 'someline_category_id');
    }

    protected function getDefaultSomelineImageUrl()
    {
        return null;
    }

}
