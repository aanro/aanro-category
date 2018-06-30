<?php

namespace Someline\Component\Category\Repositories\Eloquent;

use Someline\Repositories\Eloquent\BaseRepository;
use Someline\Repositories\Criteria\RequestCriteria;
use Someline\Repositories\Interfaces\SomelineCategoryRepository;
use Someline\Models\Category\SomelineCategory;
use Someline\Validators\SomelineCategoryValidator;
use Someline\Component\Category\Presenters\SomelineCategoryPresenter;

/**
 * Class SomelineCategoryRepositoryEloquentBase
 * @package namespace Someline\Component\Category\Repositories\Eloquent;
 */
class SomelineCategoryRepositoryEloquentBase extends BaseRepository implements SomelineCategoryRepository
{

    protected $fieldSearchable = [
        'title' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SomelineCategory::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return SomelineCategoryValidator::class;
    }


    /**
     * Specify Presenter class name
     *
     * @return mixed
     */
    public function presenter()
    {

        return SomelineCategoryPresenter::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
