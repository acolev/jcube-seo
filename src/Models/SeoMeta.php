<?php

namespace jCube\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
  public $table = 'seo_meta';
  public $timestamps = false;

  protected $fillable = [
    'locale',
    'object_type',
    'object_id',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'description',
    'raw_html'
  ];

}
