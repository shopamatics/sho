<?php if(($_POST['name']&&$_POST['phone'])||$_POST['com']||$_POST['email']):?>
<?php

    require 'api.php';
	if(isset($_POST['phone']))
		$_POST["Order"]['phone'] = $_POST['phone'];

	if(isset($_POST['name']))
		$_POST["Order"]['fio'] = $_POST['name'];

	if(isset($_POST['com']))
		$_POST["Order"]['com'] = $_POST['com'];
	
	if(isset($_POST['email']))
		$_POST["Order"]['email'] = $_POST['email'];
	
  // устанавливаем API-HASH рекламодателя
    $api = new Api("14d9f4f2e8c741a3ca18b8685591049e");

    $hash = $_GET["hash"];
    $flow = $_GET['flow'];
    $url = $_POST['url'];
    if($hash)
        $api->set_hash($hash);
    else{
        $api->set_flow_by_data($flow);
        $api->set_page_by_data($url);
    }
	
if (isset($_POST["Order"]['phone']) and ( $_POST["Order"]['phone'] !== '') ) {
	$api->getInfo();
    $ya_metrika = $api->getYaMetrikaId();
    //Отправляем информацию о заказе
    $api->set_dop_info($_POST["Order"]);
    // if (isset($_POST["Order"]['com']) && $_POST["Order"]['com'] !== '')
    //     $api->set_comment($_POST["Order"]['com']);
    $api->insertRequest();
}
if (isset($_POST["Order"]['com'])||isset($_POST["Order"]['email'])){
    //Отправляем информацию о заказе
    if(isset($_POST["Order"]['com'])){
        $api->set_comment($_POST["Order"]['com']);
        $api->insertComment();
    }
    if(isset($_POST["Order"]['email'])){
        $api->set_email($_POST["Order"]['email']);
        $api->insertEmail();
    }   
}
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="vendor/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="vendor/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="vendor/jquery.min.js"></script>
    <script type="text/javascript" src="vendor/jquery.placeholder.js"></script>
    <script type="text/javascript" src="vendor/init.js"></script>
	<script src="vendor/bootstrap.min.js"></script>
	<link rel="stylesheet" href="vendor/bootstrap.min.css">
	<title>Поздравляем! Ваш заказ принят!</title>
    <script src="vendor/jquery.js" type="text/javascript"></script>
    <script src="vendor/plugins.js" type="text/javascript"></script>
    
    
    <script>
        <?php echo 'var link = "'.$_GET['url'].'";';?>
    </script>
    
    <script src="vendor/detect.js" type="text/javascript"></script>
    <!--<script src="http://official.org.ua/www/orders/send.js" type="text/javascript"></script>-->
    
   

<link media="all" href="vendor/index.css" type="text/css" rel="stylesheet">


<!-- Facebook Pixel Code -->

<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '2633263253574595');
  fbq('track', 'PageView');
  fbq('track', 'Lead');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=2633263253574595&ev=PageView&noscript=1"
/></noscript>

<!-- End Facebook Pixel Code -->


</head>
<body style="background-color: rgb(241, 244, 246);" class="man">
<!--<img style="display: none;" src="http://official.org.ua/www/orders/pixel" alt="cookie" width="1" height="1" border="0">	-->
   <style>
        @media screen and (max-width: 768px) {
            .slides {
                overflow-x: hidden;
            }
        }


        .show_window{
            will-change: scroll;
            font-size: 0;
            overflow-y: visible;
            overflow-x: hidden;
            max-width: 683px;
            min-width: 350px;
            height: 250px;
            text-align: center;
            line-height: 180px;
            display: inline-block;
            white-space: nowrap;
            vertical-align: middle;
        }

        .popular{
            position: relative;
            z-index: 0;
            padding-top: 10px;
            width: 100%;
            font-family: Arial;
            font-size: 20px;
            text-align: center;
        }

        .product{
            white-space: normal;
            z-index: 2;
            position: relative;
            margin-top: 20px;
            overflow: visible;
            line-height: 170px;
            background: url("http://official.org.ua/www/orders/figure.png") no-repeat;
            background-size: 100%;
            display: inline-block;
            box-sizing: border-box;
            width: 120px;
            height: 140px;
            text-align: center;
            vertical-align: middle;
            margin-left: 8px;
            margin-right: 8px;
        }
        .popover-content{
            color: #000;
            line-height: 16px;
            font-size: 14px;
        }
        .name {
            font-family: Arial;
            line-height: 17px;
            font-size: 13px;
            word-wrap: break-word;
            width: 120px;
            height: 60px;
            position: absolute;
            bottom: -55px;
            z-index: 1;
        }
        a{
            cursor: pointer;
        }

        .product img{
            max-height: 90%;
            margin-top: -60px;
            position: relative;
            vertical-align: middle;
        }

        #first{
            margin-left: 70px;
        }

        .slides{
            overflow-y: visible;
            z-index: 1;
            position: relative;
            margin: 0 auto;
            max-width: 685px;
            min-width: 350px;
            height: 270px;
        }

        .slides:hover .show_window{
            overflow-x: scroll;
        }
        .show_window::-webkit-scrollbar{
            height: 10px;
        }

        ::-webkit-scrollbar-thumb{
            border-radius: 40px;
            width: 100px;
            z-index: 100;
            background-color: #79c26b;
        }

        .mirror{
            position: absolute;
            top: 0;
            left: 0;
            z-index: 3;
            width: 70px;
            height: 88%;
            background: linear-gradient(to left, rgba(230, 230, 230, 0), rgba(230, 230, 230, 1));
        }
        .mirror_right{
            position: absolute;
            top: 0;
            right: 0;
            z-index: 3;
            width: 70px;
            height: 88%;
            background: linear-gradient(to left, rgba(230, 230, 230, 1), rgba(230, 230, 230, 0));
        }
        .window{
            text-align: center;
            background-color: rgb(230, 230, 230);
        }
		.input{
			padding: 6px 12px;
			border: none;
		}
		.input-left{
			border-radius: 4px 0 0 4px;border-right: 1px solid #aaa;
		}
		.input-right{
			border-radius: 0 4px 4px 0px;
		}
		@media screen and (max-width:750px){
			.input{
				margin: 5px 0;
			}
			.input-left{
			border-radius: 1px;
			border:none;
		}
			.input-right{
				border-radius: 1px;
			}
		}
		
    </style>
