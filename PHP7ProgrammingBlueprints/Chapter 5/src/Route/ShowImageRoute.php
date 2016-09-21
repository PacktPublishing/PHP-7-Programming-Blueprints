<?php
namespace Packt\Chp5\Route;

use Helmich\GridFS\BucketInterface;
use Helmich\GridFS\Stream\Psr7\DownloadStreamAdapter;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;

class ShowImageRoute
{
    /** @var BucketInterface */
    private $bucket;

    public function __construct(BucketInterface $bucket)
    {
        $this->bucket = $bucket;
    }

    public function __invoke(Request $req, Response $res, array $args): Response
    {
        $profile     = $req->getAttribute('profile');

        $stream = $this->bucket->openDownloadStreamByName($profile->getUsername());
        $file = $stream->file();
        while(!$stream->eof()) {
            $res->getBody()->write($stream->read(4096));
        }
//        $stream = new DownloadStreamAdapter($stream);

        return $res
            ->withStatus(200)
            ->withHeader('Content-Type', $file['metadata']['content-type'])
//            ->withBody($stream)
        ;
    }

}