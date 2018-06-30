<?php

namespace Someline\Component\Category\Http\Controllers\Console;

use Someline\Http\Controllers\BaseController;

class SomelineCategoryControllerBase extends BaseController
{

    public function getCategoryList()
    {
        return view('console.categories.list');
    }

    public function getCategoryNew()
    {
        return view('console.categories.new');
    }

    public function getCategoryTest()
    {
        return view('console.categories.test');
    }
}