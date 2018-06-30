<?php

namespace Someline\Component\Category\Models\Traits;

use Someline\Models\Category\SomelineCategory;

trait SomelineMorphToManyCategoryTrait
{

    /**
     * @return mixed
     */
    public function someline_categories()
    {
        return $this->morphToMany(SomelineCategory::class, 'categorizable', 'someline_categorizables', null, 'someline_category_id');
    }

    /**
     * @return mixed
     */
    public function getSomelineCategories()
    {
        return $this->someline_categories;
    }

    /**
     * @param SomelineCategory $somelineCategory
     * @return mixed
     */
    public function setSomelineCategory(SomelineCategory $somelineCategory)
    {
        $isExists = $this->someline_categories()->where('someline_categorizables.someline_category_id', $somelineCategory->getSomelineCategoryId())->first();
        if ($isExists) {
            return false;
        }
        return $this->someline_categories()->save($somelineCategory);
    }

}
