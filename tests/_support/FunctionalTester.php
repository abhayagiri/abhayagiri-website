<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions {
        sendAjaxRequest as parentSendAjaxRequest;
    }

    /**
     * CSRF enabled sendAjaxPostRequest.
     *
     * @see Codeception\Lib\InnerBrowser::sendAjaxPostRequest
     */
    public function sendAjaxPostRequest($uri, $params = [])
    {
        $this->sendAjaxRequest('POST', $uri, $params);
    }

    /**
     * CSRF enabled sendAjaxGetRequest.
     *
     * @see Codeception\Lib\InnerBrowser::sendAjaxGetRequest
     */
    public function sendAjaxGetRequest($uri, $params = [])
    {
        $this->sendAjaxRequest('GET', $uri, $params);
    }

    /**
     * CSRF enabled sendAjaxRequest.
     *
     * @see Codeception\Lib\InnerBrowser::sendAjaxRequest
     */
    public function sendAjaxRequest($method, $uri, $params = [])
    {
        $csrfToken = $this->grabAttributeFrom('meta[name="csrf-token"]', 'content');
        $this->haveHttpHeader('X-CSRF-TOKEN', $csrfToken);
        $this->parentSendAjaxRequest($method, $uri, $params);
    }
}
