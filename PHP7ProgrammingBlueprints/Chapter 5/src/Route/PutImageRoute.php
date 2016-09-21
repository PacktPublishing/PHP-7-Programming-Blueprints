<?php
namespace Packt\Chp5\Route;

use Helmich\GridFS\BucketInterface;
use Helmich\GridFS\Options\UploadOptions;
use Slim\Http\Request;
use Slim\Http\Response;

class PutImageRoute
{
    /** @var BucketInterface */
    private $bucket;

    public function __construct(BucketInterface $bucket)
    {
        $this->bucket = $bucket;
    }

    public function __invoke(Request $req, Response $res, array $args): Response
    {
        if ($req->getHeader('content-type') !== ['image/png'] && $req->getHeader('content-type') !== ['image/jpeg']) {
            return $res
                ->withStatus(415)
                ->withJson(['msg' => 'only PNG and JPEG images are supported']);
        }

        $profile = $req->getAttribute('profile');
        $uploadOptions = new UploadOptions();
        $uploadOptions = $uploadOptions->withMetadata(['content-type' => $req->getHeader('content-type')[0]]);
        $stream = $req->getBody()->detach();

        $id = $this->bucket->uploadFromStream(
            $profile->getUsername(),
            $stream,
            $uploadOptions
        );

        fclose($stream);

        return $res
            ->withStatus(200)
            ->withJson(['msg' => 'image was saved', 'id' => $id]);
    }

}