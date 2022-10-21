# HOME metrics project

This project contains a module for Drupal 8+, that adds endpoints that can be used to keep track of metrics to the HOME project API.

## Quick start

In order to install this module, you have to:
  - download the files and add them in your site's `web/modules/custom/home_api_metrics` directory
  - or install it via composer `composer require euf/home_api_metrics`

Once installed, enable the module in Drupal on the admin ui or if you have Drush, type `drush en home_api_metrics`

## Entity added
The module creates a `home_api_metrics ` entity, that counts calls to the endpoints by year and month

## Endpoints added
The module adds two endpoints:
  - /accomodation/open: Accepts GET requests without parameters, needs the `use home_api_metrics` permission.
    Counts how many times it was called in a given month
  - /accomodation/details: Accepts GET requests with one string query parameter: `group`.
    Counts how many times it was called in a given month with the same parameter value.

## Permissions and authentication
The module adds the `use home_api_metrics` permission, that has to be added to the roles that should be able to call it. Of course, being an api endpoint, the client should take care of authentication. By default, `cookies` and `api_key` authentication types are enabled for the module.
