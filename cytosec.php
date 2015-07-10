<?php

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	class CytoSec {

		protected $MessageAuthenticationCode = '';

		public function __construct() {

		}

		public function setMAC($mac) {
			$this->MessageAuthenticationCode = $mac;
		}

		public function getMAC() {
			return $this->MessageAuthenticationCode;
		}

		public function act($w, $m) {
			switch($w) {
				case 'e': return $this->enc($m); break;
				case 'd': return $this->dec($m); break;
				default:
					return $this->enc('You tried...');
					break;
			}

			foreach(array('$a', '$b', '$c', '$d', '$e', '$f', '$g', '$h', '$i', '$j', '$k', '$l', '$m'
			             ,'$n', '$o', '$p', '$q', '$r', '$s', '$t', '$u', '$v', '$w', '$x', '$y', '$z') as $zz) {
				$$zz = NULL;
				unset($$zz);
			}
		}

		private function enc($m) {
			$s = $this->s($m);
			$t = rand(1, 7);

			for($i=0; $i < $t; $i++) {
				$c = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256
				                                 ,md5($this->MessageAuthenticationCode)
				                                 ,$m
				                                 ,MCRYPT_MODE_CBC
				                                 ,md5(md5($s))
				                                 )
				                  );
			}

			return $t.substr($s, 0, 16).rtrim($c, '=').substr($s, 16, 32);
		}

		private function dec($m) {
			return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256
			                           ,md5($this->MessageAuthenticationCode)
			                           ,base64_decode(substr($m, 17, strlen($m)-33))
			                           ,MCRYPT_MODE_CBC
			                           ,md5(md5(substr($m, 1, 16).substr($m, strlen($m)-16, strlen($m))))
			                           )
			            ,"\0"
			            );
		}

		private function s($m) {
			$a = array(rand(0, 7).'C'
			          ,rand(0, 7).'Y'
			          ,rand(0, 7).'T'
			          ,rand(0, 7).'O'
			          ,rand(0, 7).'S'
			          ,rand(0, 7).'E'
			          ,rand(0, 7).'C'
			          );
			$b = '';

			foreach($a as $c) {
				$b = md5(base64_encode($c).base64_encode($m.$b));
			}

			return $b;
		}

	}

	$cs = new CytoSec();

?>

<!doctype html>
	<html>
		<head>
			<title>CytoSec::CryptoMessenger</title>
		</head>

		<body>
			<div id="cytosec">
				<header style="font-family:monospace; background:#444444; padding:4px; color:#FFFFFF;">
					<h1 style="margin-left:4px; text-shadow:-1px -1px #000000;">
						<span style="color:#0096FA;">C</span>yto
						<span style="color:#FA9600;">S</span>ec
						<span style="color:#888888; letter-spacing:-.25em;">::</span>
						<span style="font-size:0.75em;">
							<span style="color:#0096FA;">Crypto</span>
							<span style="color:#FA9600;">Messenger</span>
						</span>
					</h1>
				</header>
				<div id="content" style="padding:16px; border: 8px solid #444444;">
					<div id="fdiv">
						<form action="" method="POST" accept-charset="utf-8">
							<table>
								<tbody>
									<tr>
										<td style="width:100px;">
											<label for="mac"><a href="https://en.wikipedia.org/wiki/Message_authentication_code" target="_blank" title="Message Authentication Code">MAC</a>:</label>
										</td>
										<td style="position:relative;">
											<span id="showPass"></span>
											<input id="mac" type="password" name="mac" value="<?php $cs->getMAC(); ?>"/>
										</td>
									</tr>
									<tr>
										<td>
											<label for="way">which way?</label>
										</td>
										<td>
											<select name="way">
												<option value="e"<?php echo ((isset($_POST['way']) && $_POST['way'] == 'e')? ' selected="selected"' : ''); ?>>encode</option>
												<option value="d"<?php echo ((isset($_POST['way']) && $_POST['way'] == 'd')? ' selected="selected"' : ''); ?>>decode</option>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<textarea name="msg"><?php echo ((isset($_POST['way'])) ? $_POST['msg'] : ''); ?></textarea>
										</td>
									</tr>
									<tr>
										<td><input type="submit" name="submit" value="send"/></td>
										<td>&nbsp;</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
					<div id="rdiv">
