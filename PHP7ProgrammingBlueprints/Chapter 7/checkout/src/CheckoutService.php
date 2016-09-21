<?php
namespace Packt\Chp7\Checkout;

use React\Promise\PromiseInterface;

class CheckoutService
{
    /**
     * @var JsonRpcClient
     */
    private $client;

    public function __construct(JsonRpcClient $client)
    {
        $this->client = $client;
    }

    public function handleCheckoutOrder(string $msg): PromiseInterface
    {
        $request = json_decode($msg);
        $promises = [];

        foreach ($request->cart as $article) {
            $promises[] = $this->client->request('checkArticle', [$article->articlenumber, $article->amount]);
        }

        return \React\Promise\all($promises)
            ->then(function(array $values):bool {
                if (array_sum($values) != count($values)) {
                    throw new \Exception('not all articles are in stock');
                }
                return true;
            })->then(function() use ($request):PromiseInterface {
                $promises = [];

                foreach ($request->cart as $article) {
                    $promises[] = $this->client->request('takeArticle', [$article->articlenumber, $article->amount]);
                }

                return \React\Promise\all($promises);
            })->then(function(array $values):bool {
                if (array_sum($values) != count($values)) {
                    throw new \Exception('not all articles are in stock');
                }
                return true;
            });
    }
}