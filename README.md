# Uniform Cloudflare Turnstile Guard

[![Tests](https://github.com/anselmh/kirby-uniform-turnstile/actions/workflows/tests.yml/badge.svg)](https://github.com/anselmh/kirby-uniform-turnstile/actions/workflows/tests.yml)

A [Kirby](https://getkirby.com/) plugin implementing a [Cloudflare Turnstile](https://developers.cloudflare.com/turnstile/) guard for the [Uniform](https://github.com/mzur/kirby-uniform) plugin.

**Requirements:** Kirby 5+, PHP 8.4+, Uniform 5.7+.

## Installation

### Composer

Add the plugin to your project:

```
composer require anselmh/kirby-uniform-turnstile
```

### Manual install

It is possible to install plugins manually. Download from the Github Releases page, unpack the archive and put it into `site/plugins`.
The plugin folder should be named `uniform-turnstile`.
Please note that this project depends on [Uniform](https://github.com/mzur/kirby-uniform) so it is required to install this package as well.

## Configuration

Set the configuration in your `config.php` file:

```php
return [
  'anselmh.uniform-turnstile.siteKey' => 'my-site-key',
  'anselmh.uniform-turnstile.secretKey' => 'my-secret-key',
  'anselmh.uniform-turnstile.theme' => 'auto', // auto (default), light or dark
  'anselmh.uniform-turnstile.timeout' => 10, // seconds to wait for Cloudflare (default)
];
```

## Usage

### Template

You can use the provided helper function to embed the Turnstile into your template:

```html+php
<?= turnstileField() ?>
```

`turnstileField()` accepts optional widget attributes, rendered as `data-*` attributes, e.g. `turnstileField(['action' => 'contact', 'theme' => 'dark', 'language' => 'de'])`.

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

To also require that the token was issued for a specific widget `action`, pass it as an option:

```php
$form->turnstileGuard(['action' => 'contact']);
```

## Credits

- Thanks to Lukas Dürrenberger for the [Uniform reCAPTCHA Guard Plugin](https://github.com/eXpl0it3r/kirby-uniform-recaptcha)
- Thanks to Lukas Leitsch for the [Uniform hCaptcha Guard Plugin](https://github.com/lukasleitsch/kirby-uniform-hcaptcha)

## Development

```
composer install
composer test
```
