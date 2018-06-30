<?php

namespace Someline\Component\Category\Api\Controllers\Traits;

use Dingo\Api\Exception\ResourceException;
use Someline\Models\Article\SomelineArticle;
use Someline\Models\Category\SomelineCategory;

trait SomelineCategoriesControllerTrait
{

    public function handleSomelineCategory(array &$data)
    {
        if (!isset($data['someline_category_id'])) {
            throw new ResourceException('分类不存在');
        }
        $someline_category = SomelineCategory::find($data['someline_category_id']);
        if (is_null($someline_category)) {
            throw new ResourceException('分类不存在');
        }
        return $someline_category;
    }

    protected function validateSomelineCategories($data)
    {
        if (!isset($data['someline_category_ids']) && !is_array($data['someline_category_ids'])) {
            throw new ResourceException('请选择分类');
        }
    }

    protected function updateSomelineCategories($model, $data)
    {
        $model->someline_categories()->sync($data['someline_category_ids']);
        return $model;
    }

}