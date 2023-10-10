<?php

namespace jCube\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\NoReturn;

class SeoName extends Model
{
  public $timestamps = false;
  public $fillable = [
    'name',
    'object_type',
    'object_id',
    'parent',
    'controller',
    'action',
    'path',
    'locale',
  ];
  
  static public function boot()
  {
    parent::boot();
    
    self::saved(function (SeoName $model) {
      if ($model->getOriginal('path') !== $model->path) {
        self::moveChildren($model);
      }
    });
  }
  
  public function fullPath(): Attribute
  {
    $ids = explode(',', $this->path);
    $parents = self::whereIn('object_id', $ids)
      ->where('object_type', $this->parent)
      ->where('locale', $this->locale)
      ->orderBy('path')
      ->pluck('name')
      ->toArray();
    return Attribute::make(
      get: fn() => implode('/', [...$parents, $this->name])
    );
  }
  
  public function nextPath(): Attribute
  {
    return Attribute::make(
      fn($value) => implode(',', array_filter([$this?->path, $this?->object_id]))
    );
  }
  
  public function save(array $options = []): bool
  {
    $exists = false;
    $exists_name = false;
    
    if ($this->getOriginal('name') !== $this->name) {
      if (!$this->exists) {
        $exists = self::where('object_type', $this->object_type)
          ->where('object_id', $this->object_id)
          ->where('locale', $this->locale)
          ->exists();
      }
      $exists_name = self::where('object_type', $this->object_type)
        ->where('name', $this->name)
        ->where('locale', $this->locale)
        ->exists();
    }
    
    if ($exists || $exists_name) {
      return false;
    }
    
    return parent::save($options);
  }
  
  static public function getUrl($object_type, $object_id, $lang, $prefix = null): string|null
  {
    $item = self::where('object_type', $object_type)
      ->where('object_id', $object_id)
      ->where('locale', $lang)
      ->first();
    return '/' . implode('/', array_filter([$prefix, $item?->full_path]));
  }
  
  static private function moveChildren(SeoName $model): void
  {
    if ($model->getOriginal('path')) {
      $child_path = implode(',', [$model->getOriginal('path'), $model->object_id]);
      $children = self::where('path', 'like', $child_path . '%')
        ->orWhere('path', $child_path)
        ->where('object_type', $model->object_type)->get();
      
      foreach ($children as $child) {
        $child->path = preg_replace('/^' . $model->getOriginal('path') . '/m', $model->path, $child->path);
        if ($model->path === null) $child->path = substr($child->path, 1);
        $child->save();
      }
    } else {
      $child_path = $model->object_id;
      $children = self::where('path', 'like', $child_path . '%')
        ->where('object_type', $model->object_type)->get();
      
      foreach ($children as $child) {
        $child->path = implode(',', [$model->path, $child->path]);
        $child->save();
      }
    }
  }
}
