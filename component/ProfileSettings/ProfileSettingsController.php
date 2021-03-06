<?php

namespace Neoan3\Component\ProfileSettings;

use Neoan3\Apps\Ops;
use Neoan3\Frame\BluaBlue;
use Neoan3\Model\Api\ApiModel;
use Neoan3\Model\Image\ImageModel;
use Neoan3\Provider\Auth\Authorization;
use Neoan3\Provider\Model\InitModel;

/**
 * Class ProfileSettingsController
 * @package Neoan3\Component\ProfileSettings
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */

class ProfileSettingsController extends BluaBlue {
    /**
     * Route: ProfileSettings
     */
    function init(): void
    {
        $this->renderer->includeElement('ProfileSettings');
        $this->hook('main', 'profileSettings', []);
        $this->output();
    }
    #[Authorization('restrict',['all'])]
    #[InitModel(ImageModel::class)]
    #[InitModel(ApiModel::class)]
    function getProfileSettings()
    {
        $allImages = ImageModel::find(['^delete_date','inserter_user_id'=>'$'.$this->authObject->getUserId()]);
        $totalSize = 0;
        foreach ($allImages as $image){
            if(preg_match('/upload\/'. $this->authObject->getUserId() . '/',$image['path'])){
                $totalSize += filesize(path .preg_replace('/'.str_replace('/','\/',base).'/','',$image['path']));
            }
        }
        $apiKeys = ApiModel::find(['^delete_date','user_id'=>'$'.$this->authObject->getUserId()]);
        return [
            'storage'=>[
                'images'=> ['count'=> count($allImages),'storage'=>$totalSize]
            ],
            'api'=> $apiKeys
        ];
    }
    #[Authorization('restrict',['all'])]
    #[InitModel(ApiModel::class)]
    function postProfileSettings($what, $body): array
    {

        if($what === 'Api'){
            $apiKey = Ops::randomString(36);
            $body['user_id'] = $this->authObject->getUserId();
            $body['api_key'] = Ops::encrypt($apiKey,$apiKey);
            $inserted = ApiModel::create($body);
            $inserted['api_key'] = $apiKey;
            return $inserted;
        }
        return [];
    }
    #[Authorization('restrict',['all'])]
    #[InitModel(ApiModel::class)]
    function deleteProfileSettings($what, $id): array
    {

        if($what === 'Api'){
            if(!empty(ApiModel::find(['user_id'=>'$'.$this->authObject->getUserId(),'id'=>'$'.$id]))) {
                return ApiModel::delete($id, true);
            }
        }
        return [];
    }

}
