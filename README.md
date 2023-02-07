# HOME metrics project

This project contains a module for Drupal 9+, that adds endpoints that can be used to keep track of metrics to the HOME project API.

## Quick start

We recommend installing this module via composer with the following steps:
  - add the vcs to your `composer.json` by adding the following to the existing file:
  ```
  {
    ...
    "repositories": [
      ...
  
      {"type": "vcs", "url": "https://github.com/EuropeanUniversityFoundation/home_api_metrics/"},
    ],
    ...
  }  
```
  - Run the `composer require euf/home_api_metrics` command
  - Once installed, enable the module in Drupal on the admin ui or if you have Drush, type `drush en home_api_metrics`

## Entity added
The module creates a `home_api_metrics` entity, that counts calls to the endpoints by year and month.

## Endpoints added
The module adds the following endpoints:
  - `/accommodation/metrics/module/open`: 
    - Accepts POST requests without a body, needs the `use home api metrics endpoints` permission.
    - Counts how many times it was called in a given month and year.
  - `/accommodation/metrics/provider/open/{provider_id}`:
    - Accepts POST requests with an empty body, needs the `use home api metrics endpoints` permission.
    - URL parameter is the id of the provider fetched from the HOME API.
    - Counts how many times it was called in a given month and year with the same provider id.
  - `/accommodation/metrics/module/stats`
    - Accepts GET requests, needs the `access home api metrics statistics endpoint` permission.
    - Returns all the metrics data per year and month, in this case how many times the accommodation module was opened.
  - `/accommodation/metrics/provider/stats`
    - Accepts GET requests, needs the `access home api metrics statistics endpoint` permission.
    - Returns all the metrics data per year and month and provider, in this case how many times any listing of one provider was opened in a given year and month

## Permissions and authentication
The module adds the `use home api metrics endpoints` and `access home api metrics statistics endpoint` permissions, these have to be added to the roles that should be able to call it. The client should take care of authentication. By default, `cookie` authentication is enabled for the endpoints.
