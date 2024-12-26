<?php

namespace Heroic\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Symfony\Component\Yaml\Yaml;

class PagesRouter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ambil URI dari request
        $uri = strtolower($request->getPath());
        $uri = trim($uri, '/');
        $uriSegments = explode('/', $uri);

        // Cek apakah segment pertama adalah /api
        $isApi = false;
        $controllerName = 'PageController';
        if ($uriSegments[0] === 'api') 
        {
            array_shift($uriSegments);
            $controllerName = 'APIController';
            $isApi = true;
        }

        // Path ke folder Pages
        $basePath = APPPATH . 'Pages';
        
        // Evaluasi apakah folder sesuai dengan URI
        $found = false;

        while (count($uriSegments) > 0) {
            $folderPath = $basePath . '/' . str_replace('/', DIRECTORY_SEPARATOR, implode('/', $uriSegments));
            if (is_dir($folderPath)) {
                $found = true;
                $uri = implode('/', $uriSegments);
                break;
            }
            array_pop($uriSegments);
        }

        // Jika tidak ada yang cocok, kembalikan 404
        if(! $found) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        // Cek apakah ada folder dengan struktur tersebut
        if (is_dir($folderPath)) {
            // Pastikan ada file controller 
            
            $controllerPath = $folderPath . '/' . $controllerName . '.php';
            if (file_exists($controllerPath)) {
                // Ubah namespace dan jalankan controller
                $controllerNamespace = '\\App\\Pages\\' . str_replace('/', '\\', $uri) . '\\' . $controllerName;
                
                // Add route resource for the controller
                $routeCollection = service('routes');

                // HACK, inject new property to route collection
                $metaFile = APPPATH . 'Pages/' . $uri . '/meta.yml';
                $routeCollection->pageData = Yaml::parseFile($metaFile); 
                $routeCollection->currentURI = $uri;

                if($isApi) {
                    // Route to resource controller
                    $routeCollection->resource('api/' . $uri, ['controller' => $controllerNamespace]);
                } else {
                    // Route to base controller
                    $routeCollection->get($uri, $controllerNamespace . '::index');
                    $routeCollection->get($uri . '/(:any)', $controllerNamespace . '::detail/$1');
                    $routeCollection->post($uri, $controllerNamespace . '::process');
                }
                // dd($routeCollection->getRoutes());

                return $routeCollection;
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah response
    }
}
