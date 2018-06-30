<?php

namespace Someline\Component\Category\Models\Traits;

use Someline\Models\Category\SomelineCategory;

trait SomelineBelongsToCategoryTrait
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function someline_category()
    {
        return $this->belongsTo(SomelineCategory::class, 'someline_category_id', 'someline_category_id');
    }

    /**
     * @return SomelineCategory|null
     */
    public function getSomelineCategory()
    {
        return $this->someline_category;
    }

    /**
     * @return int
     */
    public function getSomelineCategoryId()
    {
        return (int)$this->someline_category_id;
    }

}