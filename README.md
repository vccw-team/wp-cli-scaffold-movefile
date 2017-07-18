# vccw/wp-cli-scaffold-movefile

[![Build Status](https://travis-ci.org/vccw-team/wp-cli-scaffold-movefile.svg?branch=master)](https://travis-ci.org/vccw-team/wp-cli-scaffold-movefile)

Get informations as YAML format for [Wordmove's Movefile](http://welaika.github.io/wordmove/) from WordPress.

```
$ wp scaffold movefile
```

For more details:

```
$ wp help scaffold movefile
```

Quick links: [Installing](#installing) | [Contributing](#contributing)

## Installing

Installing this package requires WP-CLI v0.23.0 or greater.  Update to the latest stable release with:

```
$ wp cli update
```

Once you've done so, you can install this package with:

```
$ wp package install vccw/wp-cli-scaffold-movefile:@stable
```

## Customize a default template

You can customize a default template of the Movefile.

```
$ curl -o ~/.wp-cli/Movefile.mustache https://github.com/vccw-team/wp-cli-scaffold-movefile/blob/master/templates/Movefile.mustache
```

Then edit `~/.wp-cli/Movefile.mustache`.

## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/vccw-team/wp-cli-scaffold-movefile/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.
