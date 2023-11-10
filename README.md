# HOME metrics project

This project contains a module for Drupal 9+. It adds endpoints that can be used to keep track of metrics to the HOME project API.

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
  - Run the `composer require euf/home_api_metrics:dev-master` command
  - Once installed, enable the module in Drupal on the admin ui or if you have Drush, type `drush en home_api_metrics`

## Entity added
The module creates a `home_api_metrics` entity, that counts calls to the endpoints by year and month.

## Endpoints added
The module adds the following endpoints:
  - `/api/accommodation/metrics/module/open`:
    - Accepts POST requests without a body, needs the `use home api metrics endpoints` permission.
    - Counts how many times it was called in a given month and year. Year and month is calculated from the time of the request.
    - For the metrics to work properly, ALL AUTHENTICATED users have to have the `use home api metrics endpoints`
  - `/api/accommodation/metrics/provider/open/{provider_name}`:
    - Accepts POST requests with an empty body, needs the `use home api metrics endpoints` permission.
    - {provider_name} parameter should be a url compatible name of the provider fetched from the HOME API.
    - Counts how many occasions it was called in a given month and year with the same provider name. Year and month is calculated from the time of the request.
    - For the metrics to work properly, ALL AUTHENTICATED users have to have the `use home api metrics endpoints`
  - `/api/accommodation/metrics/module/stats`
    - Accepts GET requests, needs the `access home api metrics statistics endpoint` permission.
    - Returns all the metrics data per year and month, in this case how many times the accommodation module was opened.
  - `/api/accommodation/metrics/provider/stats`
    - Accepts GET requests, needs the `access home api metrics statistics endpoint` permission.
    - Returns all the metrics data per year and month and provider, in this case how many times any listing of one provider was opened in a given year and month.

## Permissions and authentication
The module adds the `use home api metrics endpoints`, the `access home api metrics statistics endpoint`, the `administer home api metrics` and `access home api metrics overview` permissions. The client should take care of authentication. By default, `cookie` authentication is enabled for the endpoints.
  - `use home api metrics endpoints` has to be granted for ALL AUTHENTICATED users for the metrics to work properly, because of the way the E+ App frontend works.
  - `access home api metrics statistics endpoint` should be added to people / systems that should be able to see the metrics via the endpoints exposing them.
  - `administer home api metrics` enables every operation possible on the metrics entites.
  - `access home api metrics overview` enables the owner of the permission to see the list of metrics data.
