<?php

namespace jCube\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Lang;
use jCube\Models\SeoName;

trait hasSeoName
{
  public function slug(): Attribute
  {
    return Attribute::make(
      fn($value) => $this?->path?->name
    );
  }
  
  public function url(): Attribute
  {
    return Attribute::make(
      fn($value) => $this?->path?->full_path
    );
  }
  
  public function parent(): Attribute
  {
    return Attribute::make(
      fn($value) => $this?->path?->path
    );
  }
  
  public function next(): Attribute
  {
    return Attribute::make(
      fn($value) => $this?->path?->next_path
    );
  }
  
  
  public function path(): MorphOne
  {
    $this->hidden[] = 'path';
    return $this->morphOne(SeoName::class, "object")->where('locale', self::$lang ?: Lang::getLocale());
  }
  
  public function saveName($item, $locale)
  {
    $name = SeoName::where('object_type', self::class)
      ->where('object_id', $this->id)
      ->where('locale', $locale)
      ->firstOr(function () use ($locale) {
        return new  SeoName([
          "object_type" => self::class,
          "object_id" => $this->id,
          'locale' => $locale
        ]);
      });
    $name->fill($item);
    $name->save();
  }
  
  public function moveName($path)
  {
    $names = SeoName::where('object_type', self::class)
      ->where('object_id', $this->id)
      ->get();
    foreach ($names as $name) {
      $name->path = $path > 0 ? $path : null;
      $name->save();
    }
  }
  
}