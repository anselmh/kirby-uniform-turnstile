# Uniform hCaptcha Guard

A [Kirby 3](https://getkirby.com/) plugin implementing a [hCaptcha](https://www.hcaptcha.com/) guard for the [Uniform](https://github.com/mzur/kirby-uniform) plugin.

## Installation

### Composer

Add the plugin to your project:

```
composer require leitsch/kirby-uniform-hcaptcha
```

## Configuration

Set the configuration in your `config.php` file:

```php
return [
  'leitsch.uniform-hcaptcha.siteKey' => 'my-site-key',
  'leitsch.uniform-hcaptcha.secretKey' => 'my-secret-key',
];
```

## Usage

### Template

You can use the provided helper function to embed the hCaptcha into your template:

```html+php
<?= hcaptchaField() ?>
```

In order for hCaptcha to work, you need to provide the hCaptcha JavaScript file.

Use the helper function `hcaptchaScript()` in your template.

**Example**

```html+php
<form action="<?= $page->url() ?>" method="post">
    <label for="name" class="required">Name</label>
    <input<?php if ($form->error('name')): ?> class="erroneous"<?php endif; ?> name="name" type="text" value="<?= $form->old('name') ?>">

    <!-- ... -->

    <?= csrf_field() ?>
    <?= hcaptchaField() ?>
    <input type="submit" value="Submit">
</form>
<?= hcaptchaScript() ?>
```

### Controller

In your controller you can use the [magic method](https://kirby-uniform.readthedocs.io/en/latest/guards/guards/#magic-methods) `hcaptchaGuard()` to enable the hCaptcha guard:

```php
$form = new Form(/* ... */);

if ($kirby->request()->is('POST'))
{
    $form->hcaptchaGuard()
         ->emailAction(/* ... */);
}
```

## Credits

- Thanks to Lukas Dürrenberger for the [Uniform reCAPTCHA Guard Plugin](https://github.com/eXpl0it3r/kirby-uniform-recaptcha)
