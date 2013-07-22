<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rreimi
 * Date: 7/14/13
 * Time: 3:06 PM
 * To change this template use File | Settings | File Templates.
 */


class PreviousRefererFilter {
    public function filter($route, $request, $prefix) {
        Session::put($prefix . '_referer', URL::previous());
    }
}