<?php

namespace Neoan3\Component\DevTo;

use League\HTMLToMarkdown\HtmlConverter;
use Neoan3\Apps\Curl;
use Neoan3\Apps\Db;
use Neoan3\Apps\Ops;
use Neoan3\Core\Event;
use Neoan3\Core\RouteException;
use Neoan3\Frame\BluaBlue;
use Neoan3\Model\Image\ImageModel;
use Neoan3\Provider\Auth\Auth;
use Neoan3\Provider\Auth\Authorization;
use Neoan3\Provider\MySql\Database;

/**
 * Class DevToController
 * @package Neoan3\Component\DevTo
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */

class DevToController extends BluaBlue {
    private string $apiKey;
    private HtmlConverter $markdown;

    public function __construct(Database $db = null, Auth $auth = null, $bypass = false)
    {
        parent::__construct($db, $auth, $bypass);
        Event::listen('Plugin::DevTo', function ($ev){
            $result = $this->process($ev);
            Event::dispatch('Plugin::DevTo::out',$result);
        });
    }

    /**
     * Route: DevTo
     */
    function init(): void
    {
        $this->renderer->includeElement('DevTo');
        $this->hook('main', 'devTo', []);
        $this->output();
    }
    #[Authorization('restrict',['all'])]
    function getDevTo($apiKey, $params=[])
    {
        $this->apiKey = $apiKey;
        $header = $this->curlHeader();
        $testAnswer = Curl::curling('https://dev.to/api/articles/me',[],$header, 'GET');
        $credentials = getCredentials();
        $key = $credentials['blua_stateless']['secret'];
        $encrypted = Ops::serialize(Ops::encrypt($apiKey, $key));
        return ['token' => $encrypted, 'test' => $testAnswer];
    }

    /**
     * @throws RouteException
     */
    function process(array $article)
    {
        $this->secure($article['token']);
        $info = null;
        switch ($article['event']) {
            case 'created':
            case 'updated':
                // find existing
                $update = $this->investigateStoreObject($article['payload']['article_store']);
                $devBody = $this->transformPayload($article['payload']);
                $info = $this->sendToDevTo($article['payload']['id'], $devBody, $update);
                break;
            case 'deleted':
                break;
        }
        return ['webhook' => 'received', 'info' => $info];
    }

    private function sendToDevTo($articleId,$payload, $existingId)
    {
        $header = $this->curlHeader();
        $url = 'https://dev.to/api/articles' . ($existingId ? '/' . $existingId : '');
        $call = Curl::curling($url, json_encode(['article' => $payload]), $header, $existingId ? 'PUT' : 'POST');
        if (isset($call['id']) && !$existingId) {
            Db::ask('article_store', [
                'article_id' => '$' . $articleId,
                'store_key'  => 'dev-to-id',
                'value'      => '=' .$call['id']
            ]);
        } else {
            file_put_contents(__DIR__ . '/error-' . date('Y_m_d-H_i_s') . '.json', json_encode($call));
        }
        return $call;
    }
    /**
     * @param $store
     *
     * @return bool
     */
    private function investigateStoreObject($store): mixed
    {
        foreach ($store as $possible) {
            if ($possible['store_key'] === 'dev-to-id') {
                return $possible['value'];
            }
        }
        return false;
    }

    /**
     * @param $payload
     *
     * @return array
     */
    private function transformPayload($payload): array
    {
        $isLocal = str_contains(base, 'localhost');
        $article = [
            'title'         => $payload['name'],
            'tags'          => explode(',', $payload['keywords']),
            'description'   => $payload['teaser'],
            'body_markdown' => $this->prepareContent($payload['article_content'])
        ];
        if (!$isLocal) {
            $article['canonical_url'] = base . 'article/' . $payload['slug'] . '/';
        }
        if (!empty($payload['publish_date'])) {
            $article['published'] = true;
        }
        if ($payload['image_id'] && !$isLocal) {
            $image = $this->loadModel(ImageModel::class)::get($payload['image_id']);
            $article['cover_image'] = (!str_starts_with($image['path'], 'http') ? base : '') . $image['path'];
        }

        return $article;
    }

    /**
     * @param $contentArray
     *
     * @return string
     */
    private function prepareContent($contentArray): string
    {
        $content = '';
        foreach ($contentArray as $contentPart) {
            $content .= $this->convertContent($contentPart);
        }
        return $content;
    }

    /**
     * @param $content
     *
     * @return string
     */
    private function convertContent($content): string
    {
        $answer = '';
        if (!isset($this->markdown)) {
            $this->markdown = new HtmlConverter(['strip_tags' => true]);
        }
        switch($content['content_type']){
            case 'markdown': $answer =  $content['content'];
                break;
            case 'html' :
                try{
                    $answer = $this->markdown->convert($content['content']);
                } catch (\Exception $e){
                    $answer = '';
                }
                break;
            case 'img' :
                try{
                    $answer = $this->markdown->convert($content['html']);
                } catch (\Exception $e){
                    $answer = '';
                }
                break;
        }
        return $answer;
    }

    /**
     * @throws RouteException
     */
    private function secure($token)
    {
        try{
            $salt = getCredentials()['blua_stateless']['secret'];
            $this->apiKey = Ops::decrypt(Ops::deserialize($token), $salt);
        } catch (\Exception $e) {
            throw new RouteException('Issue', 500);
        }

    }

    private function curlHeader(): array
    {
        return [
            'User-Agent: neoan3',
            'Content-Type: application/json',
            'api-key: ' . $this->apiKey
        ];
    }

}
