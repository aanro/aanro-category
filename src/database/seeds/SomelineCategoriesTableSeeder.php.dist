<?php

use Illuminate\Database\Seeder;
use Someline\Models\Category\SomelineCategory;
use Someline\Models\Foundation\User;

class SomelineCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('someline_categories')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            SomelineCategory::TYPE_ARTICLE => [
                '国内', '国际', '社会', '军事', '航空', '科技', '娱乐'
            ],
            SomelineCategory::TYPE_PRODUCT => [
                '裤子' => [
                    '长裤' => [
                        '牛仔裤' => [
                            '休闲裤' => [
                                '红色裤'
                            ]
                        ],
                        '西裤',
                    ],
                    '短裤',
                ],
                '上衣' => [
                    '衬衫', '西服', 'T恤', '毛衣', '大衣', '皮衣'
                ],
                '裙子' => [
                    '超短裙', '短裙', '中裙', '长裙'
                ],
                '手机' => [
                    'Android手机', '苹果手机', '老人手机'
                ],
                '电脑' => [
                    '台式电脑', '笔记本电脑', '平板电脑'
                ],
                '冰箱',
                '空调' => [
                    '挂壁式空调', '立柜式空调 ',
                ],
            ]
        ];

        foreach ($data as $type => $type_data) {
            array_walk($type_data, function ($value, $key, $type) {
                $this->createCategory($type, $value, $key);
            }, $type);
        }
    }

    public function createCategory($type, $value, $key, $category = false)
    {
        if (is_string($key) && is_array($value)) {
            $data = [
                'type' => $type,
                'category_name' => $key
            ];
            $category = $this->viaParentCategoryOrDirectly($data, $category);
            foreach ($value as $value_key => $value_value) {
                $this->createCategory($type, $value_value, $value_key, $category);
            }
            return;
        }
        if (is_integer($key) && is_string($value)) {
            $data = [
                'type' => $type,
                'category_name' => $value
            ];
            $category = $this->viaParentCategoryOrDirectly($data, $category);
            return;
        }
    }

    /**
     * @param $data
     * @param bool|SomelineCategory $category
     * @return SomelineCategory
     */
    public function viaParentCategoryOrDirectly($data, $category = false)
    {
        if ($category) {
            return $category->children_categories()->create($data);
        } else {
            return SomelineCategory::create($data);
        }
    }
}
