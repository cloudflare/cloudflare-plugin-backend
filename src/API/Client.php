<?php

namespace CF\API;

use GuzzleHttp\Exception\RequestException;

class Client extends AbstractAPIClient
{
    const CLIENT_API_NAME = 'CLIENT API';
    const ENDPOINT = 'https://api.cloudflare.com/client/v4/';
    const X_AUTH_KEY = 'X-Auth-Key';
    const X_AUTH_EMAIL = 'X-Auth-Email';

    /**
     * @param Request $request
     *
     * @return Request
     */
    public function beforeSend(Request $request)
    {
        $headers = array(
            self::X_AUTH_KEY => $this->data_store->getClientV4APIKey(),
            self::X_AUTH_EMAIL => $this->data_store->getCloudFlareEmail(),
            self::CONTENT_TYPE_KEY => self::APPLICATION_JSON_KEY,
        );
        $request->setHeaders($headers);

        return $request;
    }

    /**
     * @param $message
     *
     * @return array
     */
    public function createAPIError($message)
    {
        $this->logger->error($message);

        return array(
            'result' => null,
            'success' => false,
            'errors' => array(
                array(
                    'code' => '',
                    'message' => $message,
                ),
            ),
            'messages' => array(),
        );
    }

    /**
     * @param RequestException $error
     *
     * @return string
     */
    public function getErrorMessage(RequestException $error)
    {
        $jsonResponse = json_decode($error->getResponse()->getBody(), true);

        if (isset($jsonResponse['errors'][0]['message'])) {
            return $jsonResponse['errors'][0]['message'];
        }

        return $error->getMessage();
    }

    /**
     * @param $response
     *
     * @return bool
     */
    public function responseOk($response)
    {
        return !empty($response['success']) && $response['success'] === true;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return self::ENDPOINT;
    }

    /**
     * @return string
     */
    public function getAPIClientName()
    {
        return self::CLIENT_API_NAME;
    }

    /**
     * GET /zones/:id.
     *
     * @param $zone_tag
     *
     * @return string
     */
    public function zoneGetDetails($zone_tag)
    {
        $request = new Request('GET', 'zones/'.$zone_tag, array(), array());

        return $this->callAPI($request);
    }
}
