<?php

namespace Neoan3\Component\Category;

use Neoan3\Frame\BluaBlue;
use Neoan3\Model\Category\CategoryModel;
use Neoan3\Provider\Auth\Authorization;
use Neoan3\Provider\Model\InitModel;

/**
 * Class CategoryController
 * @package Neoan3\Component\Category
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */

class CategoryController extends BluaBlue{

    /**
    * GET: api.v1/category
    * GET: api.v1/category/{id}
    * GET: api.v1/category?{query-string}
    * @return array
    */
    #[InitModel(CategoryModel::class)]
    function getCategory(): array
    {
        return CategoryModel::find(['^delete_date']);
    }

    /**
     * POST: api.v1/category
     * @param $body
     * @return array
     */
    #[Authorization('restrict')]
    #[InitModel(CategoryModel::class)]
    function postCategory(array $body): array
    {
        if($this->authObject->getPayload()['user_type'] === 'admin'){
            return CategoryModel::create($body);
        }
        return $body;
    }

    #[Authorization('restrict')]
    #[InitModel(CategoryModel::class)]
    function putCategory(array $body): array
    {
        if($this->authObject->getPayload()['user_type'] === 'admin'){
            return CategoryModel::update($body);
        }
        return $body;
    }
}