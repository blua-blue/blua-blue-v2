<?php


namespace Neoan3\Frame;


use DOMDocument;
use DOMXPath;
use Neoan3\Apps\Ops;
use Neoan3\Apps\Template;
use Neoan3\Core\ReflectionWrapper;
use Neoan3\Core\Renderer;
use Neoan3\Core\RouteException;

class VueRenderer implements Renderer
{
    private ?string $componentName = null;
    private string $lang = 'en';
    public array $hooks = [];
    public array $browserImports = [];
    public array $loadedElements = [];
    private array $templateParameters = [];
    public array $storeObject = [];
    private string $title = '';
    public array $exposedParams = [
        'base' => base,
        '$nRoutes' => []
    ];
    public function __construct($constants = [])
    {
        $this->hooks = [
            'title' => $this->title,
            'current_endpoint' => OPS::toCamelCase(current_endpoint),
            'theming' => file_get_contents(__DIR__. '/component-theming.js'),
            'header' => '',
            'main' => '',
            'footer' => '',
            'components' => '',
            'templates' => '',
            'scripts' => '',
            'topScripts' => '',
            'store' => '',
            'styles' => '',
            'base' => base,
            'spaRoutes' => '',
            'seo'=>''
        ];
        foreach ([0,1,2,3,4] as $i => $p){
            $v = sub($p);
            $this->exposedParams['$nRoutes'][] = $v;
            $this->exposedParams['sub.'. $i] = $v;
        }
        $this->readConstants($constants);
    }

    private function readConstants($constants)
    {
        foreach ($constants['modules'] as $include) {
            $this->hooks['scripts'] .= "<script type='module' src=\"$include\"></script>";
        }
        foreach ($constants['js'] as $js){
            if(isset($js['data'])){
                $this->hooks['topScripts'] = Template::embrace(file_get_contents($js['src']),$js['data']);
            } else {
                $this->hooks['scripts'] .= "<script type='application/javascript' src=\"{$js['src']}\"></script>";
            }

        }
        foreach ($constants['store'] as $store){
            foreach ($store as $name => $set){
                $this->storeObject[$name]['endpoints'] = $set['endpoints'];
                $this->storeObject[$name]['state'] = $set['state'];
            }

        }
        foreach ($constants['stylesheets'] as $stylesheet){
            $this->hooks['styles'] .= '<link rel="stylesheet" href="'.$stylesheet.'">';
//            $this->hooks['styles'] .= '<style>' . file_get_contents($stylesheet) . '</style>';
        }
    }

    public function output($afterHooks = []): void
    {
        $this->hooks['store'] ='const storeObjects = Vue.reactive('. json_encode($this->storeObject) . ');';
        $this->hooks['browserImports'] = implode("", $this->browserImports);
        $this->hooks['title'] = $this->title;
        $this->hooks['lang'] = $this->lang;
        foreach ($this->storeObject as $name => $value){
            $this->storeObject($name, $value['state']);
        }
        if(isset($afterHooks['always'])){
            foreach ($afterHooks['always'] as $always){
                $this->includeElement($always);
                $this->assignToHook('temporary', $always);
                $this->hooks['footer'] .= $this->hooks['temporary'];
            }
        }
        // compress html
        $this->hooks['header'] = preg_replace('/\n\s*/','',$this->hooks['header']);
        $this->hooks['main'] = preg_replace('/\n\s*/','',$this->hooks['main']);
        $this->hooks['footer'] = preg_replace('/\n\s*/','',$this->hooks['footer']);

        // compress style
        $this->hooks['styles'] = preg_replace('/\n\s*/','',$this->hooks['styles']);

        echo Template::embraceFromFile('/frame/BluaBlue/scaffold.html', $this->hooks);
    }

    public function setComponentName($qualifiedClassName): void
    {
        $this->componentName = $qualifiedClassName;
    }

    public function getComponentName(): string
    {
        return $this->componentName;
    }