<div class="section block-1">
    <div class="wrap">
        <img src="vendor/call-girl.png" alt="">

        <div class="top-title">
            <h2>Спасибо, Ваш заказ принят!</h2>

            <div>Наш оператор свяжется с вами в течение 15 минут</div>
            <p>Время работы нашего колл-центра - с 9:00 до 00:00.</p>
        </div>
    </div>
</div>
<!-- ЕСЛИ ЭТО ТОВАРЫ НА ОФФЕРАХ КРАСОТА И ЗДОРОВЬЕ //-->

<div class="section block-2">
    <div class="container" style="padding: 15px;">
					<div style="font-family: Lobster; font-size: 24px; color: white; text-align: center;"></div>
			<div>
				<div style="margin: 10px; font-size: 18px;">
					
					<form action="<?=$_SERVER['HTTP_REFERER']?>" id="info_form" method="POST">
						<div class="col-sm-10 col-sm-offset-1" style="padding: 0px;">
							<div class="row">
								<div class="col-sm-10 col-sm-offset-1 center text-holder" style="line-height: 40px; color: white; font-size: 18px; padding: 0px;">
									<span>Когда Вам будет удобно, чтоб мы Вам перезвонили?</br>Пожалуйста, укажите город и отделение Новой Почты, куда отправить заказ. Также можете указать e-mail для получения актуальных новостей и участия в акциях: </span>
								</div>
							</div>
                         <?php if(!$_POST['com'] && !$_POST['email']):?>
							<div class="row">
								<div class="col-sm-10 col-sm-offset-1 center" style="padding: 10px; font-size: 18px;">
								<input name="com" class="col-sm-8 col-xs-12 input input-left" maxlength='70' placeholder="Например: Киев, отделение 33" type="text">
								<input name="email" class="col-sm-4 col-xs-12 input input-right" maxlength='36' placeholder="example@gmail.com" type="text">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4 col-sm-offset-4 center button-holder" style="padding: 10px;">
                                <input type="hidden" name="hash" value="<?=$result->hash?>">
								<input class="btn btn-yellow" value="Сообщить" style="font-size: 20px ! important; padding: 2px 15px ! important;" type="submit">
								</div>
							</div>
                        <?php else:?>
                            <div class="row">
								<div class="col-sm-8 col-sm-offset-2 center" style="padding: 10px; font-size: 18px;">
                                    <span>Спасибо!</span>
								</div>
							</div>
                        <?php endif;?>
						</div>
					</form>
					
				</div>
			</div>
		</div>
</div>
    <input type="hidden" id="page_url" value="<?= $_POST['url'] ?>">

<script>
$(document).ready(function(){
	var hash = getQueryParams(document.location.search).hash;
	$('form').attr('action','http://official.org.ua/www/thankyou.php?hash='+hash);
});
function getQueryParams(qs) {
    qs = qs.split('+').join(' ');

    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
    }

    return params;
}
</script>
</body>
</html>
<?php else:?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>
    <div style="text-align: center;">Указаны неверные данные.Пожалуйста повторите вашу попытку.</div>
    <script>
        setTimeout(function(){
           window.location.href = "<?=$_SERVER['HTTP_REFERER']?>"; 
        },2000);
    </script>
</body>
</html>
<?php endif;?>
