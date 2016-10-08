<?php
/**
 * @package   Quick Contact Element for Zoo
 * @author    Дмитрий Васюков http://fictionlabs.ru
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

class Elementflquickcontactuser extends Element {

	public function __construct() {
        parent::__construct();
        $this->registerCallback('sendEmail');
    }

    public function sendEmail($name, $email, $subject, $text, $captcha) {


$admin_email = trim($this->config->get('email'));


// $author = $this->_item->created_by_alias;
//          $user   = $this->app->user->get($this->_item->created_by);

//          if (empty($author) && $user) {
//              $author = $user->name;
//          }

//          echo  $author;

$author_id = $this->_item->created_by;
$userauthor = JFactory::getUser($author_id);
$authoremail = $userauthor->email;
$sitename = JFactory::getApplication()->getCfg('sitename');
$admin_email = $authoremail;


$fullurl = JRoute::_($this->app->route->item($this->_item, false), false, 2);
$itemnameusersend = $this->_item->name;
echo $itemnameusersend;
echo $fullurl;


        $email = trim(htmlentities(strip_tags($email)));
        $name = trim(htmlentities(strip_tags($name)));
        $subject = $subject ? trim(htmlentities(strip_tags($subject))) : 'Письмо обратной связи';
        $text = trim(htmlentities(strip_tags($text)));

        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
        $config->get('mailfrom'),
        $config->get( 'fromname'));
        $mailer->setSender($sender);
        $mailer->addRecipient($admin_email);
        $body   = '<h1>Здравствуйте! Вам пришло письмо с сайта: '.$sitename.' </h1><h2>Наименование: '.$itemnameusersend.' </h2><h5>Со страницы товара: <a href="'.$fullurl.'">'.$fullurl.'</a></h5><h3><div>Данные пользователя: </h3></div><ul><li>Email - '.$email.'</li><li>Имя - '.$name.'</li></ul><h3><div>Сообщение</h3><div>'.$text.'</div>';
        $mailer->setSubject($subject.' - '.$config->get( 'fromname'));
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setBody($body);

        if ($this->config->get('captcha')) {
            if ($captcha == $this->config->get('captcha_answer')) {
                $send = $mailer->Send();
                if ($send !== true) {
                    $result = false;
                    $mes = $this->config->get('error_notice');
                } else {
                    $result = true;
                    $mes = $this->config->get('success_notice');
                }
            } else {
                $result = false;
                $mes = $this->config->get('captcha_error');
            }
        } else {
            $send = $mailer->Send();
            if ($send !== true) {
                $result = false;
                $mes = $this->config->get('error_notice');
            } else {
                $result = true;
                $mes = $this->config->get('success_notice');
            }
        }

        $res = array(
            'res' => $result,
            'mes' => $mes
        );

    	return json_encode($res);
    }

	public function hasValue($params = array()) {
		$value		= $this->get('value', 1);
		return !empty($value);
	}

	public function render($params = array()) {
        if ($layout = $this->getLayout('flquickcontactuser.php')) {
            return $this->renderLayout($layout);
        }

        return null;
	}

	public function edit() {
		return $this->app->html->_('select.booleanlist', $this->getControlName('value'), '', $this->get('value', 1));
	}

}
