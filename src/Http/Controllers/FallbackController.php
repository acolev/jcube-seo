<?php

namespace jCube\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Language;
use jCube\Models\SeoName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class FallbackController extends Controller
{
  public function __invoke(string $path, Request $request)
  {
    $pathArray = explode('/', $path);
    
    $def_lang = config('app.locale');
    if (class_exists(Language::class)) {
      $def_lang = Language::where('is_default', 1)->pluck('code')->first();
    }
    
    if ($def_lang === $pathArray[0]) abort(404);
    
    $seoName = SeoName::where('name', last($pathArray))->where('locale', Lang::getLocale())->firstOrFail();
    
    if (count($pathArray) > 0) {
      $prefix = '';
      if ($def_lang !== Lang::getLocale()) {
        $prefix = Lang::getLocale() . '/';
      }
      if ($path !== $prefix . $seoName->fullPath) abort(404);
    }
    
    $controller = new $seoName->controller();
    $action = $seoName->action;
    return $controller->$action($seoName->object_id, $seoName);
  }
}
