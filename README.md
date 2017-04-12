discounts-api
=============

Regarding the products where you receive the sixth free, the calculations for the number of free products is made for each item, to avoid the problem of choosing between different items.

I decided not to use a REST approach because the main request to retrieve the discount associated with the order did no qualify in the CRUD purist concept of REST.
The objective was to GET the discount, but the request should receive a JSON order, which could not go in the body to follow the REST rules of GET. A POST can solve the problem of allowing the body but again, abiding the rules of REST it would not follow the principle that POST should create data.
For this reasons, the approach chosen was to use a POST, leaving REST out of the equation.

To run the test in OrderControllerTest the application must be running on localhost.

To use the application the following bundles should be installed:
* "guzzlehttp/guzzle"
* "nelmio/api-doc-bundle"
* "phpunit/phpunit" (There is a compatibility issue with symfony/phpunit-bridge, recommend removal)

The API documentation can be access by domain/doc, Nelmio API Doc must be configured.
In config.yml

``` yml
nelmio_api_doc:
    sandbox:
        accept_type:        "application/json"
        body_format:
            formats:        [ "json" ]
            default_format: "json"
        request_format:
            formats:
                json:       "application/json"
```
In routing.yml
```yml
NelmioApiDocBundle:
    resource: "@NelmioApiDocBundle/Resources/config/routing.yml"
    prefix: "/doc"
```

In security.yml
```
security:
    providers:
        in_memory:
            memory: ~
    firewalls:
        api:
            pattern: ^/                                # All URLs are protected
            stateless: true                            # Do no set session cookies
            anonymous: true                           # Anonymous access is allowed
        api_docs:
            pattern: ^/doc
            anonymous: true
```
