<?php

namespace AmeshExtensions\PincodeChecker\Model\PincodeDataProvider;

use Magento\Framework\Serialize\Serializer\Json;

class Api {

    const API_URL = 'http://postalpincode.in/api/pincode/';

    protected $json;

    public function __construct(Json $json) {
        $this->json = $json;
    }

    public function getPincodeDetails($pincode)
    {
        $pincodeDetails = file_get_contents(self::API_URL.$pincode);
        if ($pincodeDetails != '') {
            $pincodeData = $this->json->unserialize($pincodeDetails);
            if ($pincodeData['Status'] == "Success" && isset($pincodeData['PostOffice'])) {
                $pincodeInfo = $pincodeData['PostOffice'][0];
                return [
                    'block' => $pincodeInfo['Circle'],
                    'division' => $pincodeInfo['Division'],
                    'district' => $pincodeInfo['District'],
                    'state' => $pincodeInfo['State']
                ];
            }
        }
        return [
            'block' => '',
            'division' => '',
            'district' => '',
            'state' => ''
        ];
    }
}