<?php
class SalesXp_Test_Model_Observer
{
    public function inspectCustomerRegistrationData($observer = null)
    {
        $event = $observer->getEvent();
        $controllerAction = $event->getControllerAction();
        Mage::log($controllerAction->getRequest()->getParams());
    }
}