<?php

namespace Neoan3\Component\Report;

use Neoan3\Apps\Ops;
use Neoan3\Core\RouteException;
use Neoan3\Frame\BluaBlue;
use Neoan3\Model\Article\ArticleModel;
use Neoan3\Model\Report\ReportModel;
use Neoan3\Provider\Auth\AuthObject;
use Neoan3\Provider\Model\InitModel;

/**
 * Class ReportController
 * @package Neoan3\Component\Report
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */

class ReportController extends BluaBlue {
    /**
     * Route: Report
     */
    function init(): void
    {
        $this->renderer->includeElement('Report');
        $this->hook('main', 'report', []);
        $this->output();
    }

    /**
     * @throws \Exception
     */
    #[InitModel(ReportModel::class)]
    #[InitModel(ArticleModel::class)]
    function postReport($body)
    {
        $this->honeyPot($body['code']);
        $article = ArticleModel::find(['slug'=>$body['slug']]);
        if(!empty($article)){
            $body['article_id'] = $article[0]['id'];
            return ReportModel::create($body);
        }
        throw new RouteException('Bad entry',400);
    }
    #[InitModel(ReportModel::class)]
    #[InitModel(ArticleModel::class)]
    function getReport($slug, $params=[])
    {
        $article = ArticleModel::find(['slug'=>Ops::toKebabCase($slug)]);
        if(!empty($article)){
            $reports = ReportModel::find(['^delete_date', 'article_id'=>'$'.$article[0]['id']]);
            try{
                $user = $this->provider['auth']->validate();
                if($user->getPayload()['user_type'] !== 'admin'){
                    throw new \Exception('not admin');
                }
            } catch (\Exception $e){
                foreach ($reports as $i => $report){
                    unset($reports[$i]['reporter_email']);
                    unset($reports[$i]['claim']);
                }
            }
            return $reports;
        }
        return [];
    }

}
