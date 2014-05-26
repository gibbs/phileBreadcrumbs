PhileBreadcrumbs
================

A [PhileCMS](https://github.com/PhileCMS/Phile) plugin that generates 
breadcrumbs. The plugin returns an array leaving and adds no markup allowing
you to use it how you want.

## 1. Installation

**Install via composer**

```bash
composer require "gibbs/phile-breadcrumbs:1.*"
```

**Install via git**

Clone this repository from the ```phile``` directory into 
```plugins/gibbs/phileBreadcrumbs```. E.g:

```bash
git clone git@github.com:Gibbs/phileBreadcrumbs.git plugins/gibbs/phileBreadcrumbs
```

**Manual Install**

Download and extract the contents into: ```plugins/gibbs/phileBreadcrumbs```

## 2. Plugin Activation

Activate the plugin in your ```config.php``` file:

```php
$config['plugins']['gibbs\\phileBreadcrumbs'] = array('active' => true);
```

## 3. Examples

The following examples use the default Twig engine. The ```breadcrumbs``` 
variable contains three values:

1. ```active``` (true or false). The last item/crumb is true.
2. ```meta```. The crumbs parsed meta data. You can use any meta data.
3. ```uri```. The crumbs link/uri.

**Minimal Example**

```html
{% for crumb in breadcrumbs %}
	<a href="{{ crumb.uri }}">{{ crumb.meta.title }}</a>
{% endfor %}
```

**Bootstrap 3 Example**

```html
<ol class="breadcrumb">
	{% for crumb in breadcrumbs %}
		{% if crumb.active %}
			<li class="active">{{ crumb.meta.title }}</li>
		{% else %}
			<li><a href="{{ crumb.uri }}">{{ crumb.meta.title }}</a></li>
		{% endif %}
	{% endfor %}
</ol>
```

**Semantic UI Example**

```html
<div class="ui breadcrumb">
	{% for crumb in breadcrumbs %}
		{% if crumb.active %}
			<div class="active section">{{ crumb.meta.title }}</div>
		{% else %}
			<a href="{{ crumb.uri }}" class="section">{{ crumb.meta.title }}</a>
			<span class="divider"> / </span>
		{% endif %}
	{% endfor %}
</div>
```
