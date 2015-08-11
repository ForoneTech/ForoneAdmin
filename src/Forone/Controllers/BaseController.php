<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:36
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{

    protected $currentUser;
    protected $title;
    protected $pageTitle;
    protected $rules = [];

    function __construct()
    {
        $this->currentUser = Auth::user();
        View::share('currentUser', $this->currentUser);

        //share the config option to all the views
        View::share('siteConfig', config('forone.site_config'));
        if (!$this->pageTitle) {
            $this->pageTitle = $this->loadPageTitle();
        }
        View::share('pageTitle', $this->pageTitle);
    }

    private function loadPageTitle()
    {
        $pageTitles = config('forone.nav_titles');
        $curRouteName = Route::currentRouteName();
        if (array_key_exists($curRouteName, $pageTitles)) {
            return $pageTitles[$curRouteName];
        } else { // load menus title
            $url = URL::current();
            $menus = config('forone.menus');
            foreach ($menus as $title => $menu) {
                if (array_key_exists('children', $menu) && $menu['children'] ) {
                    foreach ($menu['children'] as $childTitle => $child) {
                        $pageTitle = $this->parseTitle($childTitle, $url, $child['active_uri']);
                        if ($pageTitle) {
                            return $pageTitle;
                        }
                    }
                } else {
                    $pageTitle = $this->parseTitle($title, $url, $menu['active_uri']);
                    if ($pageTitle) {
                        return $pageTitle;
                    }
                }
            }
        }
        return $curRouteName;
    }

    private function parseTitle($title, $currentUrl, $activeUrl)
    {
        $activeUrls = explode('|', $activeUrl);
        foreach ($activeUrls as $activeUri) {
            if (strripos($currentUrl, $activeUri)) {
                return $title;
            }
        }
        return null;
    }

    /**
     * @param null $view
     * @param array $data
     * @param array $mergeData
     * @return View
     */
    protected function view($view = null, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }

    /**
     * @param $error
     * @return $this
     */
    protected function redirectWithError($error)
    {
        return redirect()->to($this->getRedirectUrl())
            ->withErrors(['default' => $error]);
    }

}