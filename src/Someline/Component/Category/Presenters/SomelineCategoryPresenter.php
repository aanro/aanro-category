<?php

namespace Someline\Component\Category\Presenters;

use Someline\Transformers\SomelineCategoryTransformer;
use Someline\Presenters\BasePresenter;

/**
 * Class SomelineCategoryPresenter
 *
 * @package namespace Someline\Component\Category\Presenters;
 */
class SomelineCategoryPresenter extends BasePresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new SomelineCategoryTransformer();
    }
}
