<?php
declare(strict_types = 1);

use Helmich\GridFS\Bucket;
use Helmich\GridFS\Options\BucketOptions;
use MongoDB\Database;
use MongoDB\Driver\Manager;
use Packt\Chp5\Middleware\AuthenticationMiddleware;
use Packt\Chp5\Middleware\ProfileMiddleware;
use Packt\Chp5\Route\DeleteProfileRoute;
use Packt\Chp5\Route\ListProfileRoute;
use Packt\Chp5\Route\PutImageRoute;
use Packt\Chp5\Route\PutProfileRoute;
use Packt\Chp5\Route\ShowImageRoute;
use Packt\Chp5\Route\ShowProfileRoute;
use Packt\Chp5\Service\ProfileService;
use Slim\App;

require 'vendor/autoload.php';

$manager    = new Manager('mongodb://db:27017');
$database   = new Database($manager, $database);
$collection = $database->selectCollection('profiles');

$profileService = new ProfileService($this->collection);
$bucket = new Bucket($this->database, (new BucketOptions())->withBucketName('profileImages'));

$app = new App();
$app->get('/profiles', new ListProfileRoute($profileService));
$app->get('/profiles/{username}', new ShowProfileRoute($profileService))
    ->add(new ProfileMiddleware(true, $profileService));
$app->put('/profiles/{username}', new PutProfileRoute($profileService))
    ->add(new AuthenticationMiddleware($profileService))
    ->add(new ProfileMiddleware(false, $profileService));
$app->get('/profiles/{username}/image', new ShowImageRoute($bucket))
    ->add(new ProfileMiddleware(true, $profileService));
$app->put('/profiles/{username}/image', new PutImageRoute($bucket))
    ->add(new AuthenticationMiddleware($profileService))
    ->add(new ProfileMiddleware(true, $profileService));
$app->delete('/profiles/{username}', new DeleteProfileRoute($profileService))
    ->add(new AuthenticationMiddleware($profileService))
    ->add(new ProfileMiddleware(true, $profileService));
$app->run();