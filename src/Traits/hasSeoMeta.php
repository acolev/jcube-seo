<?php

namespace jCube\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Lang;
use jCube\Models\SeoMeta;

trait hasSeoMeta
{
  public function metaTitle(): Attribute
  {
    return Attribute::make(
      fn($value) => $this?->meta?->meta_title
    );
  }
  
  public function metaKeywords(): Attribute
  {
    return Attribute::make(
      fn($value) => $this?->meta?->meta_keywords
    );
  }
  
  public function MetaDescription(): Attribute
  {
    return Attribute::make(
      fn($value) => $this?->meta?->meta_description
    );
  }
  
  public function meta(): MorphOne
  {
    $this->hidden[] = 'meta';
    return $this->morphOne(SeoMeta::class, "object")->where('locale', self::$lang ?: Lang::getLocale());
  }
  
  public function saveMeta($item, $locale)
  {
    $name = SeoMeta::where('object_type', self::class)
      ->where('object_id', $this->id)
      ->where('locale', $locale)
      ->firstOr(function () use ($locale) {
        return new  SeoMeta([
          "object_type" => self::class,
          "object_id" => $this->id,
          'locale' => $locale
        ]);
      });
    $name->fill($item);
    $name->save();
  }
  
}