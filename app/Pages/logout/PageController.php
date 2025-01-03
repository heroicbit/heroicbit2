<?php namespace App\Pages\logout;

use App\Pages\member\PageController as MemberPageController;
use CodeIgniter\API\ResponseTrait;

class PageController extends MemberPageController 
{
    use ResponseTrait;
    
    public function getIndex()
    {
        $_SESSION = [];
        setcookie("kodepesantren", "", time()-3600);

        return pageView('logout/index', $this->data);
    }

}