<?php

namespace Someline\Component\Category\Transformers;

use Someline\Models\Category\SomelineCategory;
use Someline\Transformers\BaseTransformer;

/**
 * Class SomelineCategoryTransformer
 * @package namespace Someline\Component\Category\Transformers;
 */
class SomelineCategoryTransformerBase extends BaseTransformer
{

    /**
     * Transform the SomelineCategory entity
     * @param SomelineCategory $model
     *
     * @return array
     */
    public function transform(SomelineCategory $model)
    {
        return [
            'someline_category_id' => (int)$model->someline_category_id,

            'type' => $model->type,
            'category_name' => $model->category_name,
            'parent_category_id' => $model->parent_category_id,

            'created_at' => (string)$model->created_at,
            'updated_at' => (string)$model->updated_at
        ];
    }

}
