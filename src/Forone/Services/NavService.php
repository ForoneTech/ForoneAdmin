<?php
/**
 * User : YuGang Yang
 * Date : 7/29/15
 * Time : 11:02
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Services;

use Illuminate\Support\Facades\URL;

class NavService
{

    public function isActive($uri)
    {
        if (strripos($uri, '|')) {
            $uris = explode('|', $uri);
            foreach ($uris as $name) {
                if (strripos(URL::current(), $name)) {
                    return 'active';
                }
            }
        } else if (strripos(URL::current(), $uri)) {
            return 'active';
        }
        return '';
    }

}