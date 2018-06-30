<?php

namespace Someline\Component\Category\Transformers\Traits;

use Someline\Models\BaseModel;
use Someline\Transformers\SomelineCategoryTransformer;

trait SomelineIncludeSingleCategory
{

    public function includeSomelineCategory(BaseModel $model)
    {
        $someline_category = $model->someline_category;
        if ($someline_category) {
            return $this->item($someline_category, new SomelineCategoryTransformer());
        }
        return null;
    }

}