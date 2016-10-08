<?php
/**
 * @package   Quick Contact Element for Zoo
 * @author    Дмитрий Васюков http://fictionlabs.ru
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$link = $this->app->link(array('task' => 'callelement', 'format' => 'raw', 'item_id' => $this->_item->id, 'element' => $this->identifier), false);
?>

<a class="fancybox" href="#userformsend"><span class="uk-button uk-button-primary jsFavoriteToggle" title="В избранное!">
           <i class="uk-icon-comment"></i> Написать продавцу
        </span></a>
<div id="userformsend" style="display:none">
<div id="<?php echo $this->identifier ?>">
	<div class="uk-alert" style="display: none;"></div>
	<form id="fl-formuser" class="uk-form uk-margin-bottom">
	    <div class="uk-form-row">
		    <div class="uk-form-icon">
				<i class="uk-icon-user"></i>
					<input class="flquickcontactuser-name uk-form-width-medium" type="text" name="<?php echo $this->identifier ?>-phone" value="" placeholder="Ваше имя">
			</div>
		</div>
		<div class="uk-form-row">
		    <div class="uk-form-icon">
				<i class="uk-icon-at"></i>
				<input class="flquickcontactuser-email uk-form-width-medium" type="email" name="<?php echo $this->identifier ?>-email" value="" placeholder="Email">
			</div>
		</div>
		<div class="uk-form-row">
		    <div class="uk-form-icon">
				<i class="uk-icon-info"></i>
					<input class="flquickcontactuser-subject uk-form-width-medium" type="text" name="<?php echo $this->identifier ?>-subject" value="" placeholder="Тема сообщения">
			</div>
		</div>
		<div class="uk-form-row">
			<textarea class="flquickcontactuser-text uk-form-width-medium" rows="6" name="text" value="" placeholder="Ваш вопрос пишите тут..."></textarea>
		</div>

		<?php if ($this->config->get('captcha')) : ?>
			<div class="uk-form-row">
				<div class="uk-form-icon">
					<i class="uk-icon-lock"></i>
						<input class="flquickcontactuser-captcha uk-form-width-medium" type="text" name="<?php echo $this->identifier ?>-captcha" value="" placeholder="<?php echo $this->config->get('captcha_question');?>">
				</div>
			</div>
		<?php endif; ?>

		<div class="uk-form-row">
			<button class="flquickcontactuser-submit uk-button uk-button-primary">Отправить сообщение</button>
		</div>
	</form>
</div>
</div>
<script type="text/javascript">
	jQuery(function($) {


$(".fancybox").fancybox({
    openEffect  : 'none',
    closeEffect : 'none',
    afterLoad   : function() {
        this.content.html();
    }
});


		function checkEmail(element) {
			if(element.val() != '') {
				var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		        if(!expr.test(element.val())) {
		            element.removeClass('uk-form-success').addClass('uk-form-danger');
		            return false;
		        } else {
		            element.removeClass('uk-form-danger').addClass('uk-form-success');
		            return true;
		        }
			} else {
				element.removeClass('uk-form-success').addClass('uk-form-danger');
				return false;
			}
		}

		function checkText(element) {
			if(element.val() != '') {
				element.removeClass('uk-form-danger').addClass('uk-form-success');
		        return true;
			} else {
				element.removeClass('uk-form-success').addClass('uk-form-danger');
				return false;
			}
		}

		$('#<?php echo $this->identifier ?> .flquickcontactuser-submit').on( "click", function() {
			var checkemail = checkEmail($('#<?php echo $this->identifier ?> .flquickcontactuser-email'));
			var checktext = checkText($('#<?php echo $this->identifier ?> .flquickcontactuser-text'));
			var captcha = 'none';

			<?php if ($this->config->get('captcha')) : ?>
				var checkcaptcha = checkText($('#<?php echo $this->identifier ?> .flquickcontactuser-captcha'));
				checktext = checktext && checkcaptcha;
				var captcha = $('#<?php echo $this->identifier ?> .flquickcontactuser-captcha').val();
			<?php endif; ?>

			if (checkemail && checktext) {
				jQuery.ajax({
		            type: 'POST',
		           	url: '<?php echo $link; ?>',
		           	data: {method: 'sendEmail', 'args[0]': $('#<?php echo $this->identifier ?> .flquickcontactuser-name').val(), 'args[1]': $('#<?php echo $this->identifier ?> .flquickcontactuser-email').val(), 'args[2]': $('#<?php echo $this->identifier ?> .flquickcontactuser-subject').val(), 'args[3]': $('#<?php echo $this->identifier ?> .flquickcontactuser-text').val(), 'args[4]': captcha},
		           	beforeSend: function(){
		           		$('#<?php echo $this->identifier ?> .uk-alert').removeClass('uk-alert-danger').removeClass('uk-alert-success').hide();
		           		swal({   title: "Отправлено!",   text: "Сообщение успешно отправлено", type: "success",  timer: 2000,   showConfirmButton: false });
	                    $('#<?php echo $this->identifier ?> .flquickcontactuser-submit').prepend('<i class="uk-icon uk-icon-refresh uk-icon-spin"></i> ');
	                    parent.jQuery.fancybox.close();
	                },
		            success: function(data){
		            	var dataObj = $.parseJSON(data);
		            	$('#<?php echo $this->identifier ?> .uk-alert').hide().html(dataObj.mes).fadeIn().delay(5000).fadeOut(500);
		            	if (dataObj.res) {
		            		$('#<?php echo $this->identifier ?> .uk-alert').removeClass('uk-alert-danger').addClass('uk-alert-success');
		            	} else {
		            		$('#<?php echo $this->identifier ?> .uk-alert').addClass('uk-alert-danger').removeClass('uk-alert-success');
		            	}
		            	$('#<?php echo $this->identifier ?> .flquickcontactuser-submit i').fadeOut(function(){
		                    $(this).remove();
		                });
		            }
				})
			}

			return false;
		})
	});
</script>