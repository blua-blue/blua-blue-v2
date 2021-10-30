<?php

namespace Neoan3\Component\Migrate;

use Neoan3\Core\Event;
use Neoan3\Core\Renderer;
use Neoan3\Core\RouteException;
use Neoan3\Core\Serve;
use Neoan3\Provider\FileSystem\File;
use Neoan3\Provider\FileSystem\Native;

/**
 * Class MigrateController
 * @package Neoan3\Component\Migrate
 *
 * Generated by neoan3-cli for neoan3 v3.*
 */
class MigrateController extends Serve
{
    private bool $isSafeSpace;
    private bool $isNewestCli = true;
    private Native $fileSystem;
    private bool $execWorks = false;

    public function __construct(Renderer $renderer = null, Native $fileSystem= null)
    {
        parent::__construct($renderer);
        $this->fileSystem = $this->assignProvider('file', $fileSystem, function (){
            return new File();
        });
        $this->isSafeSpace = $this->fileSystem->exists(dirname(path) . '/.safe-space');
        if($this->isSafeSpace){
            // only run version-check if executed as route-component
            Event::hook('Core::beforeInit', function (){
                $cliVersion = $this->runShellCommand('neoan3 -v');

                preg_match('/v([0-9]+)\.([0-9]+)\.([0-9]+)/',$cliVersion, $version);
                if(!empty($version)){
                    // current minimum requirement
                    $this->isNewestCli = $version[1] >= 1 && $version[2]>=5 && $version[3]>=2;
                }
            });
        }
    }

    /**
     * Route: Migrate
     */
    function init(): void
    {
        $this
            ->callback(function($serve){
                $serve->renderer->includeJs(__DIR__ . '/migrate.ctrl.js',[
                    'base'=>base,
                    'models' => $serve->migrateFiles(),
                    'safeSpace' => $serve->isSafeSpace,
                    'newestCli' => $this->isNewestCli
                ]);
                $serve->renderer->includeStylesheet('https://cdn.jsdelivr.net/npm/gaudiamus-css@latest/css/gaudiamus.min.css');
                $serve->renderer->includeJs('https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.min.js');
                $serve->renderer->includeStylesheet(base . 'frame/Demo/demo.css');
            })
            ->hook('main', 'migrate', ['cli-requirement' => $this->isNewestCli])
            ->hook('header','nav')
            ->output();
    }

    function postMigrate(array $body): array
    {
        $folder = path . '/model/' . ucfirst($body['name']);
        if($this->fileSystem->exists($folder) ){
            $this->generateInterfaces($body, $folder);
            $this->fileSystem->putContents($folder. '/migrate.json', json_encode($body['migrate']));
            $oopWrapper = new GenerateWrapper($body['name'], $this->fileSystem);
            $oopWrapper->generate();
            return $this->updateDatabase($body['dbCredentials']);
        }
        return ['success'=> false];
    }

    /**
     * @throws RouteException
     */
    function putMigrate($body):array
    {
        if(!$this->isSafeSpace){
            throw new RouteException('Not within safe space', 401);
        }
        $this->runShellCommand("neoan3 new model ". $body['name']);
        return json_decode($this->migrateFiles());
    }
    private function updateDatabase($credentialName): array
    {
        if($this->isSafeSpace){
            $try = $this->runShellCommand('neoan3 migrate models up -n:' . $credentialName);
            return ['success'=> preg_match('/done/',$try) ? 'safe-space' : false];
        }
        return ['success'=> true];
    }

    private function migrateFiles()
    {
        $models = [];
        foreach ($this->fileSystem->glob(path . '/model/*/migrate.json') as $migrate) {
            preg_match('/model\/([^\/]+)/', $migrate, $name);
            $models[] = [
                'name' => $name[1],
                'migrate' => json_decode($this->fileSystem->getContents($migrate), true)
            ];
        }
        return json_encode($models);
    }

    private function generateInterfaces($migrate, $folder)
    {
        $generator = new GenerateTSInterface($migrate['migrate']);
        $this->fileSystem->putContents($folder . '/' . $migrate['name'] . '.ts', $generator->generate());
    }
    private function runShellCommand($neoanCommand)
    {
        $path = path;
        $command = "cd $path && $neoanCommand";
        return shell_exec($command);
    }

}