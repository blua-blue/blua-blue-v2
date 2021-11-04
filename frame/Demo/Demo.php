<?php
/**
 * Created by PhpStorm.
 * User: sroehrl
 * Date: 2/4/2019
 * Time: 1:36 PM
 */

namespace Neoan3\Frame;

use Exception;
use Neoan3\Core\Serve;
use Neoan3\Provider\Attributes\UseAttributes;
use Neoan3\Provider\Auth\Auth;
use Neoan3\Provider\Auth\AuthObject;
use Neoan3\Provider\Auth\JwtWrapper;
use Neoan3\Provider\MySql\Database;
use Neoan3\Provider\MySql\DatabaseWrapper;

/**
 * Class Demo
 * @package Neoan3\Frame
 * @property Auth $auth
 */
class Demo extends Serve
{

    /**
     * Name your credentials
     * @var string
     */
    private string $dbCredentials = 'neoan3_db';

    public Auth $Auth;

    public ?AuthObject $authObject;

    /**
     * Demo constructor.
     * @param Database|null $db
     * @param Auth|null $auth
     */
    function __construct()
    {
        parent::__construct();


        /*
         * PHP8 Attributes
         * */
        if(PHP_MAJOR_VERSION >= 8){
            $phpAttributes = new UseAttributes();
            $phpAttributes->hookAttributes($this->provider);
        }

        $this->hook('header', 'nav');
    }


    /**
     * @return array
     */
    function constants(): array
    {
        return [
            'base' => [base],
            'title' => ['blua.blue API documentation'],
            'link' => [
                [
                    'sizes' => '32x32',
                    'type' => 'image/png',
                    'rel' => 'icon',
                    'href' => 'asset/neoan-favicon.png'
                ]
            ],
            'stylesheet' => [
                'https://unpkg.com/@stoplight/elements/styles.min.css',
                base . '/frame/BluaBlue/style.css',
                base . '/frame/BluaBlue/base.css',
            ],
        ];
    }
}
