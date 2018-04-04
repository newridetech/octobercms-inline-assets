# Inline Assets

Optimize assets delivery by inlining files to achieve better PageSpeed score.

Google PageSpeed suggests to inline assets https://developers.google.com/speed/docs/insights/OptimizeCSSDelivery#example and there is no simple way to do so in OctoberCMS (`|theme` filter generates URL for remote assets access).

This plugin provides easy way to include inlined assets in your code:

Instead of:

```html
<link href="{{ [ 'scss/styles.scss' ]|theme }}" rel="stylesheet">
```

Use:
```html
<style>{{ [ 'scss/styles.scss' ]|inline }}</style>
```

And that's all. :)

Standard OctoberCMS's asset minifier is used internally so there are no additional considerations on how to organize your JS, CSS assets (also all OctoberCMS configuration options also apply here: https://octobercms.com/docs/markup/filter-theme). You can just switch from `|theme` to `|inline` without modifying your asset's code.
