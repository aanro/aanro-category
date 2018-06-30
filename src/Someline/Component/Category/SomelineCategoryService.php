<?php

namespace Someline\Component\Category;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Someline\Models\Category\SomelineCategory;

class SomelineCategoryService
{

    /**
     * @return Collection
     */
    public static function getAllCategories()
    {
        $type = null;
        $someline_category_id = null;
        $level = 0;
        $all_children = true;
        $only_children = false;
        $order_by_sequence = false;
        $with_image = false;
        return self::getCategories($type, $someline_category_id, $level, $all_children, $order_by_sequence, $only_children, $with_image);
    }

    /**
     * @param $type
     * @param int $level
     * @param bool $with_image
     * @param bool $order_by_sequence
     * @return Collection
     */
    public static function getCategoriesForType($type, $level = 0, $with_image = false, $order_by_sequence = false)
    {
//        $type = null;
        $someline_category_id = null;
//        $level = 0;
        $all_children = false;
        $only_children = false;
//        $order_by_sequence = false;
//        $with_image = false;
        return self::getCategories($type, $someline_category_id, $level, $all_children, $order_by_sequence, $only_children, $with_image);
    }

    /**
     * @param $someline_category_id
     * @param int $level
     * @param bool $with_image
     * @param bool $order_by_sequence
     * @return Collection
     */
    public static function getCategoriesWithOnlyChildren($someline_category_id, $level = 0, $with_image = false, $order_by_sequence = false)
    {
        $type = null;
        $all_children = false;
        $only_children = true;
        return self::getCategories($type, $someline_category_id, $level, $all_children, $order_by_sequence, $only_children, $with_image);
    }


    /**
     * @param $type
     * @param $identifier
     * @param int $level
     * @param bool $with_image
     * @param bool $order_by_sequence
     * @return Collection
     */
    public static function getCategoriesFromIdentifierWithOnlyChildren($type, $identifier, $level = 0, $with_image = false, $order_by_sequence = false)
    {
        $somelineCategory = SomelineCategory::fromIdentifier($type, $identifier);
        if (!$somelineCategory) {
            return collect();
        }
        return self::getCategoriesWithOnlyChildren($somelineCategory->getSomelineCategoryId(), $level, $with_image, $order_by_sequence);
    }

    /**
     * @param $someline_category_id
     * @param int $level
     * @param bool $with_image
     * @param bool $order_by_sequence
     * @return Collection
     */
    public static function getCategoriesWithLevel($someline_category_id, $level = 0, $with_image = false, $order_by_sequence = false)
    {
        $type = null;
        $all_children = true;
        $only_children = false;
        return self::getCategories($type, $someline_category_id, $level, $all_children, $order_by_sequence, $only_children, $with_image);
    }

    /**
     * @param $type
     * @param $identifier
     * @param int $level
     * @param bool $with_image
     * @param bool $order_by_sequence
     * @return Collection
     */
    public static function getCategoriesFromIdentifierWithLevel($type, $identifier, $level = 0, $with_image = false, $order_by_sequence = false)
    {
        $somelineCategory = SomelineCategory::fromIdentifier($type, $identifier);
        if (!$somelineCategory) {
            return collect();
        }
        return self::getCategoriesWithLevel($somelineCategory->getSomelineCategoryId(), $level, $with_image, $order_by_sequence);
    }

    /**
     * @param $someline_category_id
     * @param bool $with_image
     * @param bool $order_by_sequence
     * @return Collection
     */
    public static function getCategoriesWithAllChildren($someline_category_id, $with_image = false, $order_by_sequence = false)
    {
        $type = null;
        $level = 0;
        $all_children = true;
        $only_children = false;
        return self::getCategories($type, $someline_category_id, $level, $all_children, $order_by_sequence, $only_children, $with_image);
    }

    /**
     * @param $type
     * @param $identifier
     * @param bool $with_image
     * @param bool $order_by_sequence
     * @return Collection
     */
    public static function getCategoriesFromIdentifierWithAllChildren($type, $identifier, $with_image = false, $order_by_sequence = false)
    {
        $somelineCategory = SomelineCategory::fromIdentifier($type, $identifier);
        if (!$somelineCategory) {
            return collect();
        }
        return self::getCategoriesWithAllChildren($somelineCategory->getSomelineCategoryId(), $with_image, $order_by_sequence);
    }

