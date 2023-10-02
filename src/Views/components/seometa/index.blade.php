@props([
	'item',
	'extended' => false,
	'meta_title' => '',
	'meta_description' => '',
	'meta_keywords' => '',
	'raw_html' => '',
])

<div class="form-group">
  <x-form.input name="meta_title" data-field="meta_title" label="Meta title" :value="$meta_title"/>
</div>
<div class="form-group">
  <x-form.input type="text" name="meta_description" data-field="meta_description" label="Meta Description" :value="@$meta_description"
                rows="4"/>
</div>
<div class="form-group">
  <x-form.input type="text" name="meta_keywords" data-field="meta_keywords" label="Meta Keywords" :value="$meta_keywords"
                rows="4"/>
</div>
@if($extended)
  <div class="form-group">
    <x-form.input type="text" name="raw_html" data-field="raw_html" label="HTML" :value="$raw_html" rows="4"/>
  </div>
@endif
