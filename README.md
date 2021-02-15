# Directus Seo Plugin

**The Plugin gets SEO informations from a directus headless CMS and provides it as a twig variable in the templates**

The **Directus Seo** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). adds SEO configuration from directus headless cms

## Installation

Installing the Directus Seo plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install directus-seo

This will install the Directus Seo plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/directus-seo`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `directus-seo`. You can find these files on [GitHub](https://github.com//grav-plugin-directus-seo) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/directus-seo
	
> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com//grav-plugin-directus-seo/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/directus-seo/directus-seo.yaml` to `user/config/plugins/directus-seo.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
seo_table: zbr_seo
seo_slugField: slug
```

seo_table - the table with the SEO metadata

seo_slugField - the field with the page route

Note that if you use the Admin Plugin, a file with your configuration named directus-seo.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

**After installing the plugin, at the first Page Request in GRAV it lookups in a Directus CMS table for Metadata like Opengraph informations. It generates a seo.json and saves it to the page folder in GRAV. If there are metadata informations, the data is available in twig as the variable "directus_seo"**

## CLI commands

```
Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       Use environment configuration (defaults to localhost)
      --lang[=LANG]     Language to be used (defaults to en)
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  dump  Dumps the SEO metadata informations
  help  Displays help for a command
  list  Lists commands
```


