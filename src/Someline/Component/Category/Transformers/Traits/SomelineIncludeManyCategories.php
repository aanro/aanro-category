<?php
namespace Someline\Component\Category\Transformers\Traits;

use Someline\Models\BaseModel;
use Someline\Transformers\SomelineCategoryTransformer;

trait SomelineIncludeManyCategories
{

    public function includeCategories(BaseModel $model)
    {
        $someline_categories = $model->someline_categories;
        if ($someline_categories->isNotEmpty()) {
            return $this->collection($someline_categories, new SomelineCategoryTransformer());
        }
        return null;
    }

}