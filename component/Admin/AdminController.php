<?php

namespace Neoan3\Component\Admin;

use Neoan3\Core\RouteException;
use Neoan3\Frame\BluaBlue;
use Neoan3\Model\Article\ArticleModel;
use Neoan3\Model\Message\MessageModel;
use Neoan3\Model\User\UserModel;
use Neoan3\Provider\Auth\Authorization;
use Neoan3\Provider\Model\InitModel;

/**
 * Class AdminController
 * @package Neoan3\Component\Admin
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */
class AdminController extends BluaBlue
{
    /**
     * Route: Admin
     */
    function init(): void
    {
        $this->renderer->includeElement('Admin');
        $this->hook('main', 'admin', []);
        $this->output();
    }

    /**
     * @throws RouteException
     */
    #[Authorization('restrict')]
    function getAdmin($entity, $id = null, $params = [])
    {
        $this->isAdmin();
        $orderMatch = ['User' => 'insert_date', 'Message' => 'sent_from', 'Category' => 'name'];
        $order = $orderMatch[$entity];
        $params = array_merge($params, ['orderBy' => [$order, 'asc']]);
        $class = "Neoan3\Model\\" . $entity . "\\" . $entity . "Model";
        $model = $this->loadModel($class);
        if ($id) {
            return $model::get($id);
        } else {
            return $model::find([], $params);
        }

    }

    /**
     * @throws RouteException
     */
    #[Authorization('restrict')]
    #[InitModel(ArticleModel::class)]
    #[InitModel(UserModel::class)]
    #[InitModel(MessageModel::class)]
    function deleteAdmin($entity, $params = [])
    {
        $this->isAdmin();
        if ($entity === 'User') {
            // delete webhooks
            $this->provider['db']->smart('>DELETE FROM webhook WHERE user_id = UNHEX({{id}})', $params);
            // delete images
            $this->provider['db']->smart('>DELETE FROM image WHERE inserter_user_id = UNHEX({{id}})', $params);
            // delete articles
            $allArticles = ArticleModel::find(['author_user_id' => '$' . $params['id']]);
            foreach ($allArticles as $article) {
                ArticleModel::delete($article['id'], true);
            }
            // delete user
            UserModel::delete($params['id'], true);

        } elseif ($entity === 'Message'){
            MessageModel::delete($params['id']);
        }
        return ['response' => 'hidden'];
    }

    /**
     * @throws RouteException
     */
    private function isAdmin()
    {
        if ($this->authObject->getPayload()['user_type'] !== 'admin') {
            throw new RouteException('Admin permissions required', 401);
        }
    }

}
