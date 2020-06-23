# Aura Authors Plugin

The **Aura Authors** Plugin for [Grav CMS](https://github.com/getgrav/grav) enables you to store author bios in a centrally managed repository and have them displayed across various pages of your site.

![Aura Authors Plugin for Grav - Demo](assets/demo.jpg)

## Features

* Easily manage author bios via the Grav admin interface
* Central repository ensures that a single change to an author's bio will automatically update it across multiple pages
* Optionally include author's image and links to social media accounts such as Twitter, LinkedIn etc.
* Use the included mobile and desktop responsive styling or provide your own
* Supports `author` taxonomy type for page collections based on author's individual taxonomy label

## Installation

It is recommended to install Aura Authors directly through the Admin Plugin by browsing to the `Plugins` tab and selecting `Add`.

## Configuration

* Enter author details via the Admin Plugin by browsing to `Plugins` > `Aura Authors` and selecting `Add Item`.

* Copy the following code snippet to the relevant Twig template within your theme, at the place where you would like the author bio to be displayed.

```
    {% include 'partials/author-bio.html.twig' ignore missing %}
```

* Once an author is defined at the page level (see Usage below) the relevant author bio will now be displayed on that page (provided it uses the template updated above). Alternatively if you do not have access or do not wish to edit the theme you can include the code snippet directly within a page via the page editor. For this option to work you will need ensure Twig processing is enabled either at the page level (`Page Editor` > `Advanced` > `Overrides` > `Process`) or the site level (`Configuration` > `System` > `Content` > `Process`).

* Optionally customise the layout of the author bio by copying the included file `templates/partials/author-bio.html.twig` into the same location under your theme and editing it. Default styling can be disabled via the Plugin configuration panel if you wish to provide your own.

## Usage

Authors can be selected per page via the page editor, on the `Aura` tab. The list of authors will be automatically populated with author records you create via the Plugins panel.

## Credits

Includes a subset of the [IcoMoon - Free](https://icomoon.io/#icons-icomoon) icon pack.