<?php

namespace Someline\Component\Category\Api\Controllers;

use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Prettus\Validator\Contracts\ValidatorInterface;
use Someline\Api\Controllers\BaseController;
use Someline\Component\Category\SomelineCategoryService;
use Someline\Models\Category\SomelineCategory;
use Someline\Presenters\BasicPresenter;
use Someline\Repositories\Interfaces\SomelineCategoryRepository;
use Someline\Validators\SomelineCategoryValidator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SomelineCategoriesControllerBase extends BaseController
{

    /**
     * @var SomelineCategoryRepository
     */
    protected $repository;

    /**
     * @var SomelineCategoryValidator
     */
    protected $validator;

    public function __construct(SomelineCategoryRepository $repository, SomelineCategoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $someline_category_id = $request->get('someline_category_id');
        $level = (int)$request->get('level', 0);
        $all_children = (bool)$request->get('all_children', false);
        $order_by_sequence = (bool)$request->get('order_by_sequence', false);
        $only_children = (bool)$request->get('only_children', false);
        $with_image = (bool)$request->get('with_image', true);

        $result = SomelineCategoryService::getCategories($type,
            $someline_category_id, $level, $all_children,
            $order_by_sequence, $only_children, $with_image);

        $result = $result->toArray();

        return (new BasicPresenter())->present($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);

        $somelineCategory = $this->repository->skipPresenter(true)->create($data);

        $somelineCategory->someline_image_url = $somelineCategory->someline_image_url;

        // throw exception if store failed
//        throw new StoreResourceFailedException('Failed to store.');

        // A. return 201 created
//        return $this->response->created(null);

        // B. return data
        return (new BasicPresenter())->present($somelineCategory->toArray());

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $data = $request->all();

        $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);

        $somelineCategory = $this->repository->skipPresenter(true)->update($data, $id);

        $somelineCategory->someline_image_url = $somelineCategory->someline_image_url;
        // throw exception if update failed
//        throw new UpdateResourceFailedException('Failed to update.');

        // Updated, return 204 No Content
        return (new BasicPresenter())->present($somelineCategory->toArray());

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if ($deleted) {
            // Deleted, return 204 No Content
            return $this->response->noContent();
        } else {
            // Failed, throw exception
            throw new DeleteResourceFailedException('Failed to delete.');
        }
    }

    public function updateCategories(Request $request)
    {
        $categories = $request->get('someline_categories');
        if (!is_array($categories)) {
            throw new ResourceException('数据格式有误');
        }
        foreach ($categories as $category_data) {
            $this->updateCategory($category_data, false, true);
        }
    }

    /**
     * @param $category_data
     * @param SomelineCategory|boolean $parent_category
     * @param bool $is_parent
     */
    public function updateCategory($category_data, $parent_category = false, $is_parent = false)
    {
        if (!array_key_exists('type', $category_data)
            || !array_key_exists('category_name', $category_data)
        ) {
            throw new ResourceException("分类的数据格式有误");
        }
        $someline_category_id = $category_data['someline_category_id'] ?? 0;
        if ($someline_category_id) {
            $category = SomelineCategory::find($someline_category_id);
            if (is_null($category)) {
                throw new ResourceNotFoundException('分类不存在');
            }
        } else {
            $category = new SomelineCategory();
        }
        $category->category_name = $category_data['category_name'];
        $category->type = $category_data['type'];

        if ($parent_category) {
            $category->parent_category_id = $parent_category->getSomelineCategoryId();
        }

        if ($is_parent) {
            $category->parent_category_id = null;
        }

        if ($category->isDirty()) {
            $category->save();
        }
        foreach ($category_data['children'] ?? [] as $children_category_data) {
            $this->updateCategory($children_category_data, $category);
        }
    }
}
