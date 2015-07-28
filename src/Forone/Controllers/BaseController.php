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
            $pageTitles = config('forone.nav_titles');
            if ($pageTitles) {
                $curRouteName = Route::currentRouteName();
                if (array_key_exists($curRouteName, $pageTitles)) {
                    $this->pageTitle = $pageTitles[$curRouteName];
                } else {
                    $this->pageTitle = $curRouteName;
                }
            }
        }
        View::share('pageTitle', $this->pageTitle);
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