<?php 

namespace Heroic\Controllers;

use App\Controllers\BaseController;
use Symfony\Component\Yaml\Yaml;

class Page extends BaseController
{
    public function index()
    {
        $uri = service('uri');
        $segments = $uri->getSegments();

        if (empty($segments))
            $segments = [service('settings')->get('Theme.homePage')];

        $pageDetail = $this->pageDetail($segments);
        
        // Return JSON output if set
        if(is_object($pageDetail)) return $pageDetail;

        // Render page
        $fileTemplate = 'Pages/'.$pageDetail['uri'].'/content.php';
        if(! file_exists(APPPATH . 'Views/' . $fileTemplate))
            throw new \CodeIgniter\Exceptions\ConfigException('Template page file not found: content.php');
        return view($fileTemplate, $pageDetail);
    }

    private function pageDetail($segments, $customdata = [], $return_as_string = false)
    {
        // pecah segment url
        $strseg = implode('/', $segments);

        // Ambil page, Kalo page 404 pun ga ada juga, show 404 bawaan ci
        if(! $page = $this->getPage($strseg))
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Run page action class
        $pagedata = [];
        if(file_exists($page['path'].'/PageAction.php')){
            $actionPath = str_replace('/', '\\', $page['uri']);
            $ActionClassName = "App\Views\Pages\\{$actionPath}\PageAction";
            $Action = new $ActionClassName;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $Action->process();
            } elseif($this->request->getGet('dataonly')) {
                $pagedata = json_encode($Action->supply());
                return $this->response->setJSON($pagedata);
			} else {
                $pagedata = $Action->_output();
			}
        }

        // merge page data and other custom data
        $page = array_merge($page, $pagedata);

        return $page;
    }

    private function getPage($url = null, $parse = true)
    {
        // get page fields
        if(! $pagedata = $this->pageExists($url)){
            http_response_code(404);
            if(! $pagedata = $this->pageExists('404'))
                return false;
        }
        
        // get another md or html file as custom fields
        $files = scandir($pagedata['path']);

        foreach ($files as $file) {
            if(is_dir($pagedata['path'].'/'.$file)) continue;

            $filepath = pathinfo($pagedata['path'].'/'.$file);
        }

        $pagedata['url'] = $pagedata['uri'];
        $file_segment = explode('/', $pagedata['uri']);
        if(! empty($pagedata['uri'])){
            $pagedata['slug'] = array_pop($file_segment);
            if(! empty($pagedata['uri']))
                $pagedata['parent'] = implode('/', $file_segment);
        }

        return $pagedata;
    }

    /**
     * search if page is exist
     *
     * @access  private
     * @param   string  category, null for get all
     * @param   int     page number
     * @return  array
     */
    private function pageExists($url = null, $remain_uri = '')
    {
        if(file_exists(realpath(APPPATH.'Views/Pages/'.$url.'/meta.yml'))){
            $pagePath = realpath(APPPATH.'Views/Pages/'.$url);
            $metaFile = $pagePath.'/meta.yml';
        }
        else {
            if(!empty($url)){
                $url = explode('/', $url);
                $remain = array_pop($url);
                $url = implode('/', $url);
                return $this->pageExists($url, $remain);
            }
            return false;
        }

        $pagedata = Yaml::parseFile($metaFile);
        $pagedata['uri'] = $url;
        $pagedata['path'] = $pagePath;

        // Accept next non-page uri as param or not 
        if(!empty($remain_uri))
            if(!($pagedata['accept_param_uri'] ?? false))
                return false;

        return $pagedata;
    }
}
