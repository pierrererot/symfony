<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 10/07/2018
 * Time: 10:33
 */

$params =
    [
        "clientSourceReference" => "BOUY4",
        "orders" => [
            [
                "sourceReference" => uniqid("ref"),
                "internalReference" => "TESTTEST-01",
                "externalReference" => "CCC",
                "productListName" => "productListName",
                "lib" => "azdad",
                "comCity" => "azdad",
                "comCountry" => "azdad",
                "comPostcode" => "azdad",
                "comClecom" => "azdad",
                "affaire" => "azdad",
                "operationId" => "2",
                "operationTypeId" => "3",
                "agencyId" => "5",
                "statusId" => "4",
                "initialSite" => [
                    "label"=>"testINIT",
                    "recipient1"=>"aaa",
                    "recipient2"=>'',
                    "recipient3"=>'',
                    "street1"=>"la playa",
                    "street2"=>'',
                    "street3"=>'',
                    "postcode"=>"ICI",
                    "city"=>"EHLA",
                    "country"=>"FR",
                    "contactPhoneNumber"=>123456789,
                    "contactFullName"=>"INITIAL",
                ],
                "finalSite" => [
                    "label"=>"testFINAL",
                    "recipient1"=>"aaa",
                    "recipient2"=>'bbbb',
                    "recipient3"=>'ccc',
                    "street1"=>"aaaa",
                    "street2"=>'bbbb',
                    "street3"=>'ccccc',
                    "postcode"=>"ICI",
                    "city"=>"EHLA",
                    "country"=>"FR",
                    "contactPhoneNumber"=>123456789,
                    "contactFullName"=>"FINAL",
                ],
                "internalContactPhoneNumber"=>1111111111,
                "internalContactFullName"=>"AltéAd",
                "externalContactPhoneNumber"=>2222222222,
                "externalContactFullName"=>"Client",
                "steps" => [
                    [
                         "sourceReference" =>"TEST-01",
                         "agency" => "aaa",
                         "arrivingAt" => "aaa",
                         "leavingAt" =>"aaa",
                            "checkpoint" => [
                            "label"=>"test",
                            "recipient1"=>"aaa",
                            "recipient2"=>'',
                            "recipient3"=>'',
                            "street1"=>"la playa",
                            "street2"=>'',
                            "street3"=>'',
                            "postcode"=>"ICI",
                            "city"=>"EHLA",
                            "country"=>"FR",
                            "contactPhoneNumber"=>123456789,
                            "contactFullName"=>"FullNAME",
                        ],
                    ],
                    [
                        "sourceReference"=>"zzzzzzzz",
                        "agency"=>"cbb",
                        "arrivingAt"=>"aaa",
                        "leavingAt"=>"aaa",
                        "checkpoint" => [
                            "label"=>"test",
                            "recipient1"=>"aaa",
                            "recipient2"=>'',
                            "recipient3"=>'',
                            "street1"=>"la playa",
                            "street2"=>'',
                            "street3"=>'',
                            "postcode"=>"ICI",
                            "city"=>"EHLA",
                            "country"=>"FR",
                            "contactPhoneNumber"=>123456789,
                            "contactFullName"=>"FullNAME",
                        ]
                    ]
                ],
                "products" => [
                    [
                        "quantity" => 3,
                        "serialNumber" => 'serial azeaze',
                        "weightDimensions" => '25aaa'
                    ],
                    [
                        "quantity" => 2,
                        "serialNumber" => 'serial 2222',
                        "weightDimensions" => 'a4846648adz'
                    ],
                ]
            ]
        ]
    ];


$client = new SoapClient("public/client.wsdl", ['trace'=>1]);

try {
    $result = $client->__soapCall("updateOrders",$params);
}catch (SoapFault $exception){
    echo $client->__getLastResponse();
    var_dump($exception->getTraceAsString());
    throw $exception;
}


var_dump($result);