<?php

	if(isset($_POST['way'])
	&& isset($_POST['msg'])) {
		if(isset($_POST['mac'])) $cs->setMAC($_POST['mac']);

		$b = $cs->act('e', $_POST['msg']);
		$c = $cs->act('d', $b);

		echo "\t\t\t\t\t\t". '<code id="'.$_POST['way'].'">'.nl2br($cs->act($_POST['way'], $_POST['msg'])).'</code>'. "\n";
	} else {
		echo "\t\t\t\t\t\t". '<code>Your message will be displayed here.</code>'. "\n";
	}

?>
						<div id="expl"></div>
					</div>
					<footer>
						<div id="footerwrap">
							<span id="rightToCopy">&copy; 2014 &mdash; <?php echo date("Y"); ?> &emsp; <a href="http://github.com/CytoDev" target="_blank" title="CytoDev on Github">CytoDev</a></span>
							<a id="how" href="https://github.com/CytoDev/CytoSec" target="_blank" title="README.md on Github">How does this work?</a>
						</div>
					</footer>
				</div>
			</div>
			<style type="text/css">
				body             {font:9pt/1.5em sans-serif; min-height:530px;}
				h1               {margin:7px 0 0 0; letter-spacing:normal; word-spacing:normal;}
				table            {width:100%; letter-spacing:normal; word-spacing:normal;}
				input            {width:100%; border:2px solid #444444; padding:8px; color:#404040; box-shadow:0px 1px 2px 0px inset rgba(0, 0, 0, 0.3); border-radius:1px; box-sizing:border-box; }
				a                {color:#0096FA;}
				select           {width:100%;}
				textarea         {width:100%; height:281px; resize:vertical; border:2px solid #444444; padding:8px; color:#404040; box-shadow:0px 1px 2px 0px inset rgba(0, 0, 0, 0.3); border-radius:1px; box-sizing:border-box; font-family:monospace;}
				code             {padding:5px; border-radius:5px; background:#fafafa; border:1px solid #eee; font:1em/2.3em ‘Andale Mono’,’Lucida Console’,monospace;}
				code > code      {padding:2px; border-radius:2px; border:none;}
				footer           {position:fixed; width:100%; letter-spacing:normal; word-spacing:normal; bottom:0; left:0; padding:16px 0; background-color:#EFEFEF; box-shadow:0px 0px 5px rgba(0, 0, 0, 0.5);}
				#cytosec         {max-width:960px; margin:64px auto 0 auto; background-color:#F7F7F7; box-shadow:0px 2px 2px 0px rgba(0, 0, 0, 0.3); letter-spacing:-.31em; word-spacing:-.43em;}
				#fdiv            {display:inline-block; vertical-align:top; width:50%; padding-right:8px; box-sizing:border-box;}
				#showPass        {cursor:pointer; width:40px; height:32px; filter:opacity(25%); background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD///8sYW6UAAAANnRSTlMAAQIDBAYHCBESFx4gMzQ4PD9HSElMTlBRUlxsbYWIjaCmtLe9vsDKzNDS2Nnp7/D2+vv8/f4Xyox/AAABHklEQVR42r1T2RKCMAxMKWi971vxFi88Cs3/f5pb9AFGZ/pmptNJsttNaQL914SQPkwK8ROWMu9/wzjl1frT+Xzar3lQK1I8j0RjeXpwZo/TsiGQK+DtvWE2qcVT6+3bOYakysqweWrm+HiMmfUT4aoC4IM3L5wkOL7tqlJJdbcQSRK+NAFleOfG2kB/RgIxtlmKUPOtAxCrdecEVRl4eRyG4zIYCJG8tyyjeoWLtSGqH9gYPtSJNu/ctQrFNWu2AgPyd6xhvPNpYCUArImGcC0eK5rYq9jiE1LxJz2kc+alHAUUZlrYQgoipCzh7CQ4Szgv6f7M4kONFotR8aGcT11sVk8FgerlmvXd7ijKt9s1MK6RcwytY+xdP85f7QXLGVHPwNY6CwAAAABJRU5ErkJggg==); background-repeat:no-repeat; background-color:#fff; background-position: center; display:inline-block; position:absolute; right:3px; top:3px;}
				#showPass:active {filter:opacity(75%);}
				#rdiv            {display:inline-block; vertical-align:top; max-width:50%; margin-top:72px; word-wrap:break-word; padding-left:8px; letter-spacing:normal; word-spacing:normal; box-sizing:border-box;}
				#expl            {display:block; margin-top:1em; text-align:center; height:1.2em;}
				#footerwrap      {margin:auto; max-width:960px;}
				#how             {float:right;}
			</style>
			<script type="text/javascript">
				var e  = document.getElementById('e');
				var sp = document.getElementById('showPass');

				sp.addEventListener('mousedown', function() {
					document.getElementById('mac').type = 'text';
				});

				sp.addEventListener('mouseup', function() {
					document.getElementById('mac').type = 'password';
				});

				if(e !== null) {
					var eh = e.innerHTML;
					var t0 = eh.substr(0, 1);
					var s1 = eh.substr(1, 17);
					var s2 = eh.substr(eh.length-16, eh.length);
					var m3 = eh.replace(t0, '').replace(s1, '').replace(s2, '');

					e.innerHTML = '<code class="t">'+t0+'</code>'
					            + '<code class="s">'+s1+'</code>'
					            + '<code class="m">'+m3+'</code>'
					            + '<code class="s">'+s2+'</code>'
					            ;

					document.getElementsByClassName('t')[0].addEventListener('mouseover', function(event) {
						event.target.style.background = '#0096fa';
						event.target.style.cursor = 'pointer';
						document.getElementById('expl').innerHTML = 'Times hashed';
					});
					document.getElementsByClassName('s')[0].addEventListener('mouseover', function(event) {
						document.getElementsByClassName('s')[1].style.background = '#96fa00';
						event.target.style.background = '#96fa00';
						event.target.style.cursor = 'pointer';
						document.getElementById('expl').innerHTML = 'Salt';
					});
					document.getElementsByClassName('s')[1].addEventListener('mouseover', function(event) {
						document.getElementsByClassName('s')[0].style.background = '#96fa00';
						event.target.style.background = '#96fa00';
						event.target.style.cursor = 'pointer';
						document.getElementById('expl').innerHTML = 'Salt';
					});
					document.getElementsByClassName('m')[0].addEventListener('mouseover', function(event) {
						event.target.style.background = '#fa0096';
						event.target.style.cursor = 'pointer';
						document.getElementById('expl').innerHTML = 'Message';
					});
					document.getElementsByClassName('t')[0].addEventListener('mouseout',  function(event) {
						event.target.style.background = '';
						document.getElementById('expl').innerHTML = '';
					});
					document.getElementsByClassName('s')[0].addEventListener('mouseout',  function(event) {
						document.getElementsByClassName('s')[1].style.background = '';
						event.target.style.background = '';
						document.getElementById('expl').innerHTML = '';
					});
					document.getElementsByClassName('s')[1].addEventListener('mouseout',  function(event) {
						document.getElementsByClassName('s')[0].style.background = '';
						event.target.style.background = '';
						document.getElementById('expl').innerHTML = '';
					});
					document.getElementsByClassName('m')[0].addEventListener('mouseout',  function(event) {
						event.target.style.background = '';
						document.getElementById('expl').innerHTML = '';
					});
				}
			</script>
		</body>
	</html>
