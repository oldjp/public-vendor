# Silex provider:

Silex provider define addittional routes whitch are resolved as vendor assets (this is only helpful when you don't 
want to have `vendor` folder in `public` directory). For example:

```
project
  app - application directory 
  vendor - composer libraries
    author
      package
        assets
          css
          js
            file.js
          img
  public - http root folder
    css
    js
    img
```

If you want to access to package assets using public-vendor provider use this url:

```html
<script src="/vendor/author/package/assets/js/file.js"></script>
```

### Setup provider

```php
$app = Silex\Application();
$app->register(new PublicVendor\ServiceProvider());
```

### Setup vendor path

Default vendor path is `__DIR__.'../../../../'` as it should be in composer's vendor folder.

```php
$app['public-vendor']->setPath('new/path');
```

### Setup aliases

Alias helps to remove one part of vendor name, for example: from `author/package` to `package`.

This configuration:

```php
$app['public-vendor']->addAlias('author/package', 'package');
```

will make this path available:

```html
<script src="/vendor/package/assets/js/file.js"></script>
```

### Setup sub paths

Sub paths define directory in package where asset are stored. This configuration:

```php
$app['public-vendor']->addSubPath('author/package', 'assets');
```

will make this path available:

```html
<script src="/vendor/author/package/js/file.js"></script>
```

# Grunt Task:

If you are using `usemin` task you can add `publicVendor` task before `useminPrepare`. It's important to setup `gruntfile.js` in the same directory as composer's `vendor` directory. For example:

```
project
  vendor
  public
  gruntfile.js
```

### Setup task

```js
grunt.loadTasks('vendor/johnpitcher/public-vendor/tasks')
grunt.initConfig({
  publicVendor: {
    options:{
      aliases: { 'package' : 'author/package' },
      subPaths: { 'author/package' : 'assets' },
      baseDir: 'vendor'
    }
  },
  useminPrepare: { ... }
);
```