    /**
     * @param null $type
     * @param null $someline_category_id
     * @param int $level
     * @param bool $all_children
     * @param bool $order_by_sequence
     * @param bool $only_children
     * @param bool $with_image
     * @return Collection
     */
    public static function getCategories($type = null, $someline_category_id = null, $level = 0, $all_children = false,
                                         $order_by_sequence = false, $only_children = false, $with_image = false)
    {

        $level = $level < 0 ? 0 : $level;
        $only_children = !empty($someline_category_id) ? $only_children : false;
        $all_children = $level > 0 || $only_children ? false : $all_children;
        $with_children = $all_children || $only_children || $level > 0;
        $level = $all_children ? -1 : $level;

        $where = [];

        if ($someline_category_id) {
            $where['someline_category_id'] = $someline_category_id;
        }

        if ($type) {
            $where['type'] = $type;
        }

        /** @var Builder $categoryBuilder */
        $categoryBuilder = SomelineCategory::where($where);

        if (empty($someline_category_id)) {
            $categoryBuilder->whereNull('parent_category_id');
        }

        if ($with_image) {
            $categoryBuilder->with('someline_image');
        }

        if ($order_by_sequence) {
            $categoryBuilder->orderBy('sequence', 'desc');
        }

        /** @var Collection $somelineCategories */
        $somelineCategories = $categoryBuilder->get();

        $children_attribute_name = 'children';

        /** @var SomelineCategory $somelineCategory */
        foreach ($somelineCategories as $somelineCategory) {
            if ($with_image) {
                $somelineCategory->someline_image_url = $somelineCategory->someline_image_url;
            }
            if ($with_children) {
                self::getCategoryChildren($somelineCategory, $level, $children_attribute_name, $order_by_sequence);
            }
        }

        $result = collect();
        if ($somelineCategories->count() > 0) {
            if ($only_children) {
                $children = $somelineCategories->first()->{$children_attribute_name};
                if ($children) {
                    $result = $children;
                }
            } else {
                $result = $somelineCategories;
            }
        }

        return $result;
    }

    /**
     * @param SomelineCategory $somelineCategory
     * @param int $level
     * @param string $attribute_name
     * @param bool $order_by_sequence
     */
    public static function getCategoryChildren(SomelineCategory $somelineCategory, int $level, string $attribute_name = 'children', $order_by_sequence = false)
    {
        $children_categories = $somelineCategory->children_categories();
        if ($order_by_sequence) {
            $children_categories->orderBy('sequence', 'desc');
        }
        $children_categories = $children_categories->get();
//        unset($somelineCategory->children_categories);

        if ($children_categories->isNotEmpty()) {
            $somelineCategory->$attribute_name = $children_categories;
        }

        if ($level > 1 || $level < 0) {
            $nextLevel = $level - 1;
            foreach ($children_categories as $children_category) {
                $children_category->someline_image_url = $children_category->someline_image_url;
                self::getCategoryChildren($children_category, $nextLevel, $attribute_name, $order_by_sequence);
            }
        }
    }

    /**
     * @param SomelineCategory $category
     * @param string $attribute_name
     * @return Collection
     */
    public static function flattenCategoryChildren(SomelineCategory $category, string $attribute_name = 'children')
    {
        $collection = collect();
        self::combineChildrenToUnionCategories($category, $collection, $attribute_name);
        return $collection;
    }

    /**
     * @param SomelineCategory $category
     * @param Collection $union_categories
     * @param string $attribute_name
     */
    public static function combineChildrenToUnionCategories(SomelineCategory $category, Collection &$union_categories, $attribute_name = 'children')
    {
        $children = $category->$attribute_name;
        if (!empty($children)) {
            foreach ($children as $child) {
                self::combineChildrenToUnionCategories($child, $union_categories, $attribute_name);
            }
            $category->$attribute_name = null;
        }
        if (empty($category->$attribute_name)) {
            $union_categories->push($category);
        }
    }

    /**
     * @param SomelineCategory $category
     * @param string $attribute_name
     * @return static
     */
    public static function pluckAllCategoryIds(SomelineCategory $category, string $attribute_name = 'children')
    {
        $collection = self::flattenCategoryChildren($category, $attribute_name);
        return $collection->pluck('someline_category_id');
    }

}