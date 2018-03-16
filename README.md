# vccw-team/wp-cli-scaffold-movefile

[![Build Status](https://travis-ci.org/vccw-team/wp-cli-scaffold-movefile.svg?branch=master)](https://travis-ci.org/vccw-team/wp-cli-scaffold-movefile)

Get informations as YAML format for [Wordmove's movefile.yml](http://welaika.github.io/wordmove/) from WordPress.

**Note:** We changed the package name because the package index for package command is being deprecated. See [installing](#installing).


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
$ wp package install vccw-team/wp-cli-scaffold-movefile
```

We changed the package name because the package index for package command is being deprecated.
https://make.wordpress.org/cli/2017/07/18/feature-development-discussion-recap/

So, if you are using this this command with the old name, please uninstall the package and install again with the new name.

```
$ wp package uninstall vccw/wp-cli-scaffold-movefile
$ wp package install vccw-team/wp-cli-scaffold-movefile
```

## Customize a default template

You can customize a default template of the movefile.yml.

```
$ curl -o ~/.wp-cli/movefile.mustache https://github.com/vccw-team/wp-cli-scaffold-movefile/blob/master/templates/movefile.mustache
```

Then edit `~/.wp-cli/movefile.mustache`.

## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/vccw-team/wp-cli-scaffold-movefile/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.
