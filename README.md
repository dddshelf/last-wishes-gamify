Last Wishes Gamification Bounded Context
========================================

This application is an example of a Gamification Bounded Context aligned with the Gamification subdomain, a specialized supporting sudbdomain which handles all the gamification related concerns. This bounded countext exposes a REST API endpoint with two different parts. One exposes all the events published in the bounded context, and the other exposes a simple API to interact directly in terms of the subdomain's ubiquitous language.

This application used as an example in the book **[Domain-Driven Design in PHP by examples](https://leanpub.com/ddd-in-php)** in the *Integrating Bounded Contexts* chapter. It's full eventsourced and it has integration with the **[Last Wishes](https://github.com/dddinphp/last-wishes/)** application via messaging using RabbitMQ.

To run it, just execute the following in the command line

```sh
composer install
php app/console server:run
```

And then, using your browser, access to

**http://127.0.0.1:8000/documentation**

It also has two AMQP consumers: one that listens to the ```Lw\Domain\Model\User\UserRegistered``` event and the other that listens to the ```Lw\Domain\Model\Wish\WishWasMade```. To run the consumer for the ```Lw\Domain\Model\User\UserRegistered``` you should run the following in the command line

```sh
php app/console rabbitmq:consumer last_will_user_registered
```

And to run the consumer for the ```Lw\Domain\Model\Wish\WishWasMade``` just execute the following in the command line

```sh
php app/console rabbitmq:consumer last_will_wish_was_made
```

Enjoy! :)