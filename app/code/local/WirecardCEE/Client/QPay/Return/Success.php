<?php
/*
    Die vorliegende Software ist Eigentum von Wirecard CEE und daher vertraulich zu behandeln.
    Jegliche Weitergabe an dritte, in welcher Form auch immer, ist unzulaessig.

    Software & Service Copyright (C) by
    Wirecard Central Eastern Europe GmbH,
    FB-Nr: FN 195599 x, http://www.wirecard.at
*/

require_once 'WirecardCEE/Client/QPay/Return/Abstract.php';

/**
 * container for success return data.
 */
class WirecardCEE_Client_QPay_Return_Success
    extends WirecardCEE_Client_QPay_Return_Abstract
{

    private $_fingerprintOrder = Array();
    private $_secret;
    
    protected $_state = 'SUCCESS';
    
    private static $SECRET = 'secret';

    /**
     * creates an instance of an WirecardCEE_Client_QPay_Return_Success object
     * @param mixed[] $returnData
     * @param string $secret 
     */
    public function __construct(Array $returnData, $secret)
    {
        $this->_secret = strval($secret);
        parent::__construct($returnData);
    }

    /**
     * getter for the return parameter amount
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * getter for the return parameter currency
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * getter for the return parameter paymentType
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * getter for the return parameter financialInstitution
     * @return string
     */
    public function getFinancialInstitution()
    {
        return $this->financialInstitution;
    }

    /**
     * getter for the return parameter Language
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * getter for the return parameter orderNumber
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * getter for the return parameter gatewayReferenceNumber
     * @return string
     */
    public function getGatewayReferenceNumber()
    {
        return $this->gatewayReferenceNumber;
    }

    /**
     * getter for the return parameter gatewayContractNumber
     * @return string
     */
    public function getGatewayContractNumber()
    {
        return $this->gatewayContractNumber;
    }
    
    /**
     * getter for the return parameter avsResponseCode
     * @return string 
     */
    public function getAvsResponseCode()
    {
        return $this->avsResponseCode;
    }
    
    /**
     * getter for the return parameter avsResponseMessage
     * @return string 
     */
    public function getAvsResponseMessage()
    {
        return $this->avsResponseMessage;
    }
    
    /**
     * @see WirecardCEE_Client_QPay_Return_Abstract::validate()
     * @throws WirecardCEE_Client_QPay_Exception
     * @return bool 
     */
    public function validate()
    {
        try
        {
            $this->_fingerprintOrder = $this->_getResponseFingerprintOrder();
            $calcFingerprint = $this->_getCalculatedFingerprint();
            $responseFingerprint = $this->responseFingerprint;
            $this->_compareFingerprints($calcFingerprint, $responseFingerprint);
        }
        catch (WirecardCEE_Client_QPay_Exception $e)
        {
            throw new WirecardCEE_Client_QPay_Exception($e->getMessage(), $e->getCode(), $e);
        }
        return true;
    }
    
    private function _getResponseFingerprintOrder()
    {
        if($this->responseFingerprintOrder)
        {
            $fingerprintOrderArray = explode(',', $this->responseFingerprintOrder);
            if(!in_array(self::$SECRET, $fingerprintOrderArray))
            {
                require_once 'WirecardCEE/Client/QPay/Exception.php';
                throw new WirecardCEE_Client_QPay_Exception('Parameter responseFingerprintOrder is invalid. Secret is missing');
            }
        }
        else
        {
            require_once 'WirecardCEE/Client/QPay/Exception.php';
            throw new WirecardCEE_Client_QPay_Exception('Parameter responseFingerprintOrder has not been returned.');
        }
        return $fingerprintOrderArray;
    }
    
    private function _getCalculatedFingerprint()
    {
        $responseFingerprintOrder = $this->_fingerprintOrder;
        $responseFingerprintData = $this->_getFingerprintFields($responseFingerprintOrder);
        require_once 'WirecardCEE/Fingerprint.php';
        return WirecardCEE_Fingerprint::generate($responseFingerprintData, $responseFingerprintOrder);
    }
    
    private function _compareFingerprints($calcFingerprint, $responseFingerprint)
    {
        $responseFingerprintOrder = $this->_fingerprintOrder;
        $responseFingerprintData = $this->_getFingerprintFields($responseFingerprintOrder);
        if(!WirecardCEE_Fingerprint::compare($responseFingerprintData, $responseFingerprintOrder, $responseFingerprint));
        if($responseFingerprint != $calcFingerprint)
        {
            require_once 'WirecardCEE/Client/QPay/Exception.php';
            throw new WirecardCEE_Client_QPay_Exception('Fingerprints do not match. calc: ' . $calcFingerprint . ' resp: ' . $responseFingerprint);
        }
        else 
        {
            return true;
        }
        
    }

    /**
     * check if magic quotes gpc or magic quotes runtime are enabled
     * @return bool
     */
    private function _magicQuotesUsed()
    {
        if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function _prepareValueForFingerprint($value)
    {
        if($this->_magicQuotesUsed())
        {
            return stripslashes($value);
        }
        else
        {
            return $value;
        }
    }
    
    protected function _getFingerprintFields($fingerprintOrder)
    {
        $fingerprintData = Array();
        foreach($fingerprintOrder AS $fingerprintKey)
        {
            $fingerprintData[$fingerprintKey] = $this->_prepareValueForFingerprint($this->_getField($fingerprintKey));
        }
        return $fingerprintData;
    }
    
    protected function _getField($name)
    {
        if($name == self::$SECRET)
        {
            if($this->_secret != '')
            {
                return $this->_secret;
            }
            else
            {
                require_once 'WirecardCEE/Client/QPay/Exception.php';
                throw new WirecardCEE_Client_QPay_Exception('Secret is empty.');
            }
        }
        else 
        {
            return $this->$name;
        }
    }
}