    public function setLang(string $lang): void
    {
        $this->lang = $lang;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function attachParameters($assocArray = []): void
    {
        $this->templateParameters = array_merge($this->templateParameters, $assocArray);
    }

    public function readTemplateTag($path)
    {
        $vueComponent = file_get_contents($path);
        preg_match("/<template>(.+)<\/template>[\r\n]*<(script|style)>/ms", $vueComponent, $ssrTemplate);
        return $ssrTemplate[1];
    }

    private function parseRoutes(array $routes)
    {
        foreach ($routes as $route){
            foreach ($route as $name => $path){
                $name = Ops::toPascalCase($name);
                $this->hooks['spaRoutes'] .= "\nrouter.addRoute({path: '$path', component: n$name, name: '$name'})";
                if(!in_array($name,$this->loadedElements)){
                    $this->includeElement($name);
                }
            }
        }
    }
    private function parseStores(array $stores)
    {
        foreach ($stores as $name => $definitions){
            if(!isset($this->storeObject[$name])){
                $this->storeObject[$name] = [
                    'endpoints' => [],
                    'state' => []
                ];
            }
            foreach ($definitions as $method => $handle){
                $actions = explode(',',preg_replace('/\s/','', $handle));
                $usesAuto = in_array('auto', $actions);
                $preloads = in_array('preload', $actions);

                if($usesAuto){
                    $route = '/vue/' . $name;
                } else {
                    $endpoints = preg_grep('/^\/[^$]*/', $actions);
                    $route = end($endpoints);
                }
                $this->storeObject[$name]['endpoints'][$method] = $route;
                try{
                    if($preloads && $usesAuto){
                        $className = '\\Neoan3\\Component\\Vue\\VueController';
                        $apiCtrl = new $className();
                        $function = 'getVue';
                        $this->storeObject[$name]['state'] = $apiCtrl->$function($name);
                    } elseif ($preloads) {
                        $className = '\\Neoan3\\Component\\' . Ops::toPascalCase($name) . '\\' . Ops::toPascalCase($name) . 'Controller';
                        $function = 'get' . Ops::toPascalCase($name);
                        $r = new ReflectionWrapper($className, $function);
                        $r->dispatchAttributes(__NAMESPACE__);
                        $apiCtrl = new $className();

                        $state = $apiCtrl->$function();
                        $this->storeObject[$name]['state'] = $state;
                    }
                } catch (RouteException $e){
                    redirect();
                }

            }
        }
    }

    private function handleImports($script) : string
    {
        return preg_replace_callback('/^(@)*import(\(([^\)]*)\)|\s([^\s]+)([^\n]*))/im',function($hit){
            if(!empty($hit[1]) && !empty($hit[3])){
                $atImports = json_decode($hit[3], true);
                if(isset($atImports['routes'])){
                    $this->parseRoutes($atImports['routes']);
                }
                if(isset($atImports['store'])){
                    $this->parseStores($atImports['store']);
                }
            } elseif(!isset($this->browserImports[$hit[4]])) {
                $this->browserImports[$hit[4]] = '  '.$hit[0];
            }

            return "/*$hit[0]*/\n";
        }, $script);
    }

    public function includeJs($src)
    {
        $this->hooks['scripts'] .= '<script src="'.$src.'"></script>';
    }
    /**
     * @param $style
     */
    function includeStylesheet($style)
    {
        $this->hooks['styles'] .= '<link rel="stylesheet" href="'.$style.'">';
    }


    public function includeElement($element, $params =[]): VueRenderer
    {
        if(in_array($element, $this->loadedElements)){
            return $this;
        }
        $params = array_merge($this->exposedParams, $params);

        $path = path . '/component/'. ucfirst($element) . '/' . ucfirst($element) . '.vue';
        if(file_exists($path)){
            $this->loadedElements[] = $element;
            $params['template'] = $this->readTemplateTag($path);

            // remove ssr notations from template

            // attributes using curlies
            $params['template'] = preg_replace('/\s[a-z]+="{{[a-z_\.]+}}"/i','', $params['template']);
            // n-x attributes?

            $doc = $this->loadDomDoc(file_get_contents($path));
            $script = $this->prepareScript($doc, $params);



            $this->attachStyles($doc,'n-' . Ops::toKebabCase($element));

            // handle imports & remove duplicates
            $script = $this->handleImports($script);

            // register globally
            $script = preg_replace('/export default/', "const n$element = ", $script);
            $script .= "\napp.component('n$element', n$element);";
            $this->hooks['components'] .= $script;
        }

        return $this;
    }


    public function assignToHook($name, $view, $params = []): void
    {
        if($name === 'seo'){
            $this->attachParameters($params);
            $this->hooks[$name] = Template::embraceFromFile(
                '/component/' . ucfirst($view) . '/' . lcfirst($view) . '.view.html',
                $this->templateParameters
            );
        } else {
            $path = path . '/component/'. ucfirst($view) . '/' . ucfirst($view) . '.vue';
            $template = $this->readTemplateTag($path);
            $currentRoute = Ops::toCamelCase($view);
            $condition = $name === 'main' ? "v-if=\"\$route.path === '/$currentRoute'\"" : '';
            foreach ($this->storeObject as $storeName => $nfo){
                $params = array_merge([$storeName => $nfo['state']], $params);
            }
            $this->hooks[$name] = "<n-$view $condition>". Template::embrace($template,$params) . "</n-$view>";
        }

    }
    public function storeObject($constName, $value){
        $this->hooks['scripts'] .= "<script>\nstoreObjects.$constName.state = " . json_encode($value) . ';</script>';
    }

    public function prepareStyles(DOMDocument $doc, $selector=''): string
    {
        $style = '';
        $xPath = new DOMXPath($doc);
        $styleTags = $xPath->query('//style');
        if($styleTags){
            for($i=0;$i<$styleTags->length;$i++){
                $style .= $styleTags->item($i)->textContent;
            }

        }
        return $style;
    }

    private function attachStyles(DOMDocument $doc, $selector = '')
    {
        $xPath = new DOMXPath($doc);
        $tag = $xPath->query('//style')->item(0);
        if($tag){
            $pure = $doc->saveHTML($tag);
            if($tag->attributes->length > 0){
                foreach ($tag->attributes as $attribute){
                    switch($attribute->name){
                        case 'scoped':
                            $this->hooks['styles'] .= preg_replace('/\n([a-z#\.])/', "\n$selector $1", $pure);
                            break;
                        default:
                            $this->hooks['styles'] .= $pure;
                    }
                }
            } else {
                $this->hooks['styles'] .= $pure;
            }

        }
    }
    public function loadDomDoc($html) : DOMDocument
    {
        libxml_use_internal_errors(TRUE);
        // secure @-notation
        $html = preg_replace('/(@)([a-z-]+)=/i', 'v-on:$2=', $html);
        // secure #-notation
        $html = preg_replace('/(<template[a-z\s\"=-]+)(#)([a-z-]+)/i', '$1v-slot:$3', $html);

        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->loadHTML("<html>$html</html>",LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOBLANKS );
        return $doc;
    }

    private function attachTemplateTag(DOMDocument $doc, &$params = [])
    {
        $xPath = new DOMXPath($doc);
        // template
        $template = mb_substr(Template::embrace(

            $doc->saveHTML($xPath->query('//template')->item(0)),
            $this->templateParameters
        ),mb_strlen('<template>'),mb_strlen('</template>') *-1);



        $params['template'] = $template;
    }
    public function prepareScript(DOMDocument $doc, $params)
    {

        $xPath = new DOMXPath($doc);
        $requiredComponents = $xPath->query('//*[starts-with(name(), "n-")]');
        foreach ($requiredComponents as $requiredComponent){
            $componentName = Ops::toPascalCase(mb_substr($requiredComponent->nodeName, 2));
            $this->includeElement($componentName);
        }
        $jsParams = [];
        foreach ($params as $key => $value){
            $jsParams[$key] = is_array($value) ? json_encode($value) : $value;
        }
        $script = substr($doc->saveHTML($xPath->query('//script')->item(0)),9,-10);
        return Template::embrace(
            $script,
            $jsParams
        );

    }
}