<?php

class Category extends Eloquent {

    const TYPE_PRODUCT="Product";

    const TYPE_SERVICE="Service";

    private static $cacheTime = 1000000;

    public function scopeOrderByName($query, $order = 'asc') {
        return $query->orderBy('name', $order);
    }

    public function scopeParents($query) {
        return $query->where('category_id', '=', null);
    }

    public function scopeOnlyServices($query) {
        return $query->where('type', self::TYPE_SERVICE);
    }

    public function scopeOnlyProducts($query) {
        return $query->where('type', self::TYPE_PRODUCT);
    }

    public function parent() {
        return $this->belongsTo('Category', 'category_id');
    }

    public function subcategories() {
        return $this->hasMany('Category', 'category_id')->orderBy('name', 'asc');
    }

    public function publications() {
        return $this->belongsToMany('Publication', 'publications_categories');
    }

    public function publishers(){
        return $this->belongsToMany('Publisher','publishers_categories');
    }

    public static function getCategoryTree($type = 'Product') {
        $categories = Category::get();

        $level = array();
        $level[0] = Category::parents()->where('type', $type)->orderBy('name', 'asc')->lists('id');

        $currentLvl = 0;
        while (count($level[$currentLvl]) > 0) {
            $values = Category::whereIn('category_id', $level[$currentLvl])->orderBy('name', 'asc')->lists('id');

            if ($values == null){
                break;
            }
            $level[$currentLvl+1] = array();
            $level[$currentLvl+1] = (array) $values;
            $currentLvl++;
        }

        foreach ($categories as $category) {
            $cats[$category->id] = (object) $category->getAttributes();
            $cats[$category->id]->subcategories = array();
        }

//        foreach ($level[1] as $lvl) {
//            $id = intval($lvl);
//            $cats[$id]->subcategories = self::getSubCategories($cats[$id], $level[2], $cats);
//            //$cats[$cats[$id]->category_id]->subcategories[] = $cats[$id];
//        }
//
//        $tree = array();
//
//        foreach ($level[0] as $lvl) {
//            $id = intval($lvl);
//            $cats[$id]->subcategories = self::getSubCategories($cats[$id], $level[1], $cats);
//            $tree[] = $cats[intval($lvl)];
//        }

        $tree = array();

        $currentLvl--;
        while ($currentLvl >= 0) {
            foreach ($level[$currentLvl] as $lvl) {
                $id = intval($lvl);
                $cats[$id]->subcategories = self::getSubCategories($cats[$id], $level[$currentLvl+1], $cats);
                //$tree[] = $cats[intval($lvl)];
                if ($currentLvl == 0){
                    $tree[] = $cats[$id];
                }
            }
            $currentLvl--;
        }

        return $tree;

        //echo json_encode($tree);
        //die;

    }

    private static function getSubCategories($parent, $childs, $cats){

        $subcategories = array();
        foreach ($childs as $childId) {
            $id = intval($childId);
            if ($cats[$id]->category_id == $parent->id) {
                $subcategories[] = $cats[$id];
                //unset($cats[$childId]);
            }
        }

        return $subcategories;
    }

}