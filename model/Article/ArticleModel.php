<?php
/* Generated by neoan3-cli */

namespace Neoan3\Model\Article;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use Neoan3\Apps\Ops;
use Neoan3\Model\User\UserModel;
use Neoan3\Provider\Model\Model;
use Neoan3\Provider\MySql\Database;
use Neoan3\Provider\MySql\Transform;

/**
 * Class ArticleModel
 * @package Neoan3\Model\Article
 * @method static get(string $id)
 * @method static create(array $modelArray)
 * @method static update(array $modelArray)
 * @method static find(array $conditionArray, array $callFunctions = [])
 * @method static delete(string $id, bool $hard = false)
 */
class ArticleModel implements Model
{

    /**
     * @var Database|null
     */
    private static ?Database $db = null;

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (!method_exists(self::class, $method)) {
            $transform = new Transform('article', self::$db);
            return self::outgoing($transform->$method(...self::incoming($method, ...$args)));
        } else {
            return self::$method(...$args);
        }
    }

    static public function incoming($method, $entity, $functions = []): array
    {
        if (in_array($method, ['update', 'create'])) {
            if (!empty($entity) && isset($entity['article_content'])) {
                if(!isset($entity['slug'])){
                    $entity['slug'] = Ops::toKebabCase($entity['name']) . '-' . Ops::randomString(4);
                }
                foreach ($entity['article_content'] as $i => $content) {
                    $entity['article_content'][$i]['content'] = '=' . $content['content'];
                }
            } elseif (!empty($entity)) {
                foreach ($entity as $i => $article) {
                    $entity[$i] = self::incoming($article, $functions);
                }
            }
        }

        return [$entity, $functions];
    }

    public static function outgoing($result)
    {
        UserModel::init(['db' => self::$db]);
        if (!empty($result) && isset($result['article_content'])) {
            // single article

            // content
            $environment = new Environment(['html_input' => 'escape']);
            $environment->addExtension(new CommonMarkCoreExtension());
            $environment->addExtension(new TableExtension());
            $converter = new MarkdownConverter($environment);
            usort($result['article_content'], function ($a, $b) {
                return ($a['sort'] < $b['sort']) ? -1 : 1;
            });
            foreach ($result['article_content'] as $i => $content) {
                if ($content['content_type'] === 'markdown') {
                    $result['article_content'][$i]['html'] = $converter->convertToHtml($content['content'])->getContent();
                } elseif ($content['content_type'] === 'html') {
                    $result['article_content'][$i]['html'] = $content['content'];
                } elseif ($content['content_type'] === 'img') {
                    $result['article_content'][$i]['html'] = self::imgHtml($content);
                } elseif ($content['content_type'] === 'video'){
                    $result['article_content'][$i]['html'] = self::videoHtml($content);
                }
            }
            // image
            if ($result['image_id']) {
                $image = self::$db->easy('image.*', ['id' => '$' . $result['image_id']]);
                if(empty($image)){
                    try{
                        // cleanup
                        self::$db->smart('article',['image_id'=>null], ['id'=>'$'.$result['id']]);
                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                    }

                    $result['image_id'] = null;
                } else {
                    $result['image'] = $image[0];
                }

            }

            // author
            $result['author'] = UserModel::get($result['author_user_id']);
            foreach (['user_email', 'user_profile'] as $exclude) {
                unset($result['author'][$exclude]);
            }

        } elseif (!empty($result)) {
            foreach ($result as $i => $article) {
                $result[$i] = self::outgoing($article);
            }
        }
        return $result;
    }
    private static function videoHtml($content)
    {
        $src = $content['title'] === 'youtube' ? 'https://www.youtube.com/embed/' : '';
        $src .= $content['content'];
        return "<div class=\"video-wrapper\">
        <iframe class=\"embedded-video {$content['title']}\" src=\"$src\" title=\"Player\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>
        </div>";
    }
    private static function imgHtml($content):string
    {
        return "
            <figure class=\"content-img-container\">
                <img class=\"content-img\" src=\"{$content['content']}\" alt=\"{$content['content']}\"/>
                <figcaption>{$content['title']}</figcaption>
            </figure>
        ";
    }
    public static function cleanUpImage($userId, $imageId, $imagePath)
    {
        // article
        $articles = self::find(['author_user_id'=>'$'.$userId]);
        foreach ($articles as $i => $article){
            // top level
            if($article['image_id'] === $imageId){
                $article['image_id'] = null;
            }
            // image content
            foreach ($article['article_content'] as $k => $content){
                if($content['content_type'] ==='img' && $content['content'] === $imagePath){
                    $article['article_content'][$k]['content'] = null;
                }
            }
            self::update($article);
        }

    }


    /**
     * @param array $providers
     */
    public static function init(array $providers)
    {
        foreach ($providers as $key => $provider) {
            if ($key === 'db') {
                self::$db = $provider;
            }
        }
    }

}