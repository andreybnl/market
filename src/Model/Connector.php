<?php

namespace App\Model;

use App\Repository\SupplierRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Connector
{
    private $client;
    private $container;
    private $supplierRepository;

    public function __construct(
        SupplierRepository $supplierRepository,
        HttpClientInterface $client,
        ContainerInterface $container
    )
    {
        $this->supplierRepository = $supplierRepository;
        $this->client = $client;
        $this->container = $container;
    }

    public function getToken($supplierName)
    {
        try {
            $accessToken = null;
            $supplier = $this->supplierRepository->findOneBy(['name' => $supplierName]);
            if ($supplier && ($supplier->getOauth2Key() || $supplier->getGrantType())) {
                if (!($supplier->getTokenCreated()) ||
                    (((new \DateTime('now'))->getTimestamp()) - ($supplier->getTokenCreated()->getTimestamp()) > 3500)) {
                    $json_prep = array("grant_type" => $supplier->getGrantType(), "username" => $supplier->getUser(),
                        "password" => $supplier->getPassword());
                    $add = array(
                        "headers" => [
                            "Authorization" => "Basic " . $supplier->getOauth2Key()],
                        "body" => http_build_query($json_prep),
                        'verify_peer' => false, 'verify_host' => false           //remove when cert will be!
                    );
                    $token_response = $this->client->request(
                        'POST',
                        $supplier->getTokenUrl(), $add
                    );
                    //permanent white ip should be for real-server!!!
                    if ($token_response->getStatusCode() == '200') {
                        $accessToken = json_decode($token_response->getContent(false))->access_token;
                        $supplier->setToken($accessToken);
                        $now = new \DateTime('now');
                        $now->format('Y-m-d H:i:s');
                        $supplier->setTokenCreated($now);
                        $entityManager = $this->container->get('doctrine')->getManager();
                        $entityManager->flush();
                        return $accessToken;
                    }
                } else {
                    return $supplier->getToken();
                }
            }
        } catch (\exception $e) {
        }
        return $accessToken;
    }

    public function getContent($accessToken, $supplierName, $retry, $request, $type = 'GET', $body = NULL,
                               $header = "Content-Version: 1.1")
    {
        $response = NULL;
        if ($header == 'Content-Version: 1.1' && $type == ('PATCH' || 'POST' || 'PUT')) {
            $header = "Content-type: application/json";
            $retry = 1;
        }
        try {
            $body = str_replace("\r\n", NULL, $body);
            $body = str_replace("\n", NULL, $body);
            $body = str_replace(" ", '', $body);
            $supplier = $this->supplierRepository->findOneBy(['name' => $supplierName]);
            if ($accessToken) {
                $headers = array(
                    'auth_bearer' => $accessToken,
                    'headers' => array($header),
                    'body' => $body,
                    'timeout' => $supplier->getDelay(),
                    'verify_peer' => false, 'verify_host' => false              //remove when cert will be!
                );
            } else {
                $headers = array(
                    'auth_basic' => array("username" => $supplier->getUser(), "password" => $supplier->getPassword()),
                    'headers' => array($header),
                    'body' => $body,
                    'timeout' => $supplier->getDelay()
                );
            }

            for ($i = 0; $i < $retry; $i++) {
                $response = $this->client->request(
                    $type,
                    $supplier->getApiUrl() . $request, $headers
                );
                if ($response->getStatusCode()) {
                    break;
                }
            }
        } catch (\Exception $e) {
        }
        return $response;
    }
}