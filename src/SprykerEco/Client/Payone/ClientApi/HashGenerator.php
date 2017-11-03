<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi;

use SprykerEco\Client\Payone\ClientApi\Request\AbstractRequest;
use SprykerEco\Shared\Payone\Dependency\HashInterface;

class HashGenerator implements HashGeneratorInterface
{
    /**
     * @var \SprykerEco\Shared\Payone\Dependency\HashInterface
     */
    protected $hashProvider;

    /**
     * @var array
     */
    protected $hashParameters = [
        'mid',
        'amount',
        'productid',
        'aid',
        'currency',
        'accessname',
        'portalid',
        'due_time',
        'accesscode',
        'mode',
        'storecarddata',
        'access_expiretime',
        'request',
        'checktype',
        'access_canceltime',
        'responsetype',
        'addresschecktype',
        'access_starttime',
        'reference',
        'consumerscoretype',
        'access_period',
        'userid',
        'invoiceid',
        'access_aboperiod',
        'customerid',
        'invoiceappendix',
        'access_price',
        'param',
        'invoice_deliverymode',
        'access_aboprice',
        'narrative_text',
        'eci',
        'access_vat',
        'successurl',
        'settleperiod',
        'errorurl',
        'settletime',
        'backurl',
        'vaccountname',
        'exiturl',
        'vreference',
        'clearingtype',
        'encoding',
    ];

    /**
     * @param \SprykerEco\Shared\Payone\Dependency\HashInterface $hashProvider
     */
    public function __construct(HashInterface $hashProvider)
    {
        $this->hashProvider = $hashProvider;
    }

    /**
     * @param Request\AbstractRequest $request
     * @param string $securityKey
     *
     * @return string
     */
    public function generateHash(AbstractRequest $request, $securityKey)
    {
        $hashString = '';
        $requestData = $request->toArray();
        sort($this->hashParameters);
        foreach ($this->hashParameters as $key) {
            if (!array_key_exists($key, $requestData)) {
                continue;
            }
            $hashString .= $requestData[$key];
        }
        $hashString .= $securityKey;

        return $this->hashProvider->hash($hashString);
    }
}
