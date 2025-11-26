# Uniform Cloudflare Turnstile Guard

A [Kirby 3](https://getkirby.com/) plugin implementing a [Cloudflare Turnstile](https://developers.cloudflare.com/turnstile/) guard for the [Uniform](https://github.com/mzur/kirby-uniform) plugin.

## Installation

### Composer

Add the plugin to your project:

```
composer require anselmh/kirby-uniform-turnstile
```

### Manual install

It is possible to install plugins manually. Download from the Github Releases page, unpack the archive and put it into `site/plugins`.
Please note that this project depends on [Uniform](https://github.com/mzur/kirby-uniform) so it is required to install this package as well.

## Configuration

Set the configuration in your `config.php` file:

```php
return [
  'anselmh.uniform-turnstile.siteKey' => 'my-site-key',
  'anselmh.uniform-turnstile.secretKey' => 'my-secret-key',
  'anselmh.uniform-turnstile.theme' => 'auto(default)|light|dark',
];
```

## Usage

### Template

You can use the provided helper function to embed the Turnstile into your template:

```html+php
<?= turnstileField() ?>
```

In order for turnstile to work, you need to provide the Turnstile JavaScript file.

Use the helper function `turnstileScript()` in your template or add it to the template at right before the closing `</body>` tag.

**Example**

```html+php
<form action="<?= $page->url() ?>" method="post">
    <label for="name" class="required">Name</label>
    <input<?php if ($form->error('name')): ?> class="erroneous"<?php endif; ?> name="name" type="text" value="<?= $form->old('name') ?>">

    <!-- ... -->

    <?= csrf_field() ?>
    <?= turnstileField() ?>
    <input type="submit" value="Submit">
</form>
<?= turnstileScript() ?>
```

### Controller

In your controller you can use the [magic method](https://kirby-uniform.readthedocs.io/en/latest/guards/guards/#magic-methods) `turnstileGuard()` to enable the turnstile guard:

```php
$form = new Form(/* ... */);

if ($kirby->request()->is('POST'))
{
    $form->turnstileGuard()
         ->emailAction(/* ... */);
}
```

## Credits

- Thanks to Lukas Dürrenberger for the [Uniform reCAPTCHA Guard Plugin](https://github.com/eXpl0it3r/kirby-uniform-recaptcha)
- Thanks to Lukas Leitsch for the [Uniform hCaptcha Guard Plugin](https://github.com/lukasleitsch/kirby-uniform-hcaptcha)
