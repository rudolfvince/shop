<?php
/*
    Die vorliegende Software ist Eigentum von Wirecard CEE und daher vertraulich zu behandeln.
    Jegliche Weitergabe an dritte, in welcher Form auch immer, ist unzulaessig.

    Software & Service Copyright (C) by
    Wirecard Central Eastern Europe GmbH,
    FB-Nr: FN 195599 x, http://www.wirecard.at
*/

require_once 'WirecardCEE/Client/QPay/Response/Toolkit/FinancialObject.php';

/**
 * Payment-object returned by an {@link WirecardCEE_Client_QPay_Request_Toolkit::getOrderDetails()} operation.
 */
class WirecardCEE_Client_QPay_Response_Toolkit_Order_Payment
    extends WirecardCEE_Client_QPay_Response_Toolkit_FinancialObject
{
    private static $MERCHANT_NUMBER = 'merchantNumber';
    private static $PAYMENT_NUMBER = 'paymentNumber';
    private static $ORDER_NUMBER = 'orderNumber';
    private static $APPROVE_AMOUNT = 'approveAmount';
    private static $DEPOSIT_AMOUNT = 'depositAmount';
    private static $CURRENCY = 'currency';
    private static $TIME_CREATED = 'timeCreated';
    private static $TIME_MODIFIED = 'timeModified';
    private static $STATE = 'state';
    private static $PAYMENT_TYPE = 'paymentType';
    private static $OPERATIONS_ALLOWED = 'operationsAllowed';
    private static $GATEWAY_REFERENCE_NUMBER = 'gatewayReferenceNumber';
    private static $AVS_RESULT_CODE = 'avsResultCode';
    private static $AVS_RESULT_MESSAGE = 'avsResultMessage';
    
    /**
     * creates an instance of an {@link WirecardCEE_Client_QPay_Response_Toolkit_Order_Payment} object
     * @param string[] $paymentData
     */
    public function __construct($paymentData)
    {
        $this->_data = $paymentData;
    }
    
    /**
     * getter for payments merchant number
     * @return string
     */
    public function getMerchantNumber()
    {
        return $this->_getField(self::$MERCHANT_NUMBER);
    }
    
    /**
     * getter for the payment number
     * @return string
     */
    public function getPaymentNumber()
    {
        return $this->_getField(self::$PAYMENT_NUMBER);
    }
    
    /**
     * getter for the corrensponding order number
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->_getField(self::$ORDER_NUMBER);
    }
    
    /**
     * getter for the approved amount
     * @return string
     */
    public function getApproveAmount()
    {
        return $this->_getField(self::$APPROVE_AMOUNT);
    }
    
    /**
     * getter for the deposited amount
     * @return string
     */
    public function getDepositAmount()
    {
        return $this->_getField(self::$DEPOSIT_AMOUNT);
    }
    
    /**
     * getter for the payment currency
     * @return string
     */
    public function getCurrency()
    {
        return $this->_getField(self::$CURRENCY);
    }
    
    /**
     * getter for the creation time of this payment
     * @return DateTime 
     */
    public function getTimeCreated()
    {
        return DateTime::createFromFormat(self::$DATETIME_FORMAT, $this->_getField(self::$TIME_CREATED));
    }
    
    /**
     * getter for the last time this payment has been updated
     * @return DateTime 
     */
    public function getTimeModified()
    {
        return DateTime::createFromFormat(self::$DATETIME_FORMAT, $this->_getField(self::$TIME_MODIFIED));
    }
    
    /**
     * getter for the current payment state
     * @return string
     */
    public function getState()
    {
        return $this->_getField(self::$STATE);
    }
    
    /**
     * getter for the paymenttype
     * @return string
     */
    public function getPaymentType()
    {
        return $this->_getField(self::$PAYMENT_TYPE);
    }
    
    /**
     * getter for the allowed follow-up operations
     * @return string[]
     */
    public function getOperationsAllowed()
    {
        return explode(',', $this->_getField(self::$OPERATIONS_ALLOWED));
    }
    
    /**
     * getter for the gateway reference number
     * @return string
     */
    public function getGatewayReferencenumber()
    {
        return $this->_getField(self::$GATEWAY_REFERENCE_NUMBER);
    }
    
    /**
     * getter for the AVS result-code
     * @return string
     */
    public function getAvsResultCode()
    {
        return $this->_getField(self::$AVS_RESULT_CODE);
    }
    
    /**
     * getter for the AVS result-message
     * @return string
     */
    public function getAvsResultMessage()
    {
        return $this->_getField(self::$AVS_RESULT_MESSAGE);
    }
}
