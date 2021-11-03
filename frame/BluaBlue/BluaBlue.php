<?php
/**
 * Created by neoan3-cli.
 */

namespace Neoan3\Frame;

use Exception;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use Neoan3\Apps\Ops;
use Neoan3\Apps\TemplateFunctions;
use Neoan3\Component\Auth\AuthController;
use Neoan3\Core\Event;
use Neoan3\Core\RouteException;
use Neoan3\Core\Serve;
use Neoan3\Provider\Attributes\UseAttributes;
use Neoan3\Provider\Auth\SessionWrapper;
use Neoan3\Provider\MySql\Database;
use Neoan3\Provider\MySql\DatabaseWrapper;
use Neoan3\Provider\Auth\JwtWrapper;
use Neoan3\Provider\Auth\Auth;
use Neoan3\Provider\Auth\AuthObject;

require_once 'VueRenderer.php';

/**
 * Class bluaBlue
 * @package Neoan3\Frame
 */
class BluaBlue extends Serve
{
    /**
     * Db credential name
     * @var string
     */
    private string $dbCredentials = 'blua_db';
    private string $authSecret = 'jwt_secret';
    /**
     * @var Database|DatabaseWrapper
     */
    public Database|DatabaseWrapper $db;

    public ?AuthObject $authObject;

    public Auth $Auth;

    /**
     * Demo constructor.
     * @param Database|null $db
     * @param Auth|null $auth
     * @throws Exception
     */
    function __construct(Database $db = null, Auth $auth = null, $bypass = false)
    {
        parent::__construct(new VueRenderer($this->constants()));

        // template functions
        TemplateFunctions::registerClosure('convertTime', function($input){
            return preg_replace('/\s/','T', $input);
        });

        $credentials = getCredentials();

        $this->db = $this->assignProvider('db', $db, function() use($credentials){
            return new DatabaseWrapper($credentials[$this->dbCredentials]);
        });

        // if api call, it depends on where it's from
        Event::hook('Core\\Api::incoming', function($ev) use($db, $auth, $credentials, $bypass){
            // same origin get?
            if(
                (isset($ev['HTTP_SEC_FETCH_SITE']) && $ev['HTTP_SEC_FETCH_SITE'] === 'same-origin') ||
                (isset($ev['HTTP_ORIGIN']) && $ev['HTTP_ORIGIN'] === preg_replace('/\/$/','', base))
            ){
                $this->Auth = $this->assignProvider('auth', $auth, function () use ($credentials){
                    return new SessionWrapper($credentials[$this->authSecret]['secret']);
                });

            } else {

                $this->Auth = $this->assignProvider('auth', $auth, function () use ($credentials){

                    return $this->createJWTWrapper();
                });
                if(!$bypass){
                    $this->externalCall($ev['REQUEST_METHOD']);
                }

            }

        });
        // if rendering, always load vue-renderer
        Event::hook('Core::beforeInit', function ($ev) use ($db, $auth, $credentials){
            $this->Auth = $this->assignProvider('auth', $auth, function () use ($credentials){
                return new SessionWrapper($credentials[$this->authSecret]['secret']);
            });
            $this->renderer->includeElement('Verification',['verification'=>$this->honeyPot()]);
        });





        /*
         * PHP8 Attributes
         * */
        if(PHP_MAJOR_VERSION >= 8){
            $phpAttributes = new UseAttributes();
            $phpAttributes->hookAttributes($this->provider);
            $this->authObject = $phpAttributes->authObject;
        }
    }

    /**
     * @throws Exception
     */
    function createJWTWrapper(): JwtWrapper
    {
        $wrapper = new JwtWrapper();
        $wrapper->setSecret(getCredentials()[$this->authSecret]['secret']);
        return $wrapper;
    }
    // needs active auth
    function externalCall($method)
    {

        if(sub(1)==='auth' && $method === 'POST'){
            $authController = new AuthController($this->db,$this->Auth,true);
            echo json_encode($authController->apiLogin(sub(2), sub(3)));
            exit();
        }
        $this->authObject = $this->Auth->validate();

    }

    /**
     * @throws Exception
     */
    function honeyPot($code=null): array|bool
    {
        $secret = getCredentials()[$this->authSecret]['secret'];
        $colors = ['red','green','blue'];
        if($code===null){
            $randomColor = array_rand($colors);
            return ['code'=>Ops::encrypt(date('m-dH').$colors[$randomColor],$secret), 'color'=>$colors[$randomColor]];
        }
        try{
            $parts = explode('-', $code);
            $encrypted = $parts[0];
            $givenColorValue = $parts[1];
            $decrypted = Ops::decrypt($encrypted,$secret);
            $serverValue = date('m-dH');
            $check = preg_match("/$serverValue/", $decrypted, $matches);
            if(!$check){
                throw new \Exception('bot');
            }
            $targetColor = substr($decrypted,strlen($matches[0]));

            preg_match_all('/\d{1,3}/', $givenColorValue, $matches);
            $key = array_search(max($matches[0]), $matches[0]);
            if($colors[$key] !== $targetColor){
                throw new RouteException('bot',401);
            }
        } catch (\Exception $e) {
            throw new RouteException('bot',401);
        }

        return true;
    }

    function getMailClient() : Client
    {
        $keys = getCredentials()['blua_mailjet'];
        return new Client($keys['public'],$keys['private'], true,['version'=>'v3.1']);
    }
    function standardMail($toEmail, $toName, $subject, $body) : Response
    {
        $mailing = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "neoan@blua.blue",
                        'Name' => "Blua.Blue"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => "A message from blua.blue in HTML format",
                    'HTMLPart' => $body
                ]
            ]
        ];
        $mail = $this->getMailClient();
        return $mail->post(Resources::$Email, ['body' => $mailing]);
    }

    function output($params = [])
    {
        if(empty($params)){
            $params = ['always'=> ['Gdpr']];
        }

        $this->renderer->includeElement('Header');
        $this->renderer->includeElement('Footer');
        $this->hook('header', 'header');
        $this->hook('footer', 'footer');
        parent::output($params);
    }

    /**
     * Overwriting Serve's constants()
     * @return array
     */
    function constants()
    {
        return [
            'base' => [base],
            'modules' => [],
            'store'=>[],
            'link' => [
                [
                    'sizes' => '32x32',
                    'type' => 'image/png',
                    'rel' => 'icon',
                    'href' => 'asset/neoan-favicon.png'
                ]
            ],
            'stylesheets' => [
                base . '/frame/BluaBlue/style.css',
                base . '/frame/BluaBlue/base.css',
                 'https://cdn.jsdelivr.net/npm/jodit@3.8.5/build/jodit.min.css',
            ],
            'js'=> [
                ['src'=>'https://cdn.jsdelivr.net/npm/sortablejs@1.10.2/Sortable.min.js'],
                ['src'=>'https://cdn.jsdelivr.net/npm/vuedraggable@4.1.0/dist/vuedraggable.umd.min.js'],
                ['src'=>'https://cdn.jsdelivr.net/npm/jodit@3.8.5/build/jodit.min.js'],
                ['src'=>'  https://cdn.jsdelivr.net/npm/showdown@latest/dist/showdown.min.js'],
            ]
        ];
    }
}
